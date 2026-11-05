<?php

use App\Http\Controllers\AuthController;
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
    $allNews          = require database_path('dummy/news.php');
    $allAnnouncements = require database_path('dummy/announcements.php');

    $portals = array_filter(config('sekolah.portal', []), fn($p, $key) =>
        in_array($key, ['cbt', 'rapor', 'ppdb']),
        ARRAY_FILTER_USE_BOTH
    );

    return view('pages.home.index', [
        'news'          => array_slice($allNews, 0, 3),
        'announcements' => array_slice($allAnnouncements, 0, 3),
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
        $facilities = require database_path('dummy/facilities.php');
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
        $achievements = require database_path('dummy/achievements.php');
        return view('pages.student-life.achievements', compact('achievements'));
    })->name('student-life.achievements');
});

// ─── Galeri ──────────────────────────────────────────────────────────────
Route::get('/galeri', function () {
    $gallery = require database_path('dummy/gallery.php');
    return view('pages.gallery.index', compact('gallery'));
})->name('gallery.index');

// ─── Berita ──────────────────────────────────────────────────────────────
Route::get('/berita', function () {
    $news = require database_path('dummy/news.php');
    return view('pages.news.index', compact('news'));
})->name('news.index');

Route::get('/berita/{slug}', function (string $slug) {
    $news    = require database_path('dummy/news.php');
    $article = collect($news)->firstWhere('slug', $slug);

    if (!$article) {
        abort(404);
    }

    return view('pages.news.show', compact('article'));
})->name('news.show');

// ─── Pengumuman ──────────────────────────────────────────────────────────
Route::get('/pengumuman', function () {
    $announcements = require database_path('dummy/announcements.php');
    return view('pages.announcements.index', compact('announcements'));
})->name('announcements.index');

Route::get('/pengumuman/{slug}', function (string $slug) {
    $all          = require database_path('dummy/announcements.php');
    $announcement = collect($all)->firstWhere('slug', $slug);

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
        Route::get('/admin', function () {
            return view('pages.admin.dashboard');
        })->name('dashboard.admin');

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

        // Modul Akademik Admin
        Route::prefix('admin/akademik')->name('admin.akademik.')->group(function () {
            Route::get('/jadwal', function () {
                return view('pages.admin.akademik.jadwal');
            })->name('jadwal');

            Route::get('/kenaikan', function () {
                $students = require database_path('dummy/students_dummy.php');
                return view('pages.admin.akademik.kenaikan', compact('students'));
            })->name('kenaikan');

            Route::get('/absensi', function () {
                return view('pages.admin.akademik.absensi');
            })->name('absensi');
        });

        // Modul Akun & Sistem Admin
        Route::prefix('admin/sistem')->name('admin.sistem.')->group(function () {
            Route::get('/pengguna', function () {
                $teachers = require database_path('dummy/teachers_dummy.php');
                $students = require database_path('dummy/students_dummy.php');
                return view('pages.admin.sistem.pengguna', compact('teachers', 'students'));
            })->name('pengguna');

            Route::get('/reset-password', function () {
                return view('pages.admin.sistem.reset-password');
            })->name('reset-password');

            Route::get('/pengaturan', function () {
                return view('pages.admin.sistem.pengaturan');
            })->name('pengaturan');
        });

        Route::get('/guru-panel', function () {
            return response('<h1>Panel Guru & Wali Kelas SIAKAD</h1><p>Selamat datang, ' . auth()->user()->username . '</p><form action="' . route('logout') . '" method="POST"><input type="hidden" name="_token" value="' . csrf_token() . '"><button type="submit">Logout</button></form>');
        })->name('dashboard.guru');

        Route::get('/siswa-panel', function () {
            return response('<h1>Panel Siswa SIAKAD</h1><p>Selamat datang, ' . auth()->user()->username . '</p><form action="' . route('logout') . '" method="POST"><input type="hidden" name="_token" value="' . csrf_token() . '"><button type="submit">Logout</button></form>');
        })->name('dashboard.siswa');
    });
});
