<?php

/*
 * DUMMY & STATIC CONFIGURATION — SMP MUHAMMADIYAH 7 MEDAN
 * 
 * Konfigurasi terpusat untuk identitas sekolah, informasi kontak, tautan portal,
 * dan metadata publik. Sesuai PRD.md (Bab 6 & 8) dan DESIGN.md (Bab 2, 5, 13, 14).
 * Siap dialihkan atau dilengkapi dengan data resmi/database pada tahap lanjutan.
 */

return [
    'nama' => 'SMP Muhammadiyah 7 Medan',
    'nama_pendek' => 'SMP Muhammadiyah 7',
    'slogan' => 'Unggul dalam Prestasi, Santun dalam Budi Pekerti, Mandiri dalam Berpikir',
    'deskripsi' => 'Sekolah Menengah Pertama Islam terpadu di bawah naungan Majelis Dikdasmen Pimpinan Cabang Muhammadiyah Medan Perjuangan yang berkomitmen membentuk generasi cerdas, berakhlak mulia, dan berprestasi.',
    'npsn' => '10257325',
    'status' => 'Swasta',
    'akreditasi' => 'A',

    'kepala_sekolah' => [
        'nama' => 'M. Reza Akbar, S.Pd.',
        'jabatan' => 'Kepala Sekolah',
        'foto' => 'images/principal/principal.webp',
        'sambutan_singkat' => 'Selamat datang di Website Resmi SMP Muhammadiyah 7 Medan. Kami senantiasa berkomitmen menghadirkan layanan pendidikan bermutu tinggi yang memadukan keunggulan akademik dengan penanaman nilai-nilai Al-Islam dan Kemuhammadiyahan untuk membekali masa depan peserta didik.',
    ],

    'kontak' => [
        'alamat' => 'Jl. Pelita II No. 3-5, Sidorame Barat II',
        'kecamatan' => 'Kec. Medan Perjuangan',
        'kota' => 'Kota Medan',
        'provinsi' => 'Sumatera Utara',
        'kode_pos' => '20236',
        'alamat_lengkap' => 'Jl. Pelita II No. 3-5, Sidorame Barat II, Kec. Medan Perjuangan, Kota Medan, Sumatera Utara 20236',
        'telepon' => '(061) 6621557',
        'telepon_raw' => '0616621557',
        'email' => 'smpm7medan@gmail.com',
        'jam_layanan' => 'Senin – Sabtu, 07.30 – 14.30 WIB',
    ],

    'portal' => [
        'cbt' => [
            'label' => 'CBT Online',
            'deskripsi' => 'Ujian Berbasis Komputer & Asesmen Siswa',
            'url' => '#',
            'status' => 'placeholder',
        ],
        'rapor' => [
            'label' => 'Rapor Digital (RKM)',
            'deskripsi' => 'Akses Nilai & Capaian Belajar Siswa',
            'url' => '#',
            'status' => 'placeholder',
        ],
        'ppdb' => [
            'label' => 'PPDB Online',
            'deskripsi' => 'Pendaftaran Peserta Didik Baru',
            'url' => '#',
            'status' => 'placeholder',
        ],
        'login' => [
            'label' => 'Login SIAKAD',
            'deskripsi' => 'Pintu Masuk Sistem Informasi Akademik Internal',
            'url' => '/login',
            'status' => 'active',
        ],
    ],

    'statistik' => [
        'is_dummy' => true,
        'catatan' => '/* DUMMY DATA - BELUM DIBACA DARI DATABASE RKM */',
        'siswa' => 320,
        'guru' => 28,
        'kelas' => 12,
        'ekskul' => 8,
    ],
];
