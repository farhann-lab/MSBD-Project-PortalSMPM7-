<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pertemuan;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPemantauanAbsensiController extends Controller
{
    /**
     * Tampilkan antarmuka pemantauan absensi siswa seluruh kelas (PAS-1 s.d. PAS-5).
     * Panel ini bersifat READ-ONLY (Admin hanya memantau).
     */
    public function index(Request $request)
    {
        $tahunList = Tahun::orderBy('tahun', 'desc')->get();
        $tasmAktif = Tahun::getTasmAktif();

        // Parameter filter (PAS-1)
        $selectedTasm = $request->get('tasm', $tasmAktif);
        $selectedKelasId = $request->get('id_kelas', '');
        $selectedGuruId = $request->get('id_guru', '');
        $selectedMapelId = $request->get('id_mapel', '');
        $tanggalMulai = $request->get('tanggal_mulai', date('Y-m-01'));
        $tanggalAkhir = $request->get('tanggal_akhir', date('Y-m-d'));

        // Daftar Master untuk Dropdown Filter
        $semuaKelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $semuaGuru = Guru::aktif()->orderBy('nama')->get();
        $semuaMapel = Mapel::orderBy('kelompok')->orderBy('nama')->get();

        // Query Pertemuan Tanpa Absensi (PAS-3)
        // Pertemuan yang ada di siakad_pertemuan tetapi belum memiliki satupun baris di siakad_absensi_siswa
        $queryTanpaAbsensi = Pertemuan::with([
            'jadwal.guruMapel.guru',
            'jadwal.guruMapel.kelas',
            'jadwal.guruMapel.mapel',
        ])
            ->whereDoesntHave('absensiSiswa')
            ->whereHas('jadwal', function ($q) use ($selectedTasm, $selectedKelasId, $selectedGuruId, $selectedMapelId) {
                $q->where('tasm', $selectedTasm);
                if (!empty($selectedKelasId)) {
                    $q->whereHas('guruMapel', fn($gm) => $gm->where('id_kelas', $selectedKelasId));
                }
                if (!empty($selectedGuruId)) {
                    $q->whereHas('guruMapel', fn($gm) => $gm->where('id_guru', $selectedGuruId));
                }
                if (!empty($selectedMapelId)) {
                    $q->whereHas('guruMapel', fn($gm) => $gm->where('id_mapel', $selectedMapelId));
                }
            });

        if (!empty($tanggalMulai) && !empty($tanggalAkhir)) {
            $queryTanpaAbsensi->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
        }

        $pertemuanTanpaAbsensi = $queryTanpaAbsensi->orderBy('tanggal', 'desc')->paginate(15, ['*'], 'page_tanpa_absensi');

        // Query Sesi Pertemuan dengan Rekap Absensi (PAS-2, PAS-4)
        $queryPertemuan = Pertemuan::with([
            'jadwal.guruMapel.guru',
            'jadwal.guruMapel.kelas',
            'jadwal.guruMapel.mapel',
            'absensiSiswa',
        ])
            ->whereHas('jadwal', function ($q) use ($selectedTasm, $selectedKelasId, $selectedGuruId, $selectedMapelId) {
                $q->where('tasm', $selectedTasm);
                if (!empty($selectedKelasId)) {
                    $q->whereHas('guruMapel', fn($gm) => $gm->where('id_kelas', $selectedKelasId));
                }
                if (!empty($selectedGuruId)) {
                    $q->whereHas('guruMapel', fn($gm) => $gm->where('id_guru', $selectedGuruId));
                }
                if (!empty($selectedMapelId)) {
                    $q->whereHas('guruMapel', fn($gm) => $gm->where('id_mapel', $selectedMapelId));
                }
            });

        if (!empty($tanggalMulai) && !empty($tanggalAkhir)) {
            $queryPertemuan->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
        }

        $riwayatPertemuan = $queryPertemuan->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20, ['*'], 'page_riwayat')
            ->withQueryString();

        // Hitung Statistik Agregat Keseluruhan (PAS-4)
        $stats = DB::table('siakad_absensi_siswa as a')
            ->join('siakad_pertemuan as p', 'p.id', '=', 'a.pertemuan_id')
            ->join('siakad_jadwal as j', 'j.id', '=', 'p.jadwal_id')
            ->join('t_guru_mapel as gm', 'gm.id', '=', 'j.id_guru_mapel')
            ->where('j.tasm', $selectedTasm)
            ->when(!empty($selectedKelasId), fn($q) => $q->where('gm.id_kelas', $selectedKelasId))
            ->when(!empty($selectedGuruId), fn($q) => $q->where('gm.id_guru', $selectedGuruId))
            ->when(!empty($selectedMapelId), fn($q) => $q->where('gm.id_mapel', $selectedMapelId))
            ->when(!empty($tanggalMulai) && !empty($tanggalAkhir), fn($q) => $q->whereBetween('p.tanggal', [$tanggalMulai, $tanggalAkhir]))
            ->selectRaw("
                COUNT(a.id) as total_catatan,
                SUM(CASE WHEN a.status = 'H' THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN a.status = 'S' THEN 1 ELSE 0 END) as total_sakit,
                SUM(CASE WHEN a.status = 'I' THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN a.status = 'A' THEN 1 ELSE 0 END) as total_alpa
            ")
            ->first();

        $totalCatatan = $stats->total_catatan ?? 0;
        $totalHadir = (int) ($stats->total_hadir ?? 0);
        $totalSakit = (int) ($stats->total_sakit ?? 0);
        $totalIzin = (int) ($stats->total_izin ?? 0);
        $totalAlpa = (int) ($stats->total_alpa ?? 0);

        $persentaseHadir = $totalCatatan > 0 ? round(($totalHadir / $totalCatatan) * 100, 1) : 0;

        return view('pages.admin.akademik.absensi', compact(
            'tahunList',
            'tasmAktif',
            'selectedTasm',
            'selectedKelasId',
            'selectedGuruId',
            'selectedMapelId',
            'tanggalMulai',
            'tanggalAkhir',
            'semuaKelas',
            'semuaGuru',
            'semuaMapel',
            'pertemuanTanpaAbsensi',
            'riwayatPertemuan',
            'totalCatatan',
            'totalHadir',
            'totalSakit',
            'totalIzin',
            'totalAlpa',
            'persentaseHadir'
        ));
    }
}
