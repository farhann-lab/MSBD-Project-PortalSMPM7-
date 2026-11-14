<?php

use App\Http\Controllers\Admin\AdminBeritaController;
use App\Http\Controllers\Admin\AdminFasilitasController;
use App\Http\Controllers\Admin\AdminGaleriController;
use App\Http\Controllers\Admin\AdminGuruController;
use App\Http\Controllers\Admin\AdminImporSiswaController;
use App\Http\Controllers\Admin\AdminPengumumanController;
use App\Http\Controllers\Admin\AdminPrestasiPublikController;
use App\Http\Controllers\Admin\AdminSiswaController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Models\Berita;
use App\Models\Fasilitas;
use App\Models\Galeri;
use App\Models\Pengumuman;
use App\Models\PrestasiPublik;
use App\Models\PublikGuru;
use App\Models\PublikSiswa;
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
Route::get('/', [HomeController::class, 'index'])->name('home');

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
    $q     = trim(request('q', ''));
    $mapel = trim(request('mapel', ''));

    // Coba ambil dari Database View v_publik_guru
    $guruQuery = PublikGuru::query();
    if (!empty($q)) {
        $guruQuery->search($q);
    }
    if (!empty($mapel)) {
        $guruQuery->mapel($mapel);
    }
    $dbTeachers = $guruQuery->orderBy('nama')->paginate(16)->withQueryString();

    if ($dbTeachers->total() > 0) {
        $teachers = $dbTeachers->through(fn($item) => [
            'id'           => $item->id,
            'nama_lengkap' => $item->nama,
            'mapel'        => $item->mata_pelajaran ?: 'Tenaga Kependidikan',
            'jabatan'      => null,
        ]);

        // Daftar opsi mata pelajaran dari database view
        $allMapels = PublikGuru::whereNotNull('mata_pelajaran')
            ->where('mata_pelajaran', '!=', '')
            ->pluck('mata_pelajaran')
            ->flatMap(fn($str) => array_map('trim', explode(',', $str)))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
        $mapelOptions = array_combine($allMapels, $allMapels);
        $totalCount   = $dbTeachers->total();
    } else {
        // Fallback jika database view kosong / kondisi pencarian khusus
        $allTeachers = require database_path('dummy/teachers_dummy.php');
        if ($q) {
            $allTeachers = array_values(array_filter($allTeachers, fn($t) =>
                stripos($t['nama_lengkap'], $q) !== false
            ));
        }
        if ($mapel) {
            $allTeachers = array_values(array_filter($allTeachers, fn($t) =>
                stripos($t['mapel'], $mapel) !== false
            ));
        }
        usort($allTeachers, fn($a, $b) => strcoll($a['nama_lengkap'], $b['nama_lengkap']));

        $allRaw       = require database_path('dummy/teachers_dummy.php');
        $mapelList    = collect($allRaw)->pluck('mapel')->filter()->unique()->sort()->values()->toArray();
        $mapelOptions = array_combine($mapelList, $mapelList);
        $teachers     = $allTeachers;
        $totalCount   = count($allTeachers);
    }

    return view('pages.directory.teachers', [
        'teachers'     => $teachers,
        'mapelOptions' => $mapelOptions,
        'totalCount'   => $totalCount,
    ]);
})->name('directory.teachers');

