<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes — SIAKAD SMP Muhammadiyah 7 Medan (Portal Publik)
|--------------------------------------------------------------------------
| Phase 3: Semua rute halaman MVP terhubung ke data fixture dummy.
| Sesuai PRD.md dan DESIGN.md.
*/

// ─── Dev / Debug Showcases ───────────────────────────────────────────────────
Route::get('/layout-preview', function () {
    return view('pages.layout-preview');
})->name('layout.preview');

Route::get('/components-preview', function () {
    return view('pages.components-preview');
})->name('components.preview');

// ─── Beranda ─────────────────────────────────────────────────────────────────
Route::get('/', function () {
    $allNews          = require database_path('dummy/news.php');
    $allAnnouncements = require database_path('dummy/announcements.php');

    // Portal pintasan dari config (filter hanya CBT, Rapor, PPDB — bukan login)
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

// ─── Profil Sekolah ──────────────────────────────────────────────────────────
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

// ─── Direktori Guru & Siswa ───────────────────────────────────────────────────
Route::get('/guru', function () {
    $allTeachers = require database_path('dummy/teachers_dummy.php');

    // Filter: pencarian nama
    $q = request('q', '');
    if ($q) {
        $allTeachers = array_values(array_filter($allTeachers, fn($t) =>
            stripos($t['nama_lengkap'], $q) !== false
        ));
    }

    // Filter: mata pelajaran
    $mapel = request('mapel', '');
    if ($mapel) {
        $allTeachers = array_values(array_filter($allTeachers, fn($t) =>
            $t['mapel'] === $mapel
        ));
    }

    // Urut alfabetis
    usort($allTeachers, fn($a, $b) => strcoll($a['nama_lengkap'], $b['nama_lengkap']));

    // Opsi filter mapel (dari semua data asli, tanpa filter aktif)
    $allRaw     = require database_path('dummy/teachers_dummy.php');
    $mapelList  = collect($allRaw)->pluck('mapel')->filter()->unique()->sort()->values()->toArray();
    $mapelOptions = array_combine($mapelList, $mapelList);

    return view('pages.directory.teachers', [
        'teachers'     => $allTeachers,
        'mapelOptions' => $mapelOptions,
        'totalCount'   => count($allTeachers),
    ]);
})->name('directory.teachers');

Route::get('/siswa', function () {
    $allStudents = require database_path('dummy/students_dummy.php');

    // Filter: pencarian nama
    $q = request('q', '');
    if ($q) {
        $allStudents = array_values(array_filter($allStudents, fn($s) =>
            stripos($s['nama_lengkap'], $q) !== false
        ));
    }

    // Filter: kelas
    $kelas = request('kelas', '');
    if ($kelas) {
        $allStudents = array_values(array_filter($allStudents, fn($s) =>
            $s['kelas'] === $kelas
        ));
    }

    // Urut nama
    usort($allStudents, fn($a, $b) => strcoll($a['nama_lengkap'], $b['nama_lengkap']));

    // Opsi kelas
    $allRaw       = require database_path('dummy/students_dummy.php');
    $kelasList    = collect($allRaw)->pluck('kelas')->unique()->sort()->values()->toArray();
    $kelasOptions = array_combine($kelasList, $kelasList);

    return view('pages.directory.students', [
        'students'     => $allStudents,
        'kelasOptions' => $kelasOptions,
        'totalCount'   => count($allStudents),
    ]);
})->name('directory.students');

// ─── Kesiswaan ────────────────────────────────────────────────────────────────
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

// ─── Galeri ───────────────────────────────────────────────────────────────────
Route::get('/galeri', function () {
    $gallery = require database_path('dummy/gallery.php');
    return view('pages.gallery.index', compact('gallery'));
})->name('gallery.index');

// ─── Berita ───────────────────────────────────────────────────────────────────
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

// ─── Pengumuman ───────────────────────────────────────────────────────────────
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

// ─── Login SIAKAD ─────────────────────────────────────────────────────────────
Route::get('/login', function () {
    return view('pages.login.index');
})->name('login');
