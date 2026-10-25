<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class GlobalLayoutAndNavigationShellTest extends TestCase
{
    /**
     * [FE-LAYOUT-001] Test Public Layout renders semantic HTML structure,
     * meta tags, skip-to-content accessibility link, and Vite assets.
     */
    public function test_public_layout_renders_semantic_html_structure_and_assets(): void
    {
        $rendered = Blade::render('
            <x-layouts.public title="Uji Layout">
                <div id="test-content">Konten Pengujian</div>
            </x-layouts.public>
        ');

        // Semantic document structure
        $this->assertStringContainsString('<!DOCTYPE html>', $rendered);
        $this->assertStringContainsString('<html', $rendered);
        $this->assertStringContainsString('<meta charset="utf-8">', $rendered);
        $this->assertStringContainsString('name="viewport"', $rendered);
        $this->assertStringContainsString('name="csrf-token"', $rendered);

        // Title and description
        $this->assertStringContainsString('Uji Layout — SMP Muhammadiyah 7 Medan', $rendered);
        $this->assertStringContainsString('name="description"', $rendered);

        // Skip to content accessibility link
        $this->assertStringContainsString('href="#main-content"', $rendered);
        $this->assertStringContainsString('sr-only', $rendered);
        $this->assertStringContainsString('focus:not-sr-only', $rendered);
        $this->assertStringContainsString('Lewati ke konten utama', $rendered);

        // Header, Main with ID, and Footer
        $this->assertStringContainsString('<header', $rendered);
        $this->assertStringContainsString('<main id="main-content"', $rendered);
        $this->assertStringContainsString('<footer', $rendered);

        // Slot content injection
        $this->assertStringContainsString('Konten Pengujian', $rendered);
    }

    /**
     * [FE-LAYOUT-002] Test Navbar renders school brand identity, desktop menu items,
     * submenus (Profil, Direktori, Kesiswaan), and SIAKAD Login CTA button.
     */
    public function test_navbar_renders_brand_identity_and_desktop_navigation(): void
    {
        $rendered = Blade::render('<x-navbar />');

        // Brand identity
        $this->assertStringContainsString('SMP Muhammadiyah 7', $rendered);
        $this->assertStringContainsString('Kota Medan', $rendered);
        $this->assertStringContainsString('10257325', $rendered);

        // Standalone navigation links
        $this->assertStringContainsString('Beranda', $rendered);
        $this->assertStringContainsString('href="' . url('/galeri') . '"', $rendered);
        $this->assertStringContainsString('href="' . url('/berita') . '"', $rendered);
        $this->assertStringContainsString('href="' . url('/pengumuman') . '"', $rendered);

        // Desktop Dropdown toggles
        $this->assertStringContainsString('data-dropdown="profile"', $rendered);
        $this->assertStringContainsString('data-dropdown="directory"', $rendered);
        $this->assertStringContainsString('data-dropdown="student-life"', $rendered);

        // Submenu: Profil Sekolah
        $this->assertStringContainsString(url('/profil'), $rendered);
        $this->assertStringContainsString(url('/profil/kepala-sekolah'), $rendered);
        $this->assertStringContainsString(url('/profil/struktur'), $rendered);
        $this->assertStringContainsString(url('/profil/fasilitas'), $rendered);

        // Submenu: Direktori
        $this->assertStringContainsString(url('/guru'), $rendered);
        $this->assertStringContainsString(url('/siswa'), $rendered);

        // Submenu: Kesiswaan
        $this->assertStringContainsString(url('/kesiswaan/organisasi'), $rendered);
        $this->assertStringContainsString(url('/kesiswaan/ekstrakurikuler'), $rendered);
        $this->assertStringContainsString(url('/kesiswaan/prestasi'), $rendered);

        // SIAKAD Login CTA button
        $this->assertStringContainsString('Login SIAKAD', $rendered);
        $this->assertStringContainsString(url('/login'), $rendered);
    }

    /**
     * [FE-LAYOUT-003] Test Mobile Navigation Drawer, touch targets, accessibility attributes,
     * accordions, and backdrop overlay.
     */
    public function test_mobile_navigation_drawer_and_accessibility(): void
    {
        $rendered = Blade::render('<x-navbar />');

        // Mobile hamburger toggle button with min touch target 44px
        $this->assertStringContainsString('id="mobile-nav-toggle"', $rendered);
        $this->assertStringContainsString('aria-label="Buka menu navigasi"', $rendered);
        $this->assertStringContainsString('aria-expanded="false"', $rendered);
        $this->assertStringContainsString('min-h-11 min-w-11', $rendered);

        // Overlay Backdrop
        $this->assertStringContainsString('id="mobile-nav-backdrop"', $rendered);

        // Mobile Drawer dialog
        $this->assertStringContainsString('id="mobile-nav-drawer"', $rendered);
        $this->assertStringContainsString('role="dialog"', $rendered);
        $this->assertStringContainsString('aria-modal="true"', $rendered);

        // Close button inside drawer
        $this->assertStringContainsString('id="mobile-nav-drawer-close"', $rendered);
        $this->assertStringContainsString('aria-label="Tutup menu navigasi"', $rendered);

        // Mobile accordions for submenus
        $this->assertStringContainsString('mobile-accordion', $rendered);
        $this->assertStringContainsString('mobile-accordion-btn', $rendered);
        $this->assertStringContainsString('mobile-accordion-content', $rendered);
    }

    /**
     * [FE-LAYOUT-004] Test Global 4-Column Footer Component:
     * Column 1 (Identity), Column 2 (Navigation), Column 3 (Information/Portals),
     * Column 4 (Contact), and Bottom Bar (Copyright & Back-to-top).
     */
    public function test_footer_renders_four_columns_and_accessible_information(): void
    {
        $rendered = Blade::render('<x-footer />');

        // Column 1: School Identity & NPSN
        $this->assertStringContainsString('SMP Muhammadiyah 7', $rendered);
        $this->assertStringContainsString('NPSN: 10257325', $rendered);
        $this->assertStringContainsString('Akreditasi A', $rendered);

        // Column 2: Main Navigation
        $this->assertStringContainsString('Navigasi Utama', $rendered);
        $this->assertStringContainsString('Tentang Sekolah', $rendered);
        $this->assertStringContainsString('Direktori Guru', $rendered);
        $this->assertStringContainsString('Direktori Siswa', $rendered);

        // Column 3: Portals & Info
        $this->assertStringContainsString('Informasi & Layanan', $rendered);
        $this->assertStringContainsString('Portal CBT Online', $rendered);
        $this->assertStringContainsString('Portal Rapor Siswa', $rendered);
        $this->assertStringContainsString('Pendaftaran PPDB Online', $rendered);
        $this->assertStringContainsString('Pintu Masuk Login SIAKAD', $rendered);

        // Column 4: Official Contact Information
        $this->assertStringContainsString('Kontak & Lokasi', $rendered);
        $this->assertStringContainsString('Jl. Pelita II No. 3-5', $rendered);
        $this->assertStringContainsString('(061) 6621557', $rendered);
        $this->assertStringContainsString('smpm7medan@gmail.com', $rendered);
        $this->assertStringContainsString('Senin – Sabtu, 07.30 – 14.30 WIB', $rendered);

        // Bottom Bar: Copyright & Back-to-Top
        $currentYear = date('Y');
        $this->assertStringContainsString("&copy; {$currentYear}", $rendered);
        $this->assertStringContainsString('id="back-to-top-btn"', $rendered);
    }

    /**
     * Test all public routes return 200 OK and render within the global shell.
     */
    public function test_all_public_routes_return_successful_response(): void
    {
        $publicShellRoutes = [
            '/',
            '/layout-preview',
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
            '/berita/siswa-smp-muhammadiyah-7-raih-juara-1-olimpiade-matematika-tingkat-kota',
            '/pengumuman',
            '/pengumuman/jadwal-ujian-tengah-semester-ganjil-2026-2027',
            '/login',
        ];

        foreach ($publicShellRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertSee('SMP Muhammadiyah 7');
            $response->assertSee('main id="main-content"', false);
        }

        // Components preview showcase route
        $compResponse = $this->get('/components-preview');
        $compResponse->assertStatus(200);
        $compResponse->assertSee('SMP Muhammadiyah 7');
    }
}
