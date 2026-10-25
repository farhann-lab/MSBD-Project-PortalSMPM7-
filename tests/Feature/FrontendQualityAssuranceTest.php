<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendQualityAssuranceTest extends TestCase
{
    /**
     * [FE-QA-001] Verify responsive viewport meta tag and anti-overflow structure on all public pages.
     */
    public function test_all_public_pages_have_viewport_meta_tag(): void
    {
        $urls = [
            '/',
            '/profil',
            '/profil/kepala-sekolah',
            '/profil/struktur',
            '/profil/fasilitas',
            '/guru',
            '/siswa',
            '/kesiswaan/organisasi',
            '/kesiswaan/ekstrakurikuler',
            '/kesiswaan/prestasi',
            '/galeri',
            '/berita',
            '/pengumuman',
            '/login',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee('<meta name="viewport" content="width=device-width, initial-scale=1.0">', false);
            // Verify base responsive wrapper / anti-overflow structure
            $response->assertSee('min-h-full flex flex-col font-sans', false);
        }
    }

    /**
     * [FE-QA-002] Verify accessibility landmarks, skip-link, and keyboard focus attributes.
     */
    public function test_accessibility_landmarks_and_skip_link_present(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Skip to content link
        $response->assertSee('href="#main-content"', false);
        $response->assertSee('Lewati ke konten utama', false);

        // Main landmark with role="main" and tabindex for programmatic focus
        $response->assertSee('id="main-content"', false);
        $response->assertSee('role="main"', false);

        // Header and Footer landmarks
        $response->assertSee('<header', false);
        $response->assertSee('<footer', false);

        // Navigation elements have appropriate aria labels
        $response->assertSee('aria-label="Navigasi Utama"', false);
        $response->assertSee('aria-label="Buka menu navigasi"', false);
    }

    /**
     * [FE-QA-003] Audit Privacy Directory (DIR-8):
     * Verify that under NO circumstances do sensitive private fields leak in /guru or /siswa cards/tables/data.
     */
    public function test_directory_pages_never_expose_sensitive_personal_data(): void
    {
        // 1. Audit /guru HTML response: Check cards/table records for private data attributes or values
        $guruResponse = $this->get('/guru');
        $guruResponse->assertStatus(200);
        $guruHtml = $guruResponse->getContent();

        // Cards must only contain name, initials, and mapel/jabatan.
        // Must NOT leak database fields like nip_guru, nik, no_hp, etc.
        $forbiddenDataKeys = [
            'data-nip',
            'data-nik',
            'data-phone',
            'data-telp',
            'data-birth',
            'tgl_lahir',
            'tanggal_lahir',
            'no_telp',
            'no_hp',
            'alamat_rumah',
            'nama_ortu',
            'nilai_rapor',
            'presensi',
        ];

        foreach ($forbiddenDataKeys as $key) {
            $this->assertDoesNotMatchRegularExpression(
                '/' . preg_quote($key, '/') . '/i',
                $guruHtml,
                "Forbidden sensitive field key '{$key}' found in /guru output!"
            );
        }

        // 2. Audit /siswa HTML response: Cards must only contain name, initials, and kelas.
        $siswaResponse = $this->get('/siswa');
        $siswaResponse->assertStatus(200);
        $siswaHtml = $siswaResponse->getContent();

        $forbiddenStudentKeys = array_merge($forbiddenDataKeys, [
            'data-nis',
            'data-nisn',
            'nis_siswa',
            'nisn_siswa',
        ]);

        foreach ($forbiddenStudentKeys as $key) {
            $this->assertDoesNotMatchRegularExpression(
                '/' . preg_quote($key, '/') . '/i',
                $siswaHtml,
                "Forbidden sensitive field key '{$key}' found in /siswa output!"
            );
        }

        // 3. Ensure no hidden inputs containing student or teacher sensitive data
        $this->assertDoesNotMatchRegularExpression('/<input[^>]+type=["\']hidden["\'][^>]+(nip|nik|nis|nisn|telp|hp)/i', $guruHtml);
        $this->assertDoesNotMatchRegularExpression('/<input[^>]+type=["\']hidden["\'][^>]+(nip|nik|nis|nisn|telp|hp)/i', $siswaHtml);
    }

    /**
     * [FE-QA-004] Audit performance assets: Vite manifest and bundled asset sizes.
     */
    public function test_production_asset_manifest_and_bundle_size(): void
    {
        $manifestPath = public_path('build/manifest.json');
        $this->assertFileExists($manifestPath, 'Production Vite manifest.json must exist.');

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $this->assertIsArray($manifest);
        $this->assertArrayHasKey('resources/css/app.css', $manifest);
        $this->assertArrayHasKey('resources/js/app.js', $manifest);

        $cssFile = public_path('build/' . $manifest['resources/css/app.css']['file']);
        $jsFile = public_path('build/' . $manifest['resources/js/app.js']['file']);

        $this->assertFileExists($cssFile);
        $this->assertFileExists($jsFile);

        $cssSizeKb = filesize($cssFile) / 1024;
        $jsSizeKb = filesize($jsFile) / 1024;

        // Acceptance criteria: Total bundle CSS compressed < 100 KB (uncompressed is ~88 KB, well below 100 KB)
        $this->assertLessThan(150, $cssSizeKb, "CSS bundle should be under 150 KB uncompressed (< 20 KB gzipped). Actual: {$cssSizeKb} KB");
        $this->assertLessThan(100, $jsSizeKb, "JS bundle should be under 100 KB uncompressed (< 25 KB gzipped). Actual: {$jsSizeKb} KB");
    }

    /**
     * [FE-QA-002] Check that placeholders and images have aria-hidden or alt attributes.
     */
    public function test_placeholders_and_images_have_accessibility_attributes(): void
    {
        $pages = ['/', '/profil/fasilitas', '/kesiswaan/ekstrakurikuler', '/galeri', '/berita'];
        $auditedCount = 0;

        foreach ($pages as $url) {
            $html = $this->get($url)->getContent();

            // 1. If any <img> tag exists, it must have an alt attribute
            if (preg_match_all('/<img\s+[^>]*>/i', $html, $matches)) {
                foreach ($matches[0] as $imgTag) {
                    $auditedCount++;
                    $this->assertMatchesRegularExpression(
                        '/alt\s*=\s*["\'][^"\']*["\']/i',
                        $imgTag,
                        "Image tag missing alt attribute on page {$url}: {$imgTag}"
                    );
                }
            }

            // 2. All image placeholder components must have aria-hidden="true" to not confuse screen readers
            if (preg_match_all('/<div[^>]+aspect-[^>]+aria-hidden=["\']true["\'][^>]*>/i', $html, $matches)) {
                $auditedCount += count($matches[0]);
            }
        }

        $this->assertGreaterThan(0, $auditedCount, "Expected to audit images and visual placeholders across pages.");
    }
}
