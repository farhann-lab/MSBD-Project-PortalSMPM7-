<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SIAKAD SMP Muhammadiyah 7 Medan (Portal Publik)
|--------------------------------------------------------------------------
| Rute-rute antarmuka publik sesuai PRD.md dan DESIGN.md.
| Seluruh rute menggunakan master shell <x-layouts.public>.
*/

// Beranda & Layout Showcase
Route::get('/', function () {
    return view('pages.layout-preview');
})->name('home');

Route::get('/layout-preview', function () {
    return view('pages.layout-preview');
})->name('layout.preview');

Route::get('/components-preview', function () {
    return view('pages.components-preview');
})->name('components.preview');

// Profil Sekolah
Route::prefix('profil')->group(function () {
    Route::get('/', function () {
        return view('pages.placeholder', [
            'pageTitle' => 'Tentang Sekolah',
            'kicker' => 'Profil Sekolah',
            'description' => 'Visi, misi, sejarah pendirian, dan identitas resmi SMP Muhammadiyah 7 Medan.',
            'breadcrumbs' => [
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Profil', 'url' => url('/profil')],
                ['label' => 'Tentang Sekolah', 'url' => ''],
            ],
        ]);
    })->name('profile.about');

    Route::get('/kepala-sekolah', function () {
        return view('pages.placeholder', [
            'pageTitle' => 'Kepala Sekolah',
            'kicker' => 'Profil Pimpinan',
            'description' => 'Profil dan sambutan lengkap Kepala Sekolah SMP Muhammadiyah 7 Medan (M. Reza Akbar, S.Pd.).',
            'breadcrumbs' => [
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Profil', 'url' => url('/profil')],
                ['label' => 'Kepala Sekolah', 'url' => ''],
            ],
        ]);
    })->name('profile.principal');

    Route::get('/struktur', function () {
        return view('pages.placeholder', [
            'pageTitle' => 'Struktur Organisasi',
            'kicker' => 'Tata Kelola Sekolah',
            'description' => 'Bagan kepemimpinan, komite sekolah, dewan guru, dan staf tata usaha.',
            'breadcrumbs' => [
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Profil', 'url' => url('/profil')],
                ['label' => 'Struktur Organisasi', 'url' => ''],
            ],
        ]);
    })->name('profile.structure');

    Route::get('/fasilitas', function () {
        return view('pages.placeholder', [
            'pageTitle' => 'Sarana & Prasarana',
            'kicker' => 'Fasilitas Sekolah',
            'description' => 'Dokumentasi dan informasi sarana belajar, laboratorium komputer, perpustakaan, dan lapangan olahraga.',
            'breadcrumbs' => [
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Profil', 'url' => url('/profil')],
                ['label' => 'Sarana & Prasarana', 'url' => ''],
            ],
        ]);
    })->name('profile.facilities');
});

// Direktori Guru & Siswa
Route::get('/guru', function () {
    return view('pages.placeholder', [
        'pageTitle' => 'Direktori Guru',
        'kicker' => 'Tenaga Pendidik',
        'description' => 'Katalog tenaga pendidik dan guru pengampu mata pelajaran SMP Muhammadiyah 7 Medan.',
        'breadcrumbs' => [
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Direktori', 'url' => url('/guru')],
            ['label' => 'Direktori Guru', 'url' => ''],
        ],
    ]);
})->name('directory.teachers');

Route::get('/siswa', function () {
    return view('pages.placeholder', [
        'pageTitle' => 'Direktori Siswa',
        'kicker' => 'Peserta Didik',
        'description' => 'Daftar nama peserta didik aktif berdasarkan jenjang kelas dan rombel.',
        'breadcrumbs' => [
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Direktori', 'url' => url('/siswa')],
            ['label' => 'Direktori Siswa', 'url' => ''],
        ],
    ]);
})->name('directory.students');

