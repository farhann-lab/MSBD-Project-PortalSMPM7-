<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminJadwalController extends Controller
{
    /**
     * Tampilkan daftar dan grid jadwal pelajaran per rombel (JDW-1).
     */
    public function index(Request $request)
    {
        $tahunList = Tahun::orderBy('tahun', 'desc')->get();
        $tasmAktif = Tahun::getTasmAktif();

        $selectedTasm = $request->get('tasm', $tasmAktif);
        $selectedKelasId = $request->get('id_kelas');

        $semuaKelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        // Default ke kelas pertama jika tidak dipilih
        if (!$selectedKelasId && $semuaKelas->isNotEmpty()) {
            $selectedKelasId = $semuaKelas->first()->id;
        }

        // Ambil jadwal aktif untuk kelas & tasm terpilih
        $jadwalList = collect();
        $selectedKelas = null;

        if ($selectedKelasId) {
            $selectedKelas = Kelas::find($selectedKelasId);

            $jadwalList = Jadwal::with(['guruMapel.guru', 'guruMapel.mapel', 'guruMapel.kelas'])
                ->where('tasm', $selectedTasm)
                ->where('aktif', 1)
                ->whereHas('guruMapel', function ($q) use ($selectedKelasId) {
                    $q->where('id_kelas', $selectedKelasId);
                })
                ->orderBy('jam_mulai')
                ->get();
        }

        // Grouping berdasarkan hari untuk Grid
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwalGrid = [];
        foreach ($hariList as $hari) {
            $jadwalGrid[$hari] = $jadwalList->where('hari', $hari)->sortBy('jam_mulai');
        }

        // Ambil penugasan guru mapel yang tersedia pada semester dan kelas terpilih untuk dropdown modal
        $penugasanTersedia = collect();
        if ($selectedKelasId) {
            $penugasanTersedia = GuruMapel::with(['guru', 'mapel'])
                ->where('tasm', $selectedTasm)
                ->where('id_kelas', $selectedKelasId)
                ->get()
                ->sortBy(function ($gm) {
                    return $gm->mapel->nama ?? '';
                });
        }

        return view('pages.admin.akademik.jadwal', compact(
            'tahunList',
            'tasmAktif',
            'selectedTasm',
            'selectedKelasId',
            'selectedKelas',
            'semuaKelas',
            'hariList',
            'jadwalList',
            'jadwalGrid',
            'penugasanTersedia'
        ));
    }

    /**
     * Endpoint API internal untuk mengambil penugasan guru mapel per kelas & tasm.
     */
    public function getPenugasanByKelas(Request $request)
    {
        $tasm = $request->get('tasm');
        $idKelas = $request->get('id_kelas');

        $penugasan = GuruMapel::with(['guru', 'mapel'])
            ->where('tasm', $tasm)
            ->where('id_kelas', $idKelas)
            ->get()
            ->map(function ($gm) {
                return [
                    'id' => $gm->id,
                    'guru_nama' => $gm->guru->nama ?? 'Guru Tidak Diketahui',
                    'mapel_nama' => $gm->mapel->nama ?? 'Mapel Tidak Diketahui',
                    'mapel_kode' => $gm->mapel->kode ?? '',
                ];
            });

        return response()->json($penugasan);
    }

    /**
     * Simpan jadwal baru (JDW-2, JDW-3).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tasm' => ['required', 'string', 'regex:/^[0-9]{4}[12]$/'],
            'id_guru_mapel' => ['required', 'integer', 'exists:t_guru_mapel,id'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih akhir daripada jam mulai.',
            'id_guru_mapel.required' => 'Mata pelajaran dan guru pengampu wajib dipilih.',
        ]);

        $jamMulai = $validated['jam_mulai'] . ':00';
        $jamSelesai = $validated['jam_selesai'] . ':00';

        // Validasi bentrok di level aplikasi sebelum eksekusi procedure/trigger
        $penugasan = GuruMapel::findOrFail($validated['id_guru_mapel']);
        $bentrokCheck = $this->cekBentrok(
            $validated['tasm'],
            $penugasan->id_guru,
            $penugasan->id_kelas,
            $validated['hari'],
            $jamMulai,
            $jamSelesai
        );

        if ($bentrokCheck['bentrok']) {
            return back()->withInput()->with('error', $bentrokCheck['pesan']);
        }

        try {
            // Panggil Stored Procedure sp_simpan_jadwal
            DB::statement('CALL sp_simpan_jadwal(?, ?, ?, ?, ?)', [
                $validated['id_guru_mapel'],
                $validated['tasm'],
                $validated['hari'],
                $jamMulai,
                $jamSelesai,
            ]);

            return back()->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
        } catch (Throwable $e) {
            $errorMessage = $this->parseDatabaseError($e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan jadwal: ' . $errorMessage);
        }
    }

    /**
     * Perbarui jadwal yang sudah ada (JDW-4).
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'tasm' => ['required', 'string', 'regex:/^[0-9]{4}[12]$/'],
            'id_guru_mapel' => ['required', 'integer', 'exists:t_guru_mapel,id'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih akhir daripada jam mulai.',
            'id_guru_mapel.required' => 'Mata pelajaran dan guru pengampu wajib dipilih.',
        ]);

        $jamMulai = $validated['jam_mulai'] . ':00';
        $jamSelesai = $validated['jam_selesai'] . ':00';

        $penugasan = GuruMapel::findOrFail($validated['id_guru_mapel']);
        $bentrokCheck = $this->cekBentrok(
            $validated['tasm'],
            $penugasan->id_guru,
            $penugasan->id_kelas,
            $validated['hari'],
            $jamMulai,
            $jamSelesai,
            $jadwal->id
        );

        if ($bentrokCheck['bentrok']) {
            return back()->withInput()->with('error', $bentrokCheck['pesan']);
        }

        try {
            DB::statement('CALL sp_ubah_jadwal(?, ?, ?, ?, ?, ?)', [
                $jadwal->id,
                $validated['id_guru_mapel'],
                $validated['tasm'],
                $validated['hari'],
                $jamMulai,
                $jamSelesai,
            ]);

            return back()->with('success', 'Jadwal pelajaran berhasil diperbarui.');
        } catch (Throwable $e) {
            $errorMessage = $this->parseDatabaseError($e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui jadwal: ' . $errorMessage);
        }
    }

    /**
     * Hapus atau nonaktifkan jadwal (JDW-5, JDW-6).
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Cek apakah jadwal sudah memiliki catatan pertemuan di siakad_pertemuan
        $hasPertemuan = DB::table('siakad_pertemuan')->where('jadwal_id', $jadwal->id)->exists();

        try {
            if ($hasPertemuan) {
                // Jangan hard delete jika sudah ada riwayat absensi/pertemuan
                $jadwal->update(['aktif' => 0]);
                return back()->with('success', 'Jadwal memiliki riwayat pertemuan, sehingga telah dinonaktifkan (diarsipkan).');
            } else {
                $jadwal->delete();
                return back()->with('success', 'Jadwal pelajaran berhasil dihapus permanen.');
            }
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Salin seluruh jadwal dari periode lain ke periode terpilih (JDW-7, JDW-8).
     */
    public function salin(Request $request)
    {
        $validated = $request->validate([
            'tasm_asal' => ['required', 'string', 'regex:/^[0-9]{4}[12]$/'],
            'tasm_tujuan' => ['required', 'string', 'regex:/^[0-9]{4}[12]$/', 'different:tasm_asal'],
        ], [
            'tasm_tujuan.different' => 'Periode asal dan periode tujuan harus berbeda.',
        ]);

        try {
            $result = DB::select('CALL sp_salin_jadwal(?, ?)', [
                $validated['tasm_asal'],
                $validated['tasm_tujuan'],
            ]);

            $res = $result[0] ?? null;
            $disalin = $res->disalin ?? 0;
            $tanpaPenugasan = $res->tanpa_penugasan_tujuan ?? 0;
            $bentrok = $res->dilewati_karena_bentrok ?? 0;

            $pesan = "Proses salin jadwal selesai: {$disalin} jadwal berhasil disalin.";
            if ($tanpaPenugasan > 0) {
                $pesan .= " ({$tanpaPenugasan} dilewati karena penugasan guru belum disiapkan di periode tujuan).";
            }
            if ($bentrok > 0) {
                $pesan .= " ({$bentrok} dilewati karena bentrok jam).";
            }

            return back()->with('success', $pesan);
        } catch (Throwable $e) {
            $errorMessage = $this->parseDatabaseError($e->getMessage());
            return back()->with('error', 'Gagal menyalin jadwal: ' . $errorMessage);
        }
    }

    /**
     * Helper deteksi bentrok jadwal di level aplikasi.
     */
    protected function cekBentrok($tasm, $idGuru, $idKelas, $hari, $jamMulai, $jamSelesai, $excludeJadwalId = null)
    {
        $query = Jadwal::with(['guruMapel.guru', 'guruMapel.kelas', 'guruMapel.mapel'])
            ->where('tasm', $tasm)
            ->where('aktif', 1)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai);

        if ($excludeJadwalId) {
            $query->where('id', '!=', $excludeJadwalId);
        }

        $bentrokJadwal = $query->get();

        foreach ($bentrokJadwal as $existing) {
            $gm = $existing->guruMapel;
            if (!$gm) continue;

            // Bentrok Guru
            if ($gm->id_guru == $idGuru) {
                $guruNama = $gm->guru->nama ?? 'Guru bersangkutan';
                $kelasNama = $gm->kelas->nama ?? 'Kelas lain';
                return [
                    'bentrok' => true,
                    'pesan' => "Bentrok Jadwal Guru: {$guruNama} sudah memiliki jadwal mengajar di {$kelasNama} pada hari {$hari} jam {$existing->jam_mulai} - {$existing->jam_selesai}.",
                ];
            }

            // Bentrok Kelas
            if ($gm->id_kelas == $idKelas) {
                $mapelNama = $gm->mapel->nama ?? 'Mapel lain';
                return [
                    'bentrok' => true,
                    'pesan' => "Bentrok Jadwal Kelas: Kelas tersebut sudah memiliki jadwal {$mapelNama} pada hari {$hari} jam {$existing->jam_mulai} - {$existing->jam_selesai}.",
                ];
            }
        }

        return ['bentrok' => false, 'pesan' => ''];
    }

    /**
     * Parse pesan SQL/Trigger menjadi pesan yang ramah pengguna.
     */
    protected function parseDatabaseError($rawMessage)
    {
        if (str_contains($rawMessage, 'Bentrok jadwal guru atau kelas')) {
            return 'Bentrok jadwal guru atau kelas terdeteksi pada jam dan hari tersebut.';
        }
        if (str_contains($rawMessage, 'Jam mulai harus lebih kecil dari jam selesai')) {
            return 'Jam mulai harus lebih awal dari jam selesai.';
        }
        if (str_contains($rawMessage, 'Penugasan dan periode jadwal tidak cocok')) {
            return 'Penugasan guru mapel tidak sesuai dengan periode semester aktif.';
        }
        return $rawMessage;
    }
}

