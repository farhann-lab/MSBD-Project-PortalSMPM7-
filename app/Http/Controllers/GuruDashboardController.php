<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Pertemuan;
use App\Models\Tahun;
use App\Models\Walikelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruDashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Interaktif Guru & Monitoring Wali Kelas (DSH-5, DSH-6, WKL-1..8).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403, 'Akses khusus guru.');
        }

        $guru = $user->guru;
        $tasmAktif = Tahun::getTasmAktif();
        $taAktif = Tahun::getTaAktif();

        // 1. Data Penugasan Mengajar (Rombel & Mapel) pada semester aktif
        $penugasanList = GuruMapel::with(['kelas', 'mapel'])
            ->where('id_guru', $guru->id)
            ->where('tasm', $tasmAktif)
            ->get();

        $rombelCount = $penugasanList->pluck('id_kelas')->unique()->count();
        $rombelNames = $penugasanList->pluck('kelas.nama')->filter()->unique()->values();

        // 2. Deteksi Hari Ini & Jadwal Mengajar Hari Ini
        $mapHariIndo = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        $hariIniIndex = Carbon::now()->dayOfWeekIso; // 1 (Senin) .. 7 (Minggu)
        $namaHariIni = $mapHariIndo[$hariIniIndex] ?? 'Senin';

        $jadwalHariIni = Jadwal::with(['guruMapel.kelas', 'guruMapel.mapel'])
            ->whereHas('guruMapel', function ($q) use ($guru, $tasmAktif) {
                $q->where('id_guru', $guru->id)
                  ->where('tasm', $tasmAktif);
            })
            ->where('hari', $namaHariIni)
            ->where('aktif', true)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Periksa apakah jadwal hari ini sudah diisi absensi pertemuannya untuk tanggal hari ini
        $tanggalHariIni = Carbon::today()->toDateString();
        $pertemuanHariIniMap = Pertemuan::whereIn('jadwal_id', $jadwalHariIni->pluck('id'))
            ->where('tanggal', $tanggalHariIni)
            ->get()
            ->keyBy('jadwal_id');

        // 3. Notifikasi Pertemuan Belum Diisi Absensinya (v_pertemuan_tanpa_absensi)
        $pertemuanTanpaAbsensi = DB::table('v_pertemuan_tanpa_absensi')
            ->where('id_guru', $guru->id)
            ->get();
        $countTanpaAbsensi = $pertemuanTanpaAbsensi->count();

        // 4. Hitung Total Materi yang Dikelola Guru
        $countMateri = Materi::where('id_guru', $guru->id)->count();

        // 5. Materi Terakhir Diunggah (hingga 3 item)
        $materiTerakhir = Materi::with('mapel')
            ->where('id_guru', $guru->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // 6. Deteksi Peran Wali Kelas pada Semester Aktif (WKL-1 s.d. WKL-8)
        $waliKelasRecord = Walikelas::with('kelas')
            ->where('id_guru', $guru->id)
            ->where('tasm', $tasmAktif)
            ->first();

        $isWaliKelas = !is_null($waliKelasRecord);
        $kelasPerwalian = $waliKelasRecord ? ($waliKelasRecord->kelas->nama ?? '-') : null;
        $idKelasPerwalian = $waliKelasRecord ? $waliKelasRecord->id_kelas : null;

        // Data statistik rombel binaan wali kelas
        $perwalianData = null;
        if ($isWaliKelas && $idKelasPerwalian) {
            // Jumlah siswa di kelas perwalian
            $siswaPerwalian = DB::table('t_kelas_siswa as ks')
                ->join('m_siswa as s', 'ks.id_siswa', '=', 's.id')
                ->where('ks.id_kelas', $idKelasPerwalian)
                ->where('ks.ta', $taAktif)
                ->select('s.id', 's.nama', 's.nisn', 's.jk')
                ->get();

            $totalSiswa = $siswaPerwalian->count();
            $totalLaki = $siswaPerwalian->where('jk', 'L')->count();
            $totalPerempuan = $siswaPerwalian->where('jk', 'P')->count();

            // Rekap kehadiran rombel binaan via view v_rekap_absensi_kelas
            $rekapAbsensi = DB::table('v_rekap_absensi_kelas')
                ->where('id_kelas', $idKelasPerwalian)
                ->where('tasm', $tasmAktif)
                ->selectRaw('
                    COALESCE(SUM(hadir), 0) as total_hadir,
                    COALESCE(SUM(sakit), 0) as total_sakit,
                    COALESCE(SUM(izin), 0) as total_izin,
                    COALESCE(SUM(tanpa_keterangan), 0) as total_alpa
                ')
                ->first();

            $totalSesi = ($rekapAbsensi->total_hadir ?? 0) +
                         ($rekapAbsensi->total_sakit ?? 0) +
                         ($rekapAbsensi->total_izin ?? 0) +
                         ($rekapAbsensi->total_alpa ?? 0);

            $persenKehadiran = $totalSesi > 0 
                ? round((($rekapAbsensi->total_hadir ?? 0) / $totalSesi) * 100, 1) 
                : 100;

            // Siswa dengan alpa / ketidakhadiran tertinggi
            $siswaAlpaTinggi = DB::table('v_rekap_absensi_kelas as v')
                ->join('m_siswa as s', 'v.id_siswa', '=', 's.id')
                ->where('v.id_kelas', $idKelasPerwalian)
                ->where('v.tasm', $tasmAktif)
                ->groupBy('v.id_siswa', 's.nama', 's.nisn')
                ->selectRaw('
                    v.id_siswa,
                    s.nama,
                    s.nisn,
                    SUM(v.tanpa_keterangan) as jml_alpa,
                    SUM(v.sakit) as jml_sakit,
                    SUM(v.izin) as jml_izin
                ')
                ->having('jml_alpa', '>', 0)
                ->orderByDesc('jml_alpa')
                ->take(5)
                ->get();

            $perwalianData = [
                'kelas_nama' => $kelasPerwalian,
                'total_siswa' => $totalSiswa,
                'total_laki' => $totalLaki,
                'total_perempuan' => $totalPerempuan,
                'rekap' => $rekapAbsensi,
                'persen_kehadiran' => $persenKehadiran,
                'siswa_alpa_tinggi' => $siswaAlpaTinggi,
            ];
        }

        return view('pages.guru.dashboard', compact(
            'guru',
            'tasmAktif',
            'taAktif',
            'rombelCount',
            'rombelNames',
            'namaHariIni',
            'jadwalHariIni',
            'pertemuanHariIniMap',
            'countTanpaAbsensi',
            'countMateri',
            'materiTerakhir',
            'isWaliKelas',
            'kelasPerwalian',
            'perwalianData'
        ));
    }
}