// Kesiswaan
Route::prefix('kesiswaan')->group(function () {
    Route::get('/', function () {
        return redirect()->route('student-life.extracurriculars');
    });

    Route::get('/organisasi', function () {
        return view('pages.placeholder', [
            'pageTitle' => 'Organisasi Siswa',
            'kicker' => 'Kesiswaan',
            'description' => 'Ikatan Pelajar Muhammadiyah (IPM) dan wadah kepemimpinan siswa.',
            'breadcrumbs' => [
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Kesiswaan', 'url' => url('/kesiswaan/organisasi')],
                ['label' => 'Organisasi Siswa', 'url' => ''],
            ],
        ]);
    })->name('student-life.organizations');

    Route::get('/ekstrakurikuler', function () {
        return view('pages.placeholder', [
            'pageTitle' => 'Ekstrakurikuler',
            'kicker' => 'Kesiswaan',
            'description' => 'Kegiatan pengembangan minat, bakat, keagamaan, olahraga, dan seni.',
            'breadcrumbs' => [
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Kesiswaan', 'url' => url('/kesiswaan/ekstrakurikuler')],
                ['label' => 'Ekstrakurikuler', 'url' => ''],
            ],
        ]);
    })->name('student-life.extracurriculars');

    Route::get('/prestasi', function () {
        return view('pages.placeholder', [
            'pageTitle' => 'Prestasi Siswa',
            'kicker' => 'Kesiswaan',
            'description' => 'Catatan torehan kejuaraan dan capaian prestasi membanggakan siswa.',
            'breadcrumbs' => [
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Kesiswaan', 'url' => url('/kesiswaan/prestasi')],
                ['label' => 'Prestasi Siswa', 'url' => ''],
            ],
        ]);
    })->name('student-life.achievements');
});

// Galeri Dokumentasi
Route::get('/galeri', function () {
    return view('pages.placeholder', [
        'pageTitle' => 'Galeri Foto Kegiatan',
        'kicker' => 'Dokumentasi',
        'description' => 'Dokumentasi foto kegiatan akademik, keagamaan, lomba, dan fasilitas sekolah.',
        'breadcrumbs' => [
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Galeri', 'url' => ''],
        ],
    ]);
})->name('gallery.index');

// Berita
Route::get('/berita', function () {
    return view('pages.placeholder', [
        'pageTitle' => 'Warta & Berita Terkini',
        'kicker' => 'Kabar Sekolah',
        'description' => 'Informasi dan liputan kegiatan terkini di lingkungan SMP Muhammadiyah 7 Medan.',
        'breadcrumbs' => [
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Berita', 'url' => ''],
        ],
    ]);
})->name('news.index');

Route::get('/berita/{slug}', function ($slug) {
    return view('pages.placeholder', [
        'pageTitle' => 'Detail Berita: ' . str_replace('-', ' ', ucwords($slug)),
        'kicker' => 'Berita',
        'description' => 'Artikel lengkap dan dokumentasi berita sekolah.',
        'breadcrumbs' => [
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Berita', 'url' => url('/berita')],
            ['label' => 'Baca Artikel', 'url' => ''],
        ],
    ]);
})->name('news.show');

// Pengumuman
Route::get('/pengumuman', function () {
    return view('pages.placeholder', [
        'pageTitle' => 'Pengumuman Resmi',
        'kicker' => 'Informasi Resmi',
        'description' => 'Pengumuman dan edaran resmi dari pihak sekolah bagi siswa, orang tua, dan guru.',
        'breadcrumbs' => [
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Pengumuman', 'url' => ''],
        ],
    ]);
})->name('announcements.index');

Route::get('/pengumuman/{slug}', function ($slug) {
    return view('pages.placeholder', [
        'pageTitle' => 'Detail Pengumuman: ' . str_replace('-', ' ', ucwords($slug)),
        'kicker' => 'Pengumuman',
        'description' => 'Surat edaran atau rincian pengumuman resmi.',
        'breadcrumbs' => [
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Pengumuman', 'url' => url('/pengumuman')],
            ['label' => 'Lihat Pengumuman', 'url' => ''],
        ],
    ]);
})->name('announcements.show');

// Login SIAKAD
Route::get('/login', function () {
    return view('pages.placeholder', [
        'pageTitle' => 'Pintu Masuk Login SIAKAD',
        'kicker' => 'Autentikasi Internal',
        'description' => 'Portal autentikasi akademik bagi Guru, Siswa, dan Staf. Modul autentikasi dikelola dalam sistem terpisah.',
        'breadcrumbs' => [
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Login SIAKAD', 'url' => ''],
        ],
    ]);
})->name('login');