Route::get('/siswa', function () {
    $q     = trim(request('q', ''));
    $kelas = trim(request('kelas', ''));

    // Coba ambil dari Database View v_publik_siswa
    $siswaQuery = PublikSiswa::query();
    if (!empty($q)) {
        $siswaQuery->search($q);
    }
    if (!empty($kelas)) {
        $siswaQuery->kelas($kelas);
    }
    $dbStudents = $siswaQuery->orderBy('nama')->paginate(20)->withQueryString();

    if ($dbStudents->total() > 0) {
        $students = $dbStudents->through(fn($item) => [
            'id'           => $item->id,
            'nama_lengkap' => $item->nama,
            'kelas'        => $item->nama_kelas,
        ]);

        $kelasList = PublikSiswa::whereNotNull('nama_kelas')
            ->distinct()
            ->pluck('nama_kelas')
            ->sort()
            ->values()
            ->toArray();
        $kelasOptions = array_combine($kelasList, $kelasList);
        $totalCount   = $dbStudents->total();
    } else {
        // Fallback jika database view kosong / tidak ada hasil
        $allStudents = require database_path('dummy/students_dummy.php');
        if ($q) {
            $allStudents = array_values(array_filter($allStudents, fn($s) =>
                stripos($s['nama_lengkap'], $q) !== false
            ));
        }
        if ($kelas) {
            $allStudents = array_values(array_filter($allStudents, fn($s) =>
                $s['kelas'] === $kelas || stripos($s['kelas'], $kelas) === 0
            ));
        }
        usort($allStudents, fn($a, $b) => strcoll($a['nama_lengkap'], $b['nama_lengkap']));

        $allRaw       = require database_path('dummy/students_dummy.php');
        $kelasList    = collect($allRaw)->pluck('kelas')->unique()->sort()->values()->toArray();
        $kelasOptions = array_combine($kelasList, $kelasList);
        $students     = $allStudents;
        $totalCount   = count($allStudents);
    }

    return view('pages.directory.students', [
        'students'     => $students,
        'kelasOptions' => $kelasOptions,
        'totalCount'   => $totalCount,
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
            // [MASTER-GRU-001] Manajemen Data Guru & Status Keaktifan
            Route::patch('guru/{guru}/toggle-status', [AdminGuruController::class, 'toggleStatus'])->name('guru.toggle-status');
            Route::post('guru/{guru}/reset-password', [AdminGuruController::class, 'resetPassword'])->name('guru.reset-password');
            Route::resource('guru', AdminGuruController::class)->except(['show']);

            // [MASTER-SIS-001] Manajemen Data Siswa Lengkap (~30 Kolom Berkelompok)
            Route::get('siswa/template-impor', [AdminImporSiswaController::class, 'downloadTemplate'])->name('siswa.template-impor');
            Route::post('siswa/impor/upload', [AdminImporSiswaController::class, 'upload'])->name('siswa.impor.upload');
            Route::get('siswa/impor/{impor}/preview', [AdminImporSiswaController::class, 'preview'])->name('siswa.impor.preview');
            Route::post('siswa/impor/{impor}/commit', [AdminImporSiswaController::class, 'commit'])->name('siswa.impor.commit');
            Route::delete('siswa/impor/{impor}/cancel', [AdminImporSiswaController::class, 'cancel'])->name('siswa.impor.cancel');
            Route::resource('siswa', AdminSiswaController::class)->parameters(['siswa' => 'siswa'])->except(['show']);

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

        // ─── Dashboard Guru & Wali Kelas ──────────────────────────────────
        Route::prefix('guru')->name('guru.')->group(function () {
            // Akademik & Pembelajaran
            Route::get('/jadwal', function () {
                return view('pages.guru.jadwal');
            })->name('jadwal');

            Route::get('/kelas-mapel', function () {
                return view('pages.guru.kelas-mapel');
            })->name('kelas-mapel');

            Route::get('/absensi', function () {
                return view('pages.guru.absensi');
            })->name('absensi');

            Route::get('/materi', function () {
                return view('pages.guru.materi');
            })->name('materi');

            // Perwalian (Wali Kelas Aktif)
            Route::prefix('perwalian')->name('perwalian.')->group(function () {
                Route::get('/siswa', function () {
                    return view('pages.guru.perwalian.siswa');
                })->name('siswa');

                Route::get('/absensi', function () {
                    return view('pages.guru.perwalian.absensi');
                })->name('absensi');

                Route::get('/catatan', function () {
                    return view('pages.guru.perwalian.catatan');
                })->name('catatan');
            });
        });

        Route::get('/guru-panel', function () {
            return view('pages.guru.dashboard');
        })->name('dashboard.guru');

        // ─── Dashboard Siswa ────────────────────────────────────────────────
        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::get('/jadwal', function () {
                return view('pages.siswa.jadwal');
            })->name('jadwal');

            Route::get('/materi', function () {
                return view('pages.siswa.materi');
            })->name('materi');

            Route::get('/absensi', function () {
                return view('pages.siswa.absensi');
            })->name('absensi');

            Route::get('/profil', function () {
                return view('pages.siswa.profil');
            })->name('profil');
        });

        Route::get('/siswa-panel', function () {
            return view('pages.siswa.dashboard');
        })->name('dashboard.siswa');
    });
});
