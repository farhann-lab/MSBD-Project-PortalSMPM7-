<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard admin dengan data statistik nyata dari database.
     */
    public function index(): View
    {
        // 1. Data Periode / Tahun Ajaran Aktif
        $periodeAktif = $this->getPeriodeAktif();

        // 2. Data Statistik Utama
        $stats = $this->getDashboardStats($periodeAktif['tahun_kode'] ?? null);

        return view('pages.admin.dashboard', compact('stats', 'periodeAktif'));
    }

    /**
     * Mengambil dan memformat tahun ajaran / semester aktif dari database.
     * Mengutamakan tabel 'tahun' atau view 'v_periode_aktif'.
     */
    public function getPeriodeAktif(): array
    {
        $default = [
            'tahun_kode'   => '20261',
            'tahun_ajaran' => '2026/2027',
            'semester'     => '1 (Ganjil)',
            'semester_tipe'=> 'Ganjil',
            'label_lengkap'=> 'Tahun Ajaran 2026/2027 Semester 1 (Ganjil)',
            'label_topbar' => '2026/2027 Semester 1',
            'is_aktif'     => true,
            'kepala_sekolah'=> 'M. Reza Akbar, S.Pd',
        ];

        try {
            $row = null;

            // Coba dari tabel 'tahun' (skema RKM aktif = 'Y')
            if (Schema::hasTable('tahun')) {
                $row = DB::table('tahun')
                    ->where('aktif', 'Y')
                    ->orderByDesc('id')
                    ->first();
            }

            // Fallback jika tidak ada yang aktif = 'Y', ambil record terakhir
            if (!$row && Schema::hasTable('tahun')) {
                $row = DB::table('tahun')->orderByDesc('id')->first();
            }

            if ($row) {
                $kodeStr = (string) $row->tahun;
                $thAwal = (int) substr($kodeStr, 0, 4);
                $smtDigit = substr($kodeStr, 4, 1);

                $thAjaran = $thAwal . '/' . ($thAwal + 1);
                $isGanjil = ($smtDigit === '1');
                $smtLabel = $isGanjil ? '1 (Ganjil)' : '2 (Genap)';
                $smtTipe  = $isGanjil ? 'Ganjil' : 'Genap';

                return [
                    'tahun_kode'    => $kodeStr,
                    'tahun_ajaran'  => $thAjaran,
                    'semester'      => $smtLabel,
                    'semester_tipe' => $smtTipe,
                    'label_lengkap' => "Tahun Ajaran {$thAjaran} Semester {$smtLabel}",
                    'label_topbar'  => "{$thAjaran} Semester " . ($isGanjil ? '1' : '2'),
                    'is_aktif'      => ($row->aktif === 'Y'),
                    'kepala_sekolah'=> $row->nama_kepsek ?? $default['kepala_sekolah'],
                ];
            }
        } catch (\Throwable $e) {
            // Gunakan fallback default jika terjadi kegagalan koneksi
        }

        return $default;
    }

    /**
     * Menghitung statistik riil untuk kartu dashboard (Guru, Siswa, Rombel/Kelas, Mapel).
     */
    private function getDashboardStats(?string $tahunKode): array
    {
        $stats = [
            'total_guru'       => 0,
            'guru_aktif'       => 0,
            'persen_guru_aktif'=> 100,
            'total_siswa'      => 0,
            'siswa_aktif'      => 0,
            'total_kelas'      => 0,
            'total_mapel'      => 0,
        ];

        try {
            // Hitung data Guru dari m_guru
            if (Schema::hasTable('m_guru')) {
                $guruCount = DB::table('m_guru')
                    ->selectRaw("COUNT(*) as total, SUM(CASE WHEN stat_data = 'A' THEN 1 ELSE 0 END) as aktif")
                    ->first();

                if ($guruCount) {
                    $stats['total_guru'] = (int) $guruCount->total;
                    $stats['guru_aktif'] = (int) ($guruCount->aktif ?? $guruCount->total);
                    $stats['persen_guru_aktif'] = $stats['total_guru'] > 0
                        ? round(($stats['guru_aktif'] / $stats['total_guru']) * 100)
                        : 100;
                }
            }

            // Hitung data Siswa dari m_siswa
            if (Schema::hasTable('m_siswa')) {
                $siswaCount = DB::table('m_siswa')
                    ->selectRaw("COUNT(*) as total, SUM(CASE WHEN stat_data = 'A' THEN 1 ELSE 0 END) as aktif")
                    ->first();

                if ($siswaCount) {
                    $stats['total_siswa'] = (int) ($siswaCount->aktif ?? $siswaCount->total);
                    $stats['siswa_aktif'] = (int) ($siswaCount->aktif ?? $siswaCount->total);
                }
            }

            // Hitung data Rombel / Kelas dari m_kelas
            if (Schema::hasTable('m_kelas')) {
                $stats['total_kelas'] = DB::table('m_kelas')->count();
            }

            // Hitung data Mapel dari m_mapel
            if (Schema::hasTable('m_mapel')) {
                $stats['total_mapel'] = DB::table('m_mapel')->count();
            }
        } catch (\Throwable $e) {
            // Jika ada query yang gagal, kembalikan nilai fallback
        }

        return $stats;
    }
}
