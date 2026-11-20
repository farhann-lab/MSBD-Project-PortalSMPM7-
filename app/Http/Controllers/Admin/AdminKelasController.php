<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\Tahun;
use App\Models\Walikelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminKelasController extends Controller
{
    /**
     * Halaman Utama: Menampilkan Tab Data Rombel Kelas, Penempatan Siswa, dan Penetapan Wali Kelas.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'rombel'); // 'rombel', 'penempatan', 'walikelas'
        
        $tahunList = Tahun::orderBy('tahun', 'desc')->get();
        $tasmAktif = Tahun::getTasmAktif();
        $taAktif = Tahun::getTaAktif();

        // 1. Data Rombel Kelas
        $qKelas = trim($request->get('q_kelas', ''));
        $tingkatFilter = $request->get('tingkat', '');

        $kelasQuery = Kelas::withCount(['kelasSiswa as total_siswa' => function ($q) use ($taAktif) {
            $q->where('ta', $taAktif);
        }])->with(['waliKelas' => function ($q) use ($tasmAktif) {
            $q->where('tasm', $tasmAktif)->with('guru');
        }]);

        if (!empty($qKelas)) {
            $kelasQuery->where('nama', 'like', "%{$qKelas}%");
        }
        if (!empty($tingkatFilter)) {
            $kelasQuery->where('tingkat', $tingkatFilter);
        }

        $kelasList = $kelasQuery->orderBy('tingkat')->orderBy('nama')->get();

        // 2. Data Penempatan Siswa
        $selectedTa = $request->get('ta', $taAktif);
        $selectedKelasId = $request->get('id_kelas', $kelasList->first()?->id ?? null);
        $selectedKelas = $selectedKelasId ? Kelas::find($selectedKelasId) : null;

        $qSiswa = trim($request->get('q_siswa', ''));

        // Siswa yang sudah berada di kelas terpilih pada TA terpilih
        $siswaRombel = collect();
        if ($selectedKelasId) {
            $siswaRombel = KelasSiswa::with('siswa')
                ->where('id_kelas', $selectedKelasId)
                ->where('ta', $selectedTa)
                ->whereHas('siswa', function ($q) use ($qSiswa) {
                    $q->where('stat_data', 'A');
                    if (!empty($qSiswa)) {
                        $q->where(function ($sub) use ($qSiswa) {
                            $sub->where('nama', 'like', "%{$qSiswa}%")
                                ->orWhere('nis', 'like', "%{$qSiswa}%");
                        });
                    }
                })
                ->get();
        }

        // Siswa aktif yang belum memiliki rombel di TA terpilih
        $assignedSiswaIds = KelasSiswa::where('ta', $selectedTa)->pluck('id_siswa');
        $siswaBelumQuery = Siswa::where('stat_data', 'A')
            ->whereNotIn('id', $assignedSiswaIds);

        if (!empty($qSiswa)) {
            $siswaBelumQuery->where(function ($sub) use ($qSiswa) {
                $sub->where('nama', 'like', "%{$qSiswa}%")
                    ->orWhere('nis', 'like', "%{$qSiswa}%");
            });
        }
        $siswaBelumDitempatkan = $siswaBelumQuery->orderBy('nama')->paginate(20, ['*'], 'page_belum')->withQueryString();

        // 3. Data Penetapan Wali Kelas
        $selectedTasm = $request->get('tasm', $tasmAktif);
        $waliKelasList = Walikelas::with(['guru', 'kelas'])
            ->where('tasm', $selectedTasm)
            ->get();

        $semuaGuru = Guru::aktif()->orderBy('nama')->get();

        // Guru yang belum jadi walikelas di tasm ini
        $assignedGuruIds = $waliKelasList->pluck('id_guru')->filter();
        $guruTersedia = $semuaGuru->whereNotIn('id', $assignedGuruIds);

        // Kelas yang belum punya walikelas di tasm ini
        $assignedKelasIds = $waliKelasList->pluck('id_kelas')->filter();
        $kelasTanpaWali = $kelasList->whereNotIn('id', $assignedKelasIds);

        return view('pages.admin.master.kelas.index', compact(
            'tab',
            'kelasList',
            'tahunList',
            'tasmAktif',
            'taAktif',
            'selectedTa',
            'selectedKelasId',
            'selectedKelas',
            'siswaRombel',
            'siswaBelumDitempatkan',
            'selectedTasm',
            'waliKelasList',
            'semuaGuru',
            'guruTersedia',
            'kelasTanpaWali',
            'tingkatFilter',
            'qKelas',
            'qSiswa'
        ));
    }

    /**
     * Simpan Kelas Baru
     */
    public function storeKelas(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:m_kelas,nama'],
            'tingkat' => ['required', 'integer', 'in:7,8,9'],
        ], [
            'nama.unique' => 'Nama rombel kelas sudah ada.',
            'tingkat.in' => 'Tingkat kelas harus 7, 8, atau 9.',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.master.kelas.index', ['tab' => 'rombel'])
            ->with('success', "Rombel Kelas {$validated['nama']} berhasil ditambahkan.");
    }

    /**
     * Update Kelas
     */
    public function updateKelas(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('m_kelas', 'nama')->ignore($kelas->id)],
            'tingkat' => ['required', 'integer', 'in:7,8,9'],
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.master.kelas.index', ['tab' => 'rombel'])
            ->with('success', "Data Rombel Kelas {$kelas->nama} berhasil diperbarui.");
    }

    /**
     * Hapus Kelas (KLS-4: Cek dependensi)
     */
    public function destroyKelas(Kelas $kelas)
    {
        // Cek apakah kelas memiliki siswa
        $hasSiswa = KelasSiswa::where('id_kelas', $kelas->id)->exists();
        // Cek apakah kelas memiliki wali kelas
        $hasWali = Walikelas::where('id_kelas', $kelas->id)->exists();

        if ($hasSiswa || $hasWali) {
            return redirect()->route('admin.master.kelas.index', ['tab' => 'rombel'])
                ->with('error', "Kelas {$kelas->nama} tidak dapat dihapus karena masih terikat dengan data siswa atau riwayat wali kelas.");
        }

        $nama = $kelas->nama;
        $kelas->delete();

        return redirect()->route('admin.master.kelas.index', ['tab' => 'rombel'])
            ->with('success', "Kelas {$nama} berhasil dihapus.");
    }

    /**
     * Penempatan Siswa ke Kelas (Tunggal / Massal - PNS-2, PNS-3, PNS-5)
     */
    public function storePenempatan(Request $request)
    {
        $validated = $request->validate([
            'id_kelas' => ['required', 'exists:m_kelas,id'],
            'ta' => ['required', 'digits:4'],
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['exists:m_siswa,id'],
        ]);

        $idKelas = $validated['id_kelas'];
        $ta = $validated['ta'];
        $siswaIds = array_unique($validated['siswa_ids']);

        $count = 0;
        DB::transaction(function () use ($idKelas, $ta, $siswaIds, &$count) {
            foreach ($siswaIds as $idSiswa) {
                // PNS-5: Cegah duplikasi di TA yang sama
                $exists = KelasSiswa::where('id_siswa', $idSiswa)
                    ->where('ta', $ta)
                    ->exists();

                if (!$exists) {
                    KelasSiswa::create([
                        'id_kelas' => $idKelas,
                        'id_siswa' => $idSiswa,
                        'ta' => $ta,
                    ]);
                    $count++;
                }
            }
        });

        return redirect()->route('admin.master.kelas.index', [
            'tab' => 'penempatan',
            'ta' => $ta,
            'id_kelas' => $idKelas
        ])->with('success', "Sebanyak {$count} siswa berhasil ditempatkan ke dalam kelas.");
    }

    /**
     * Pindah Rombel Siswa (PNS-4)
     */
    public function pindahRombel(Request $request)
    {
        $validated = $request->validate([
            'id_kelas_siswa' => ['required', 'exists:t_kelas_siswa,id'],
            'id_kelas_tujuan' => ['required', 'exists:m_kelas,id'],
        ]);

        $penempatan = KelasSiswa::findOrFail($validated['id_kelas_siswa']);
        $kelasTujuan = Kelas::findOrFail($validated['id_kelas_tujuan']);

        // Cegah jika kelas tujuan sama
        if ($penempatan->id_kelas == $kelasTujuan->id) {
            return back()->with('error', 'Siswa sudah berada di kelas tersebut.');
        }

        $penempatan->update([
            'id_kelas' => $kelasTujuan->id,
        ]);

        return redirect()->route('admin.master.kelas.index', [
            'tab' => 'penempatan',
            'ta' => $penempatan->ta,
            'id_kelas' => $kelasTujuan->id
        ])->with('success', "Siswa berhasil dipindahkan ke rombel {$kelasTujuan->nama}.");
    }

    /**
     * Cabut / Hapus Siswa dari Penempatan Rombel
     */
    public function destroyPenempatan(KelasSiswa $penempatan)
    {
        $ta = $penempatan->ta;
        $idKelas = $penempatan->id_kelas;
        $penempatan->delete();

        return redirect()->route('admin.master.kelas.index', [
            'tab' => 'penempatan',
            'ta' => $ta,
            'id_kelas' => $idKelas
        ])->with('success', 'Siswa berhasil dikeluarkan dari rombel kelas.');
    }

    /**
     * Simpan Penetapan Wali Kelas (PWK-1..4)
     */
    public function storeWaliKelas(Request $request)
    {
        $validated = $request->validate([
            'tasm' => ['required', 'string', 'size:5'],
            'id_guru' => ['required', 'exists:m_guru,id'],
            'id_kelas' => ['required', 'exists:m_kelas,id'],
        ]);

        $tasm = $validated['tasm'];
        $idGuru = $validated['id_guru'];
        $idKelas = $validated['id_kelas'];

        // PWK-2: 1 Kelas hanya boleh 1 Wali Kelas pada semester yang sama
        $kelasPunyaWali = Walikelas::where('tasm', $tasm)
            ->where('id_kelas', $idKelas)
            ->exists();
        if ($kelasPunyaWali) {
            return back()->with('error', 'Kelas ini sudah memiliki Wali Kelas pada semester tersebut.');
        }

        // PWK-3: 1 Guru hanya boleh jadi Wali Kelas untuk 1 kelas pada semester yang sama
        $guruSudahMenjabat = Walikelas::where('tasm', $tasm)
            ->where('id_guru', $idGuru)
            ->exists();
        if ($guruSudahMenjabat) {
            return back()->with('error', 'Guru yang dipilih sudah menjadi Wali Kelas di kelas lain pada semester ini.');
        }

        Walikelas::create([
            'tasm' => $tasm,
            'id_guru' => $idGuru,
            'id_kelas' => $idKelas,
        ]);

        return redirect()->route('admin.master.kelas.index', [
            'tab' => 'walikelas',
            'tasm' => $tasm,
        ])->with('success', 'Penetapan Wali Kelas berhasil disimpan.');
    }

    /**
     * Hapus Penetapan Wali Kelas
     */
    public function destroyWaliKelas(Walikelas $walikelas)
    {
        $tasm = $walikelas->tasm;
        $walikelas->delete();

        return redirect()->route('admin.master.kelas.index', [
            'tab' => 'walikelas',
            'tasm' => $tasm,
        ])->with('success', 'Penetapan Wali Kelas berhasil dihapus.');
    }

    /**
     * Salin Wali Kelas Antar-Periode (PWK-6)
     */
    public function salinWaliKelas(Request $request)
    {
        $validated = $request->validate([
            'tasm_asal' => ['required', 'string', 'size:5'],
            'tasm_tujuan' => ['required', 'string', 'size:5', 'different:tasm_asal'],
        ], [
            'tasm_tujuan.different' => 'Periode semester tujuan harus berbeda dengan periode semester asal.',
        ]);

        $tasmAsal = $validated['tasm_asal'];
        $tasmTujuan = $validated['tasm_tujuan'];

        // Coba panggil Stored Procedure sp_salin_walikelas jika tersedia, fallback Eloquent aman
        try {
            DB::statement('CALL sp_salin_walikelas(?, ?)', [$tasmAsal, $tasmTujuan]);
        } catch (\Throwable $e) {
            // Fallback manual jika SP tidak ada atau error
            $asalList = Walikelas::where('tasm', $tasmAsal)->get();
            if ($asalList->isEmpty()) {
                return back()->with('error', "Tidak ada data wali kelas pada periode asal ({$tasmAsal}) untuk disalin.");
            }

            DB::transaction(function () use ($asalList, $tasmTujuan) {
                foreach ($asalList as $w) {
                    $exists = Walikelas::where('tasm', $tasmTujuan)
                        ->where('id_kelas', $w->id_kelas)
                        ->exists();

                    $guruOccupied = Walikelas::where('tasm', $tasmTujuan)
                        ->where('id_guru', $w->id_guru)
                        ->exists();

                    if (!$exists && !$guruOccupied) {
                        Walikelas::create([
                            'tasm' => $tasmTujuan,
                            'id_guru' => $w->id_guru,
                            'id_kelas' => $w->id_kelas,
                        ]);
                    }
                }
            });
        }

        return redirect()->route('admin.master.kelas.index', [
            'tab' => 'walikelas',
            'tasm' => $tasmTujuan,
        ])->with('success', "Wali kelas berhasil disalin dari periode {$tasmAsal} ke {$tasmTujuan}.");
    }
}
