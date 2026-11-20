<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use App\Support\RoleDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan data periode aktif secara global ke seluruh view admin
        View::composer(['components.admin.*', 'components.layouts.admin', 'pages.admin.*'], function ($view) {
            $default = [
                'tahun_kode'    => '20261',
                'tahun_ajaran'  => '2026/2027',
                'semester'      => '1 (Ganjil)',
                'semester_tipe' => 'Ganjil',
                'label_lengkap' => 'Tahun Ajaran 2026/2027 Semester 1 (Ganjil)',
                'label_topbar'  => '2026/2027 Semester 1',
                'is_aktif'      => true,
            ];

            try {
                if (Schema::connection(RoleDatabase::admin())->hasTable('tahun')) {
                    $row = DB::connection(RoleDatabase::admin())->table('tahun')->where('aktif', 'Y')->orderByDesc('id')->first();
                    if (!$row) {
                        $row = DB::connection(RoleDatabase::admin())->table('tahun')->orderByDesc('id')->first();
                    }

                    if ($row) {
                        $kodeStr = (string) $row->tahun;
                        $thAwal = (int) substr($kodeStr, 0, 4);
                        $smtDigit = substr($kodeStr, 4, 1);

                        $thAjaran = $thAwal . '/' . ($thAwal + 1);
                        $isGanjil = ($smtDigit === '1');

                        $default = [
                            'tahun_kode'    => $kodeStr,
                            'tahun_ajaran'  => $thAjaran,
                            'semester'      => $isGanjil ? '1 (Ganjil)' : '2 (Genap)',
                            'semester_tipe' => $isGanjil ? 'Ganjil' : 'Genap',
                            'label_lengkap' => "Tahun Ajaran {$thAjaran} Semester " . ($isGanjil ? '1 (Ganjil)' : '2 (Genap)'),
                            'label_topbar'  => "{$thAjaran} Semester " . ($isGanjil ? '1' : '2'),
                            'is_aktif'      => ($row->aktif === 'Y'),
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Fallback aman
            }

            $view->with('globalPeriodeAktif', $default);
        });

        // Daftarkan Observer untuk pencatatan log aktivitas CMS (CMS-LOG-001)
        \App\Models\Berita::observe(\App\Observers\CmsActivityObserver::class);
        \App\Models\Pengumuman::observe(\App\Observers\CmsActivityObserver::class);
        \App\Models\Fasilitas::observe(\App\Observers\CmsActivityObserver::class);
        \App\Models\Galeri::observe(\App\Observers\CmsActivityObserver::class);
        \App\Models\GaleriFoto::observe(\App\Observers\CmsActivityObserver::class);
        \App\Models\PrestasiPublik::observe(\App\Observers\CmsActivityObserver::class);
    }
}
