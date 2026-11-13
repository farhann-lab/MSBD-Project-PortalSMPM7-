<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Cache TTL: 2 jam (hemat CPU pada hosting 1-core sesuai spec TASKS.md BE-HOME-001).
     */
    private const CACHE_TTL = 7200;

    public function index(): View
    {
        $news          = $this->getLatestNews();
        $announcements = $this->getLatestAnnouncements();
        $statistik     = $this->getStatistik();
        $periodeAktif  = $this->getPeriodeAktif();
        $portals       = $this->getPortals();

        return view('pages.home.index', compact(
            'news',
            'announcements',
            'statistik',
            'periodeAktif',
            'portals',
        ));
    }

    // ─── Private Helpers ────────────────────────────────────────────────────────

    private function getLatestNews(): array
    {
        try {
            $dbNews = Berita::published()->latest('published_at')->take(3)->get();
            if ($dbNews->isNotEmpty()) {
                return $dbNews->map(fn($item) => [
                    'id'       => $item->id,
                    'title'    => $item->judul,
                    'slug'     => $item->slug,
                    'summary'  => $item->summary,
                    'content'  => $item->isi,
                    'image'    => $item->image_url,
                    'date'     => $item->formatted_date,
                    'category' => 'Berita Sekolah',
                ])->all();
            }
        } catch (\Throwable) {
            // Fallback ke dummy jika koneksi/query gagal
        }

        $allNews = require database_path('dummy/news.php');
        return array_slice($allNews, 0, 3);
    }

    private function getLatestAnnouncements(): array
    {
        try {
            $dbAnnouncements = Pengumuman::published()->latest('tanggal')->take(3)->get();
            if ($dbAnnouncements->isNotEmpty()) {
                return $dbAnnouncements->map(fn($item) => [
                    'id'        => $item->id,
                    'title'     => $item->judul,
                    'slug'      => $item->slug,
                    'summary'   => $item->summary,
                    'content'   => $item->isi,
                    'date'      => $item->formatted_date,
                    'tag'       => 'Pengumuman',
                    'important' => false,
                ])->all();
            }
        } catch (\Throwable) {
            // Fallback
        }

        $allAnnouncements = require database_path('dummy/announcements.php');
        return array_slice($allAnnouncements, 0, 3);
    }

    /**
     * Statistik sekolah dari database (di-cache).
     * - Tabel/kolom yang tersedia dihitung dari DB.
     * - Yang tidak tersedia memakai nilai fallback dari config.
     * - is_dummy = true bila ada angka yang masih berasal dari fallback.
     * - Jika terjadi error, hasil TIDAK di-cache (exception keluar dari closure).
     */
    private function getStatistik(): array
    {
        $fallback = [
            'siswa'  => config('sekolah.statistik.siswa', '1.000+'),
            'guru'   => config('sekolah.statistik.guru', '40+'),
            'kelas'  => config('sekolah.statistik.kelas', '20'),
            'ekskul' => config('sekolah.statistik.ekskul', 8),
        ];

        try {
            return Cache::remember('home.statistik', self::CACHE_TTL, function () use ($fallback) {
                $siswa = $this->hitung('m_siswa', 'stat_data', 'A');
                $guru  = $this->hitung('m_guru', 'stat_data', 'A');
                $kelas = $this->hitung('m_kelas');

                // Ekskul masih dari config (tabel siakad_ekstra_publik masih kosong)
                $ekskul = $fallback['ekskul'];

                return [
                    'siswa'    => $this->format($siswa ?? $fallback['siswa']),
                    'guru'     => $this->format($guru ?? $fallback['guru']),
                    'kelas'    => $this->format($kelas ?? $fallback['kelas']),
                    'ekskul'   => $this->format($ekskul),
                    'is_dummy' => in_array(null, [$siswa, $guru, $kelas], true),
                ];
            });
        } catch (\Throwable $e) {
            report($e);

            return [
                'siswa'    => $this->format($fallback['siswa']),
                'guru'     => $this->format($fallback['guru']),
                'kelas'    => $this->format($fallback['kelas']),
                'ekskul'   => $this->format($fallback['ekskul']),
                'is_dummy' => true,
            ];
        }
    }

    /**
     * Hitung baris pada tabel. Return null jika tabel tidak ada.
     */
    private function hitung(string $tabel, ?string $kolom = null, ?string $nilai = null): ?int
    {
        if (!Schema::hasTable($tabel)) {
            return null;
        }

        $query = DB::table($tabel);

        if ($kolom !== null && Schema::hasColumn($tabel, $kolom)) {
            $query->where($kolom, $nilai);
        }

        return (int) $query->count();
    }

    /**
     * Format angka ribuan (1250 -> 1.250). Nilai non-angka (mis. "1.000+") dibiarkan.
     */
    private function format(int|string $angka): string
    {
        return is_numeric($angka)
            ? number_format((int) $angka, 0, ',', '.')
            : (string) $angka;
    }

    /**
     * Baca periode / tahun ajaran aktif dari tabel `tahun` (RKM).
     */
    private function getPeriodeAktif(): array
    {
        return Cache::remember('home.periode_aktif', self::CACHE_TTL, function () {
            $default = [
                'tahun_ajaran'  => '2026/2027',
                'semester'      => '1 (Ganjil)',
                'label_lengkap' => 'Tahun Ajaran 2026/2027 Semester 1 (Ganjil)',
            ];

            try {
                if (!Schema::hasTable('tahun')) {
                    return $default;
                }

                $row = DB::table('tahun')
                    ->where('aktif', 'Y')
                    ->orderByDesc('id')
                    ->first();

                if (!$row) {
                    $row = DB::table('tahun')->orderByDesc('id')->first();
                }

                if ($row) {
                    $kode     = (string) $row->tahun;
                    $thAwal   = (int) substr($kode, 0, 4);
                    $isGanjil = (substr($kode, 4, 1) === '1');

                    $thAjaran = $thAwal . '/' . ($thAwal + 1);
                    $smtLabel = $isGanjil ? '1 (Ganjil)' : '2 (Genap)';

                    return [
                        'tahun_ajaran'  => $thAjaran,
                        'semester'      => $smtLabel,
                        'label_lengkap' => "Tahun Ajaran {$thAjaran} Semester {$smtLabel}",
                    ];
                }
            } catch (\Throwable) {
                // Fallback ke default
            }

            return $default;
        });
    }

    /**
     * Ambil 3 pintasan portal dari config (CBT, Rapor, PPDB).
     */
    private function getPortals(): array
    {
        return array_filter(
            config('sekolah.portal', []),
            fn($p, $key) => in_array($key, ['cbt', 'rapor', 'ppdb']),
            ARRAY_FILTER_USE_BOTH
        );
    }
}