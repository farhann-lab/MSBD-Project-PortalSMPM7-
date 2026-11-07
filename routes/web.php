<?php

use App\Http\Controllers\Admin\AdminBeritaController;
use App\Http\Controllers\Admin\AdminFasilitasController;
use App\Http\Controllers\Admin\AdminGaleriController;
use App\Http\Controllers\Admin\AdminPengumumanController;
use App\Http\Controllers\Admin\AdminPrestasiPublikController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\AuthController;
use App\Models\Berita;
use App\Models\Fasilitas;
use App\Models\Galeri;
use App\Models\Pengumuman;
use App\Models\PrestasiPublik;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes — SIAKAD SMP Muhammadiyah 7 Medan
|--------------------------------------------------------------------------
*/

// ─── Dev / Debug Showcases ──────────────────────────────────────────────
Route::get('/layout-preview', function () {
    return view('pages.layout-preview');
})->name('layout.preview');

Route::get('/components-preview', function () {
    return view('pages.components-preview');
})->name('components.preview');

// ─── Beranda ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    // Ambil berita & pengumuman published dari database, fallback ke dummy jika DB kosong
    $dbNews = Berita::published()->latest('published_at')->take(3)->get();
    if ($dbNews->isNotEmpty()) {
        $news = $dbNews->map(fn($item) => [
            'id'       => $item->id,
            'title'    => $item->judul,
            'slug'     => $item->slug,
            'summary'  => $item->summary,
            'content'  => $item->isi,
            'image'    => $item->image_url,
            'date'     => $item->formatted_date,
            'category' => 'Berita Sekolah',
        ])->all();
    } else {
        $allNews = require database_path('dummy/news.php');
        $news    = array_slice($allNews, 0, 3);
    }

    $dbAnnouncements = Pengumuman::published()->latest('tanggal')->take(3)->get();
    if ($dbAnnouncements->isNotEmpty()) {
        $announcements = $dbAnnouncements->map(fn($item) => [
            'id'        => $item->id,
            'title'     => $item->judul,
            'slug'      => $item->slug,
            'summary'   => $item->summary,
            'content'   => $item->isi,
            'date'      => $item->formatted_date,
            'tag'       => 'Pengumuman',
            'important' => false,
        ])->all();
    } else {
        $allAnnouncements = require database_path('dummy/announcements.php');
        $announcements    = array_slice($allAnnouncements, 0, 3);
    }

    $portals = array_filter(config('sekolah.portal', []), fn($p, $key) =>
        in_array($key, ['cbt', 'rapor', 'ppdb']),
        ARRAY_FILTER_USE_BOTH
    );

    return view('pages.home.index', [
        'news'          => $news,
        'announcements' => $announcements,
        'portals'       => $portals,
    ]);
})->name('home');

// ─── Profil Sekolah ──────────────────────────────────────────────────────
Route::prefix('profil')->group(function () {
    Route::get('/', function () {
        return view('pages.profile.index');
    })->name('profile.index');

    Route::get('/kepala-sekolah', function () {
        return view('pages.profile.principal');
    })->name('profile.principal');

    Route::get('/struktur', function () {
        return view('pages.profile.structure');
    })->name('profile.structure');

    Route::get('/fasilitas', function () {
        $dbFacilities = Fasilitas::published()->latest()->get();
        if ($dbFacilities->isNotEmpty()) {
            $facilities = $dbFacilities->map(fn($item) => [
                'id'          => $item->id,
                'name'        => $item->nama,
                'description' => $item->deskripsi,
                'image'       => $item->image_url,
                'icon'        => 'building-office',
            ])->all();
        } else {
            $facilities = require database_path('dummy/facilities.php');
        }

        return view('pages.profile.facilities', compact('facilities'));
    })->name('profile.facilities');
});

// ─── Direktori Guru & Siswa ──────────────────────────────────────────────
Route::get('/guru', function () {
    $allTeachers = require database_path('dummy/teachers_dummy.php');

    $q = request('q', '');
    if ($q) {
        $allTeachers = array_values(array_filter($allTeachers, fn($t) =>
            stripos($t['nama_lengkap'], $q) !== false
        ));
    }

    $mapel = request('mapel', '');
    if ($mapel) {
        $allTeachers = array_values(array_filter($allTeachers, fn($t) =>
            $t['mapel'] === $mapel
        ));
    }

    usort($allTeachers, fn($a, $b) => strcoll($a['nama_lengkap'], $b['nama_lengkap']));

    $allRaw       = require database_path('dummy/teachers_dummy.php');
    $mapelList    = collect($allRaw)->pluck('mapel')->filter()->unique()->sort()->values()->toArray();
    $mapelOptions = array_combine($mapelList, $mapelList);

    return view('pages.directory.teachers', [
        'teachers'     => $allTeachers,
        'mapelOptions' => $mapelOptions,
        'totalCount'   => count($allTeachers),
    ]);
})->name('directory.teachers');

