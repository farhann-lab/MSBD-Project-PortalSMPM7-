<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendInteractionsAndStateTest extends TestCase
{
    /**
     * [FE-STATE-001] Test seluruh file data dummy fixture tersedia dan terisolasi
     */
    public function test_all_dummy_fixtures_exist_and_return_valid_arrays(): void
    {
        $fixtures = [
            'news.php',
            'announcements.php',
            'facilities.php',
            'organizations.php',
            'extracurriculars.php',
            'achievements.php',
            'gallery.php',
            'teachers_dummy.php',
            'students_dummy.php',
        ];

        foreach ($fixtures as $file) {
            $path = database_path('dummy/' . $file);
            $this->assertFileExists($path, "File fixture dummy [{$file}] harus ada.");

            $data = require $path;
            $this->assertIsArray($data, "File fixture [{$file}] harus mengembalikan array PHP.");
            $this->assertNotEmpty($data, "File fixture [{$file}] tidak boleh kosong.");

            // Verifikasi komentar penanda dummy
            $content = file_get_contents($path);
            $this->assertStringContainsString('/* DUMMY DATA', $content, "File fixture [{$file}] harus memuat komentar penanda DUMMY DATA.");
        }
    }

    /**
     * [FE-INTERACTION-002] Test Search & Filtering Direktori Guru berbasis HTTP GET
     */
    public function test_teachers_directory_search_and_filtering_interaction(): void
    {
        // 1. Kondisi default (tanpa filter)
        $responseDefault = $this->get('/guru');
        $responseDefault->assertStatus(200);
        $responseDefault->assertSee('Ahmad Fauzi, S.Pd.');
        $responseDefault->assertSee('Budi Santoso, S.Pd.');

        // 2. Filter dengan pencarian nama (?q=Budi)
        $responseSearch = $this->get('/guru?q=Budi');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Budi Santoso, S.Pd.');
        $responseSearch->assertDontSee('Ahmad Fauzi, S.Pd.');
        $responseSearch->assertSee('value="Budi"', false); // Input mempertahankan nilai
        $responseSearch->assertSee('Reset'); // Tombol reset muncul

        // 3. Filter dengan mata pelajaran (?mapel=Matematika)
        $responseMapel = $this->get('/guru?mapel=Matematika');
        $responseMapel->assertStatus(200);
        $responseMapel->assertSee('Ahmad Fauzi, S.Pd.');
        $responseMapel->assertDontSee('Budi Santoso, S.Pd.'); // IPA
        $responseMapel->assertSee('selected', false);

        // 4. Filter kombinasi yang tidak cocok (Empty State)
        $responseEmpty = $this->get('/guru?q=NamaYangTidakMungkinAda123');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('Guru Tidak Ditemukan');
        $responseEmpty->assertSee('href="' . url('/guru') . '"', false); // Link reset
    }

    /**
     * [FE-INTERACTION-002] Test Search & Filtering Direktori Siswa berbasis HTTP GET
     */
    public function test_students_directory_search_and_filtering_interaction(): void
    {
        // 1. Kondisi default
        $responseDefault = $this->get('/siswa');
        $responseDefault->assertStatus(200);
        $responseDefault->assertSee('Afifah Zahra Nasution');

        // 2. Filter dengan kelas (?kelas=7A)
        $responseKelas = $this->get('/siswa?kelas=7A');
        $responseKelas->assertStatus(200);
        $responseKelas->assertSee('Afifah Zahra Nasution'); // 7A
        $responseKelas->assertDontSee('Daffa Mahendra Hutapea'); // 7B
        $responseKelas->assertSee('Reset');

        // 3. Filter nama (?q=Daffa)
        $responseSearch = $this->get('/siswa?q=Daffa');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Daffa Mahendra Hutapea');
        $responseSearch->assertDontSee('Afifah Zahra Nasution');
        $responseSearch->assertSee('value="Daffa"', false);

        // 4. Empty state pencarian
        $responseEmpty = $this->get('/siswa?q=SiswaZzz999');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('Siswa Tidak Ditemukan');
    }

    /**
     * [FE-INTERACTION-001] Test Markup Mobile Navigation untuk interaksi Client-Side JS
     */
    public function test_mobile_navigation_markup_supports_client_interactions(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Validasi elemen pengontrol JavaScript di navbar
        $response->assertSee('id="mobile-nav-toggle"', false);
        $response->assertSee('id="mobile-nav-drawer"', false);
        $response->assertSee('id="mobile-nav-backdrop"', false);
        $response->assertSee('id="mobile-nav-drawer-close"', false);
        $response->assertSee('id="icon-hamburger"', false);
        $response->assertSee('id="icon-close"', false);
        $response->assertSee('class="mobile-accordion', false);
        $response->assertSee('class="mobile-accordion-btn', false);
        $response->assertSee('class="mobile-accordion-content', false);
    }
}
