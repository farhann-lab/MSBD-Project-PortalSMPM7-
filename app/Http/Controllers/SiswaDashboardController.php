<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Tahun;
use App\Models\Walikelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    /**
     * Tampilkan Dashboard & Portal Akademik Siswa (DSH-7, DSH-8, SSW-1 s.d. SSW-7).
     * Strictly Read-Only dan Terisolasi untuk siswa bersangkutan (DIR-8).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isSiswa()) {
            abort(403, 'Akses khusus siswa.');
        }

        $siswa = $user->siswa;
        $tasmAktif = Tahun::getTasmAktif();
        $taAktif = Tahun::getTaAktif();

        // 1. Data Penempatan Kelas Siswa & Wali Kelas
        $penempatan = $siswa ? $siswa->kelasAktif($taAktif) : null;
        $kelasId = $penempatan ? $penempatan->id_kelas : null;
        $kelasNama = $penempatan ? ($penempatan->kelas->nama ?? '-') : '-';

        $waliKelas = null;
        if ($kelasId) {
            $waliKelasRecord = Walikelas::with('guru')
                ->where('id_kelas', $kelasId)
                ->where('tasm', $tasmAktif)
                ->first();
            $waliKelas = $waliKelasRecord ? ($waliKelasRecord->guru->nama ?? '-') : '-';
        }

        // 2. Deteksi Hari Ini & Jadwal Pelajaran Hari Ini
        $mapHariIndo = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        $hariIniIndex = Carbon::now()->dayOfWeekIso;
        $namaHariIni = $mapHariIndo[$hariIniIndex] ?? 'Senin';

        $jadwalHariIni = collect();
        $jadwalMingguan = collect();

        if ($kelasId) {
            // Jadwal Hari Ini
            $jadwalHariIni = Jadwal::with(['guruMapel.mapel', 'guruMapel.guru'])
                ->where('tasm', $tasmAktif)
                ->where('aktif', true)
                ->where('hari', $namaHariIni)
                ->whereHas('guruMapel', function ($q) use ($kelasId) {
                    $q->where('id_kelas', $kelasId);
                })
                ->orderBy('jam_mulai', 'asc')
                ->get();

            // Jadwal Mingguan (Dikelompokkan per hari)
            $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $semuaJadwal = Jadwal::with(['guruMapel.mapel', 'guruMapel.guru'])
                ->where('tasm', $tasmAktif)
                ->where('aktif', true)
                ->whereHas('guruMapel', function ($q) use ($kelasId) {
                    $q->where('id_kelas', $kelasId);
                })
                ->orderBy('jam_mulai', 'asc')
                ->get();

            $jadwalMingguan = $semuaJadwal->groupBy('hari')->sortBy(function ($items, $key) use ($urutanHari) {
                $pos = array_search($key, $urutanHari);
                return $pos !== false ? $pos : 99;
            });
        }

        // 3. Ringkasan Akumulasi Absensi Pribadi Siswa (DIR-8: Strictly Private)
        $riwayatAbsensi = collect();
        $totalPertemuan = 0;
        $totalHadir = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlpa = 0;
        $persentaseKehadiran = 100;

        if ($siswa) {
            $riwayatAbsensi = AbsensiSiswa::where('id_siswa', $siswa->id)
                ->whereHas('pertemuan.jadwal', function ($q) use ($tasmAktif) {
                    $q->where('tasm', $tasmAktif);
                })
                ->get();

            $totalPertemuan = $riwayatAbsensi->count();
            $totalHadir = $riwayatAbsensi->where('status', 'H')->count();
            $totalSakit = $riwayatAbsensi->where('status', 'S')->count();
            $totalIzin = $riwayatAbsensi->where('status', 'I')->count();
            $totalAlpa = $riwayatAbsensi->where('status', 'A')->count();

            $persentaseKehadiran = $totalPertemuan > 0 
                ? round(($totalHadir / $totalPertemuan) * 100, 1) 
                : 100;
        }

        // 4. Repositori Materi Belajar untuk Kelas Siswa
        $materiList = collect();
        $countMateri = 0;
        if ($kelasId) {
            $queryMateri = Materi::with(['guru', 'mapel'])
                ->where('status', 'aktif')
                ->whereHas('materiKelas.guruMapel', function ($q) use ($kelasId, $tasmAktif) {
                    $q->where('id_kelas', $kelasId)
                      ->where('tasm', $tasmAktif);
                });

            $countMateri = $queryMateri->count();
            $materiList = $queryMateri->orderBy('created_at', 'desc')->take(4)->get();
        }

        return view('pages.siswa.dashboard', compact(
            'siswa',
            'kelasNama',
            'waliKelas',
            'tasmAktif',
            'taAktif',
            'namaHariIni',
            'jadwalHariIni',
            'jadwalMingguan',
            'totalPertemuan',
            'totalHadir',
            'totalSakit',
            'totalIzin',
            'totalAlpa',
            'persentaseKehadiran',
            'countMateri',
            'materiList'
        ));
    }
}