Route::get('/siswa', function () {
    $allStudents = require database_path('dummy/students_dummy.php');

    $q = request('q', '');
    if ($q) {
        $allStudents = array_values(array_filter($allStudents, fn($s) =>
            stripos($s['nama_lengkap'], $q) !== false
        ));
    }

    $kelas = request('kelas', '');
    if ($kelas) {
        $allStudents = array_values(array_filter($allStudents, fn($s) =>
            $s['kelas'] === $kelas
        ));
    }

    usort($allStudents, fn($a, $b) => strcoll($a['nama_lengkap'], $b['nama_lengkap']));

    $allRaw       = require database_path('dummy/students_dummy.php');
    $kelasList    = collect($allRaw)->pluck('kelas')->unique()->sort()->values()->toArray();
    $kelasOptions = array_combine($kelasList, $kelasList);

    return view('pages.directory.students', [
        'students'     => $allStudents,
        'kelasOptions' => $kelasOptions,
        'totalCount'   => count($allStudents),
    ]);
})->name('directory.students');

// ─── Kesiswaan ───────────────────────────────────────────────────────────
Route::prefix('kesiswaan')->group(function () {
    Route::get('/', function () {
        return redirect()->route('student-life.extracurriculars');
    });

    Route::get('/organisasi', function () {
        $organizations = require database_path('dummy/organizations.php');
        return view('pages.student-life.organizations', compact('organizations'));
    })->name('student-life.organizations');

    Route::get('/ekstrakurikuler', function () {
        $extracurriculars = require database_path('dummy/extracurriculars.php');
        return view('pages.student-life.extracurriculars', compact('extracurriculars'));
    })->name('student-life.extracurriculars');

    Route::get('/prestasi', function () {
        $dbAchievements = PrestasiPublik::published()->latest('tanggal')->get();
        if ($dbAchievements->isNotEmpty()) {
            $achievements = $dbAchievements->map(fn($item) => [
                'id'          => $item->id,
                'name'        => $item->judul,
                'jenis'       => 'Prestasi Siswa',
                'tingkat'     => $item->tingkat ?? 'Kota',
                'tahun'       => $item->tahun,
                'description' => $item->deskripsi,
                'image'       => $item->image_url,
            ])->all();
        } else {
            $achievements = require database_path('dummy/achievements.php');
        }

        return view('pages.student-life.achievements', compact('achievements'));
    })->name('student-life.achievements');
});

// ─── Galeri ──────────────────────────────────────────────────────────────
Route::get('/galeri', function () {
    $dbGaleri = Galeri::published()->with('foto')->latest()->get();
    if ($dbGaleri->isNotEmpty()) {
        $gallery = [];
        foreach ($dbGaleri as $g) {
            if ($g->foto->isNotEmpty()) {
                foreach ($g->foto as $f) {
                    $gallery[] = [
                        'id'       => $f->id,
                        'title'    => $g->judul,
                        'image'    => $f->image_url,
                        'category' => 'Dokumentasi',
                        'date'     => $g->created_at ? $g->created_at->translatedFormat('d F Y') : null,
                    ];
                }
            } else {
                $gallery[] = [
                    'id'       => $g->id,
                    'title'    => $g->judul,
                    'image'    => null,
                    'category' => 'Dokumentasi',
                    'date'     => $g->created_at ? $g->created_at->translatedFormat('d F Y') : null,
                ];
            }
        }
    } else {
        $gallery = require database_path('dummy/gallery.php');
    }

    return view('pages.gallery.index', compact('gallery'));
})->name('gallery.index');

// ─── Berita ──────────────────────────────────────────────────────────────
Route::get('/berita', function () {
    $dbNews = Berita::published()->latest('published_at')->get();
    if ($dbNews->isNotEmpty()) {
        $news = $dbNews->map(fn($item) => [
            'id'       => $item->id,
            'title'    => $item->judul,
            'slug'     => $item->slug,
            'summary'  => $item->summary,
            'content'  => $item->isi,
            'image'    => $item->image_url,
            'date'     => $item->formatted_date,
            'category' => 'Berita Sekolah',
        ])->all();
    } else {
        $news = require database_path('dummy/news.php');
    }

    return view('pages.news.index', compact('news'));
})->name('news.index');

Route::get('/berita/{slug}', function (string $slug) {
    $dbArticle = Berita::published()->where('slug', $slug)->first();
    if ($dbArticle) {
        $article = [
            'id'       => $dbArticle->id,
            'title'    => $dbArticle->judul,
            'slug'     => $dbArticle->slug,
            'summary'  => $dbArticle->summary,
            'content'  => $dbArticle->isi,
            'image'    => $dbArticle->image_url,
            'date'     => $dbArticle->formatted_date,
            'category' => 'Berita Sekolah',
        ];
    } else {
        $news    = require database_path('dummy/news.php');
        $article = collect($news)->firstWhere('slug', $slug);
    }

    if (!$article) {
        abort(404);
    }

    return view('pages.news.show', compact('article'));
})->name('news.show');

