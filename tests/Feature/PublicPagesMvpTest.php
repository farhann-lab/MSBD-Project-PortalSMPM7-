<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesMvpTest extends TestCase
{
    /**
     * [FE-PAGE-001] Test Halaman Beranda (Home Page Layout & Sections)
     */
    public function test_home_page_renders_all_six_sections(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Seksi 1: Hero
        $response->assertSee('Membangun Generasi Unggul Berkarakter Islami');
        $response->assertSee('/profil');
        $response->assertSee('/berita');

        // Seksi 2: Pintasan Portal (CBT, Rapor, PPDB)
        $response->assertSee('CBT Online');
        $response->assertSee('Rapor Digital');
        $response->assertSee('PPDB Online');

        // Seksi 3: Sambutan Kepala Sekolah
        $response->assertSee('M. Reza Akbar, S.Pd.');
        $response->assertSee('/profil/kepala-sekolah');

        // Seksi 4: Berita Terbaru
        $response->assertSee('Berita Terbaru');
        $response->assertSee('Lihat Semua Berita');

        // Seksi 5: Pengumuman Terbaru
        $response->assertSee('Pengumuman Terbaru');
        $response->assertSee('Lihat Semua Pengumuman');

        // Seksi 6: Statistik Sekolah (dummy markers)
        $response->assertSee('Statistik Sekolah');
        $response->assertSee('Siswa Aktif');
        $response->assertSee('Tenaga Pendidik');
    }

    /**
     * [FE-PAGE-002] Test Halaman Profil: Tentang Sekolah & Kontak
     */
    public function test_profile_about_page_renders(): void
    {
        $response = $this->get('/profil');
        $response->assertStatus(200);
        $response->assertSee('Tentang SMP Muhammadiyah 7 Medan');
        $response->assertSee('Visi Sekolah');
        $response->assertSee('Misi Sekolah');
        $response->assertSee('10257325'); // NPSN
        $response->assertSee('smpm7medan@gmail.com');
        $response->assertSee('0616621557');
    }

    /**
     * [FE-PAGE-003] Test Halaman Profil: Kepala Sekolah
     */
    public function test_profile_principal_page_renders(): void
    {
        $response = $this->get('/profil/kepala-sekolah');
        $response->assertStatus(200);
        $response->assertSee('M. Reza Akbar, S.Pd.');
        $response->assertSee('Kepala Sekolah');
        $response->assertSee('Assalamu');
    }

    /**
     * [FE-PAGE-004] Test Halaman Profil: Struktur Organisasi
     */
    public function test_profile_structure_page_renders(): void
    {
        $response = $this->get('/profil/struktur');
        $response->assertStatus(200);
        $response->assertSee('Struktur Organisasi');
        $response->assertSee('M. Reza Akbar, S.Pd.');
        $response->assertSee('Data Contoh');
    }

    /**
     * [FE-PAGE-005] Test Halaman Profil: Fasilitas
     */
    public function test_profile_facilities_page_renders(): void
    {
        $response = $this->get('/profil/fasilitas');
        $response->assertStatus(200);
        $response->assertSee('Sarana &amp; Prasarana', false);
        $response->assertSee('Ruang Kelas');
        $response->assertSee('Laboratorium Komputer');
    }

    /**
     * [FE-PAGE-006] Test Halaman Direktori Guru (DIR-8 Privacy Compliant)
     */
    public function test_directory_teachers_page_renders_and_enforces_privacy(): void
    {
        $response = $this->get('/guru');
        $response->assertStatus(200);
        $response->assertSee('Direktori Guru');
        $response->assertSee('Ahmad Fauzi, S.Pd.');

        // DIR-8: Tidak boleh memuat data nilai, rapor, atau kontak pribadi guru pada output kartu
        $response->assertDontSee('NIP:');
        $response->assertDontSee('NIK:');
        $response->assertDontSee('081', false);

        // Test filter search
        $searchResponse = $this->get('/guru?q=Aisyah');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Aisyah Rahmawati, S.Pd.');
    }

    /**
     * [FE-PAGE-007] Test Halaman Direktori Siswa (DIR-8 Privacy Compliant)
     */
    public function test_directory_students_page_renders_and_enforces_privacy(): void
    {
        $response = $this->get('/siswa');
        $response->assertStatus(200);
        $response->assertSee('Direktori Siswa');
        $response->assertSee('Afifah Zahra Nasution');

        // DIR-8: Data kartu siswa tidak memuat kolom nilai/rapor/NIS sensitif
        $response->assertDontSee('NIS:');
        $response->assertDontSee('NISN:');
        $response->assertDontSee('Nilai Rapor');

        // Test filter kelas
        $filterResponse = $this->get('/siswa?kelas=7A');
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('7A');
    }

    /**
     * [FE-PAGE-008] Test Halaman Kesiswaan: Organisasi Siswa
     */
    public function test_student_life_organizations_page_renders(): void
    {
        $response = $this->get('/kesiswaan/organisasi');
        $response->assertStatus(200);
        $response->assertSee('Organisasi Siswa');
        $response->assertSee('IPM (Ikatan Pelajar Muhammadiyah)');
        $response->assertSee('OSIS (Organisasi Siswa Intra Sekolah)');
        $response->assertSee('Data Contoh');
    }

    /**
     * [FE-PAGE-009] Test Halaman Kesiswaan: Ekstrakurikuler
     */
    public function test_student_life_extracurriculars_page_renders(): void
    {
        $response = $this->get('/kesiswaan/ekstrakurikuler');
        $response->assertStatus(200);
        $response->assertSee('Ekstrakurikuler');
        $response->assertSee('Futsal');
        $response->assertSee('Tahfidz Quran');
    }

    /**
     * [FE-PAGE-010] Test Halaman Kesiswaan: Prestasi Siswa (PRD KSW-3 Warning Banner)
     */
    public function test_student_life_achievements_page_renders_warning_banner(): void
    {
        $response = $this->get('/kesiswaan/prestasi');
        $response->assertStatus(200);
        $response->assertSee('Prestasi Siswa');
        $response->assertSee('Perhatian: Data Contoh'); // PRD KSW-3 requirement
        $response->assertSee('Juara 1 Futsal Putra Antar SMP Piala Wali Kota Medan');
    }

    /**
     * [FE-PAGE-011] Test Halaman Galeri Foto
     */
    public function test_gallery_page_renders(): void
    {
        $response = $this->get('/galeri');
        $response->assertStatus(200);
        $response->assertSee('Galeri Foto Kegiatan Sekolah');
        $response->assertSee('Upacara Bendera Hari Pahlawan');
    }

    /**
     * [FE-PAGE-012 & FE-PAGE-013] Test Halaman Berita Katalog & Detail
     */
    public function test_news_index_and_detail_page_renders(): void
    {
        $indexResponse = $this->get('/berita');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Berita &amp; Informasi Terkini', false);

        $detailResponse = $this->get('/berita/siswa-smp-muhammadiyah-7-raih-juara-1-olimpiade-matematika-tingkat-kota');
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Siswa SMP Muhammadiyah 7 Medan Raih Juara 1 Olimpiade Matematika');
        $detailResponse->assertSee('Kembali ke Daftar Berita');

        // 404 pada slug tidak dikenal
        $notFoundResponse = $this->get('/berita/slug-tidak-valid-xyz');
        $notFoundResponse->assertStatus(404);
    }

    /**
     * [FE-PAGE-014] Test Halaman Pengumuman Katalog & Detail
     */
    public function test_announcements_index_and_detail_page_renders(): void
    {
        $indexResponse = $this->get('/pengumuman');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Pengumuman Sekolah');

        $detailResponse = $this->get('/pengumuman/jadwal-ujian-tengah-semester-ganjil-2026-2027');
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Jadwal Ujian Tengah Semester');
        $detailResponse->assertSee('Kembali ke Daftar Pengumuman');

        $notFoundResponse = $this->get('/pengumuman/slug-tidak-ada');
        $notFoundResponse->assertStatus(404);
    }

    /**
     * [FE-PAGE-015] Test Halaman / Pintasan Login SIAKAD
     */
    public function test_login_shortcut_page_renders(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login SIAKAD');
        $response->assertSee('Sistem Informasi Akademik');
        $response->assertSee('Hubungi via Email');
        $response->assertDontSee('<input type="password"', false); // No fake password form
    }
}
