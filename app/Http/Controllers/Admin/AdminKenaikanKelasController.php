<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\LogAktivitas;
use App\Models\Siswa;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminKenaikanKelasController extends Controller
{
    /**
     * Halaman Pratinjau Kenaikan Kelas & Kelulusan (KNK-1 s.d KNK-9, Alur 7.13).
     * Membaca status kenaikan dari t_naikkelas RKM (READ-ONLY).
     */
    public function index(Request $request)
    {
        // 1. Ambil daftar semester genap yang tersedia dari t_naikkelas atau tahun
        $tasmGenapList = DB::table('t_naikkelas')
            ->select('ta')
            ->whereRaw("ta REGEXP '^[0-9]{4}2$'")
            ->distinct()
            ->orderBy('ta', 'desc')
            ->pluck('ta');

        if ($tasmGenapList->isEmpty()) {
            $tasmGenapList = collect(['20252', '20242']);
        }

        $selectedTasm = $request->get('tasm', $tasmGenapList->first());
        $taAsal = (int) substr($selectedTasm, 0, 4);
        $taTujuan = $taAsal + 1;

        // 2. Data master kelas
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $kelasTingkat8 = $kelasList->where('tingkat', 8);
        $kelasTingkat9 = $kelasList->where('tingkat', 9);

        $selectedKelasAsalId = $request->get('kelas_asal_id', '');
        $selectedKelasTujuanId = $request->get('kelas_tujuan_id', '');
        $filterStatus = $request->get('filter_status', 'semua'); // 'semua', 'naik', 'lulus', 'evaluasi'

        // 3. Query Siswa pada TA Asal dengan status kenaikan dari t_naikkelas (READ-ONLY)
        $query = DB::table('t_kelas_siswa as ks')
            ->join('m_siswa as s', 's.id', '=', 'ks.id_siswa')
            ->join('m_kelas as k', 'k.id', '=', 'ks.id_kelas')
            ->leftJoin('t_naikkelas as nk', function ($join) use ($selectedTasm) {
                // Sesuai ARCHITECTURE.md 7.2 & 8.6: hanya baca id_siswa, ta, naik (catatan tidak dibaca)
                $join->on('nk.id_siswa', '=', 's.id')
                    ->where('nk.ta', '=', $selectedTasm)
                    ->whereRaw('nk.id = (SELECT MAX(n2.id) FROM t_naikkelas n2 WHERE n2.id_siswa = s.id AND n2.ta = ?)', [$selectedTasm]);
            })
            ->leftJoin('t_kelas_siswa as ks_tujuan', function ($join) use ($taTujuan) {
                $join->on('ks_tujuan.id_siswa', '=', 's.id')
                    ->where('ks_tujuan.ta', '=', $taTujuan);
            })
            ->leftJoin('m_kelas as k_sudah', 'k_sudah.id', '=', 'ks_tujuan.id_kelas')
            ->where('ks.ta', '=', $taAsal)
            ->whereRaw('ks.id = (SELECT MAX(y.id) FROM t_kelas_siswa y WHERE y.id_siswa = s.id AND y.ta = ?)', [$taAsal])
            ->where('s.stat_data', '!=', 'M'); // Hanya siswa aktif non-mutasi

        if (!empty($selectedKelasAsalId)) {
            $query->where('ks.id_kelas', $selectedKelasAsalId);
        }

        $rawStudents = $query->select([
            's.id as id_siswa',
            's.nama',
            's.nis',
            's.nisn',
            'k.id as kelas_asal_id',
            'k.nama as kelas_asal_nama',
            'k.tingkat as tingkat_asal',
            'nk.naik as status_rkm',
            'ks_tujuan.id as id_penempatan_tujuan',
            'k_sudah.id as id_kelas_tujuan_sudah',
            'k_sudah.nama as nama_kelas_tujuan_sudah',
        ])
        ->orderBy('k.tingkat')
        ->orderBy('k.nama')
        ->orderBy('s.nama')
        ->get();

        // 4. Transformasi data kandidat & penetapan rekomendasi rombel tujuan
        $students = $rawStudents->map(function ($s) use ($kelasList, $selectedKelasTujuanId) {
            $tingkat = (int) $s->tingkat_asal;
            $statusRkm = $s->status_rkm;
            $sudahDitempatkan = !empty($s->id_penempatan_tujuan);

            if ($tingkat === 9) {
                // KNK-9: Siswa kelas 9 yang lulus ditandai "Lulus, tidak ditempatkan" (jangan ubah status RKM)
                $statusType = 'lulus';
                $statusLabel = 'Lulus, tidak ditempatkan';
                $isEligible = false;
                $targetKelasId = null;
                $placementStatus = 'LULUS';
                $placementLabel = 'Lulus (Alumni)';
            } elseif ($statusRkm === 'Y') {
                $statusType = 'naik';
                $statusLabel = 'Naik Kelas';
                $isEligible = true;

                if ($sudahDitempatkan) {
                    $placementStatus = 'SUDAH';
                    $placementLabel = 'Sudah di ' . $s->nama_kelas_tujuan_sudah;
                    $targetKelasId = $s->id_kelas_tujuan_sudah;
                } else {
                    $placementStatus = 'SIAP';
                    $placementLabel = 'Siap Ditempatkan';
                    // Rekomendasi rombel tujuan (pertahankan pola nama rombel, e.g. VII-1 -> VIII-1)
                    $targetKelasId = $selectedKelasTujuanId ?: $this->guessTargetKelas($tingkat, $s->kelas_asal_nama, $kelasList);
                }
            } elseif ($statusRkm === 'N') {
                // Siswa tidak naik kelas
                $statusType = 'tinggal';
                $statusLabel = 'Tinggal Kelas';
                $isEligible = false;
                $placementStatus = 'TINGGAL';
                $placementLabel = 'Tinggal di Rombel Asal';
                $targetKelasId = null;
            } else {
                // KNK-6: Siswa tanpa status kenaikan ditandai peringatan (Belum Ditetapkan di RKM)
                $statusType = 'evaluasi';
                $statusLabel = 'Belum Ditetapkan';
                $isEligible = false;
                $placementStatus = 'BELUM_DITETAPKAN';
                $placementLabel = 'Menunggu Input RKM';
                $targetKelasId = null;
            }

            return (object) [
                'id_siswa' => $s->id_siswa,
                'nama' => $s->nama,
                'nis' => $s->nis,
                'nisn' => $s->nisn,
                'kelas_asal_id' => $s->kelas_asal_id,
                'kelas_asal_nama' => $s->kelas_asal_nama,
                'tingkat_asal' => $tingkat,
                'status_rkm' => $statusRkm,
                'status_type' => $statusType,
                'status_label' => $statusLabel,
                'is_eligible' => $isEligible,
                'is_placed' => $sudahDitempatkan,
                'target_kelas_id' => $targetKelasId,
                'target_kelas_nama' => $s->nama_kelas_tujuan_sudah,
                'placement_status' => $placementStatus,
                'placement_label' => $placementLabel,
            ];
        });

        // Filter status jika dipilih
        if ($filterStatus === 'naik') {
            $students = $students->where('status_type', 'naik');
        } elseif ($filterStatus === 'lulus') {
            $students = $students->where('status_type', 'lulus');
        } elseif ($filterStatus === 'evaluasi') {
            $students = $students->whereIn('status_type', ['tinggal', 'evaluasi']);
        }

        // 5. Metrik ringkasan
        $totalSiswa = $rawStudents->count();
        $totalNaik = $rawStudents->where('tingkat_asal', '<', 9)->where('status_rkm', 'Y')->count();
        $totalLulus = $rawStudents->where('tingkat_asal', 9)->where('status_rkm', 'Y')->count();
        $totalEvaluasi = $rawStudents->where('status_rkm', '!=', 'Y')->count();
        $totalSiap = $students->where('placement_status', 'SIAP')->count();
        $totalSudah = $rawStudents->whereNotNull('id_penempatan_tujuan')->count();

        // Cari rombel terpilih saat ini
        $selectedKelasAsal = $selectedKelasAsalId ? $kelasList->firstWhere('id', $selectedKelasAsalId) : null;

        return view('pages.admin.akademik.kenaikan', compact(
            'tasmGenapList',
            'selectedTasm',
            'taAsal',
            'taTujuan',
            'kelasList',
            'kelasTingkat8',
            'kelasTingkat9',
            'selectedKelasAsalId',
            'selectedKelasAsal',
            'selectedKelasTujuanId',
            'filterStatus',
            'students',
            'totalSiswa',
            'totalNaik',
            'totalLulus',
            'totalEvaluasi',
            'totalSiap',
            'totalSudah'
        ));
    }

    /**
     * Eksekusi Penempatan Siswa ke Tahun Ajaran Baru dalam 1 Transaksi Penuh (KNK-7, ARCHITECTURE 8.8).
     * Dilarang melakukan penyimpanan otomatis sebelum konfirmasi admin.
     */
    public function eksekusi(Request $request)
    {
        $validated = $request->validate([
            'tasm_asal' => ['required', 'string', 'regex:/^[0-9]{4}2$/'],
            'ta_tujuan' => ['required', 'integer'],
            'konfirmasi' => ['required', 'in:1'],
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['integer', 'exists:m_siswa,id'],
            'kelas_tujuan' => ['required', 'array'],
        ], [
            'konfirmasi.required' => 'Anda harus mencentang kotak konfirmasi sebelum mengeksekusi kenaikan kelas.',
            'siswa_ids.required' => 'Pilih setidaknya satu siswa yang memenuhi syarat kenaikan kelas.',
            'siswa_ids.min' => 'Pilih setidaknya satu siswa untuk ditempatkan.',
        ]);

        $tasmAsal = $validated['tasm_asal'];
        $taTujuan = (int) $validated['ta_tujuan'];
        $taAsal = (int) substr($tasmAsal, 0, 4);
        $siswaIds = array_unique($validated['siswa_ids']);
        $kelasTujuanMap = $validated['kelas_tujuan'];

        // Validasi tahun tujuan harus tepat 1 tahun di atas tahun asal
        if ($taTujuan !== $taAsal + 1) {
            return redirect()->back()
                ->with('error', 'Tahun tujuan harus tepat 1 tahun di atas tahun ajaran asal.')
                ->withInput();
        }

        $lockName = "siakad:penempatan:{$taTujuan}";
        $disimpan = 0;
        $dilewati = 0;

        try {
            DB::transaction(function () use ($lockName, $tasmAsal, $taAsal, $taTujuan, $siswaIds, $kelasTujuanMap, &$disimpan, &$dilewati) {
                // Per ARCHITECTURE.md 8.8: ambil user-level lock untuk mencegah konflik penempatan konkuren
                $locked = DB::select("SELECT GET_LOCK(?, 5) as locked", [$lockName]);
                if (empty($locked) || $locked[0]->locked != 1) {
                    throw new \RuntimeException('Gagal memperoleh lock transaksi penempatan. Silakan coba lagi.');
                }

                foreach ($siswaIds as $idSiswa) {
                    $targetKelasId = $kelasTujuanMap[$idSiswa] ?? null;

                    // 1. Validasi target kelas ada
                    if (!$targetKelasId || !Kelas::where('id', $targetKelasId)->exists()) {
                        $dilewati++;
                        continue;
                    }

                    // 2. Periksa status RKM: hanya siswa dengan keputusan naik 'Y' pada t_naikkelas
                    $naikRecord = DB::table('t_naikkelas')
                        ->where('id_siswa', $idSiswa)
                        ->where('ta', $tasmAsal)
                        ->orderBy('id', 'desc')
                        ->first(['id', 'naik']);

                    if (!$naikRecord || $naikRecord->naik !== 'Y') {
                        $dilewati++;
                        continue;
                    }

                    // 3. Pastikan siswa bukan kelas 9 (lulusan tidak ditempatkan di TA baru per KNK-9)
                    $lastClass = DB::table('t_kelas_siswa as ks')
                        ->join('m_kelas as k', 'k.id', '=', 'ks.id_kelas')
                        ->where('ks.id_siswa', $idSiswa)
                        ->where('ks.ta', $taAsal)
                        ->orderBy('ks.id', 'desc')
                        ->first(['k.tingkat']);

                    if ($lastClass && (int) $lastClass->tingkat === 9) {
                        $dilewati++;
                        continue; // Siswa kelas 9 lulus tidak ditempatkan
                    }

                    // 4. Pastikan belum ditempatkan pada TA tujuan (idempotent / cegah duplikasi)
                    $alreadyPlaced = DB::table('t_kelas_siswa')
                        ->where('id_siswa', $idSiswa)
                        ->where('ta', $taTujuan)
                        ->exists();

                    if ($alreadyPlaced) {
                        $dilewati++;
                        continue;
                    }

                    // 5. Eksekusi penempatan baru ke t_kelas_siswa
                    KelasSiswa::create([
                        'id_kelas' => $targetKelasId,
                        'id_siswa' => $idSiswa,
                        'ta' => $taTujuan,
                    ]);

                    $disimpan++;
                }

                // Lepas lock setelah transaksi selesai
                DB::select("DO RELEASE_LOCK(?)", [$lockName]);
            });

            // Catat log aktivitas jika ada siswa yang berhasil ditempatkan
            if ($disimpan > 0) {
                LogAktivitas::catat(
                    auth()->id(),
                    'KENAIKAN_KELAS',
                    't_kelas_siswa',
                    $taTujuan,
                    "Berhasil menempatkan {$disimpan} siswa dari periode {$tasmAsal} ke TA {$taTujuan}."
                );
            }

            $pesan = "Kenaikan kelas berhasil diproses! {$disimpan} siswa berhasil ditempatkan ke Tahun Ajaran {$taTujuan}.";
            if ($dilewati > 0) {
                $pesan .= " ({$dilewati} siswa dilewati karena sudah ditempatkan atau tidak memenuhi syarat).";
            }

            return redirect()->route('admin.akademik.kenaikan', [
                'tasm' => $tasmAsal,
                'kelas_asal_id' => $request->get('kelas_asal_id'),
            ])->with('success', $pesan);

        } catch (\Throwable $e) {
            DB::select("DO RELEASE_LOCK(?)", [$lockName]);
            Log::error('Gagal memproses kenaikan kelas: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem saat mengeksekusi kenaikan kelas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Helper tebakan rombel tujuan berdasarkan nama rombel asal (KNK-3: Mempertahankan kelompok rombel asal).
     * Contoh: "VII-1 (Fullday)" -> "VIII-1 (Fullday)", "VIII-4 (Reguler)" -> "IX-4 (Fullday)" / "IX-5 (Reguler)".
     */
    private function guessTargetKelas(int $tingkatAsal, string $namaAsal, $kelasList): ?int
    {
        $tingkatTujuan = $tingkatAsal + 1;
        if ($tingkatTujuan > 9) {
            return null; // Kelas 9 lulus
        }

        // Ambil nomor rombel dan program dari nama kelas asal (misal VII-1 -> 1)
        if (preg_match('/(?:VII|VIII|IX)-(\d+)/i', $namaAsal, $matches)) {
            $nomorRombel = $matches[1];
            // Cari kelas di tingkat tujuan dengan nomor rombel yang sama
            $kandidat = $kelasList->first(function ($k) use ($tingkatTujuan, $nomorRombel) {
                return (int) $k->tingkat === $tingkatTujuan && str_contains($k->nama, "-{$nomorRombel}");
            });

            if ($kandidat) {
                return $kandidat->id;
            }
        }

        // Fallback: kelas pertama pada tingkat tujuan
        return $kelasList->where('tingkat', $tingkatTujuan)->first()?->id;
    }
}