// ─── Pengumuman ──────────────────────────────────────────────────────────
Route::get('/pengumuman', function () {
    $dbAnnouncements = Pengumuman::published()->latest('tanggal')->get();
    if ($dbAnnouncements->isNotEmpty()) {
        $announcements = $dbAnnouncements->map(fn($item) => [
            'id'        => $item->id,
            'title'     => $item->judul,
            'slug'      => $item->slug,
            'summary'   => $item->summary,
            'content'   => $item->isi,
            'date'      => $item->formatted_date,
            'tag'       => 'Pengumuman',
            'important' => false,
        ])->all();
    } else {
        $announcements = require database_path('dummy/announcements.php');
    }

    return view('pages.announcements.index', compact('announcements'));
})->name('announcements.index');

Route::get('/pengumuman/{slug}', function (string $slug) {
    // Cari dari database berdasarkan slug / format slug-id
    $dbItem = Pengumuman::published()->get()->first(fn($p) => $p->slug === $slug);
    if ($dbItem) {
        $announcement = [
            'id'        => $dbItem->id,
            'title'     => $dbItem->judul,
            'slug'      => $dbItem->slug,
            'summary'   => $dbItem->summary,
            'content'   => $dbItem->isi,
            'date'      => $dbItem->formatted_date,
            'tag'       => 'Pengumuman',
            'important' => false,
        ];
    } else {
        $all          = require database_path('dummy/announcements.php');
        $announcement = collect($all)->firstWhere('slug', $slug);
    }

    if (!$announcement) {
        abort(404);
    }

    return view('pages.announcements.show', compact('announcement'));
})->name('announcements.show');

// ─── Autentikasi SIAKAD (AUTH-001 & AUTH-002) ────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/ubah-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/ubah-password', [AuthController::class, 'updatePassword'])->name('password.change.update');

    // Protected Dashboard Placeholders (Dengan middleware force.password)
    Route::middleware(['force.password'])->group(function () {
        Route::get('/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');

        // Manajemen Akun Admin — Khusus Hak Istimewa Admin Utama [ADMIN-AKN-001]
        Route::middleware(['admin.utama'])->prefix('admin/manajemen-admin')->name('admin.manajemen-admin.')->group(function () {
            Route::get('/', [AdminManagementController::class, 'index'])->name('index');
            Route::post('/', [AdminManagementController::class, 'store'])->name('store');
            Route::patch('/{admin}/toggle-status', [AdminManagementController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{admin}/reset-password', [AdminManagementController::class, 'resetPassword'])->name('reset-password');
        });

        // Manajemen Konten Publik CMS Admin [CMS-PUB-001 & CMS-PUB-002]
        Route::prefix('admin/cms')->name('admin.cms.')->group(function () {
            Route::resource('berita', AdminBeritaController::class)->parameters(['berita' => 'berita'])->except(['show']);
            Route::resource('pengumuman', AdminPengumumanController::class)->except(['show']);
            Route::resource('fasilitas', AdminFasilitasController::class)->parameters(['fasilitas' => 'fasilitas'])->except(['show']);
            Route::resource('galeri', AdminGaleriController::class)->except(['show']);
            Route::delete('galeri/foto/{foto}', [AdminGaleriController::class, 'destroyFoto'])->name('galeri.destroy-foto');
            Route::resource('prestasi', AdminPrestasiPublikController::class)->parameters(['prestasi' => 'prestasi'])->except(['show']);
        });

        // Modul Data Master Admin (DESIGN.md 10.1 & 11)
        Route::prefix('admin/master')->name('admin.master.')->group(function () {
            Route::get('/guru', function () {
                $teachers = require database_path('dummy/teachers_dummy.php');
                return view('pages.admin.master.guru', compact('teachers'));
            })->name('guru');

            Route::get('/siswa', function () {
                $students = require database_path('dummy/students_dummy.php');
                return view('pages.admin.master.siswa', compact('students'));
            })->name('siswa');

            Route::get('/kelas', function () {
                return view('pages.admin.master.kelas');
            })->name('kelas');

            Route::get('/mapel', function () {
                return view('pages.admin.master.mapel');
            })->name('mapel');

            Route::get('/penugasan', function () {
                return view('pages.admin.master.penugasan');
            })->name('penugasan');

            Route::get('/tahun-ajaran', function () {
                return view('pages.admin.master.tahun-ajaran');
            })->name('tahun-ajaran');

            Route::get('/ekskul', function () {
                $extracurriculars = require database_path('dummy/extracurriculars.php');
                return view('pages.admin.master.ekskul', compact('extracurriculars'));
            })->name('ekskul');
        });

        Route::get('/guru-panel', function () {
            return response('<h1>Panel Guru & Wali Kelas SIAKAD</h1><p>Selamat datang, ' . auth()->user()->username . '</p><form action="' . route('logout') . '" method="POST"><input type="hidden" name="_token" value="' . csrf_token() . '"><button type="submit">Logout</button></form>');
        })->name('dashboard.guru');

        Route::get('/siswa-panel', function () {
            return response('<h1>Panel Siswa SIAKAD</h1><p>Selamat datang, ' . auth()->user()->username . '</p><form action="' . route('logout') . '" method="POST"><input type="hidden" name="_token" value="' . csrf_token() . '"><button type="submit">Logout</button></form>');
        })->name('dashboard.siswa');
    });
});
