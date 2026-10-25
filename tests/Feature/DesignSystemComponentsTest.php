<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class DesignSystemComponentsTest extends TestCase
{
    /**
     * Test Button component rendering for both anchor and button tags,
     * including variants and accessibility focus styles.
     */
    public function test_button_component_renders_button_and_anchor_correctly(): void
    {
        // 1. As an anchor tag when href is provided
        $renderedAnchor = Blade::render('<x-button href="/profil" variant="primary">Tentang Sekolah</x-button>');
        $this->assertStringContainsString('<a href="/profil"', $renderedAnchor);
        $this->assertStringContainsString('bg-forest', $renderedAnchor);
        $this->assertStringContainsString('min-h-11', $renderedAnchor);
        $this->assertStringContainsString('focus-visible:outline-forest', $renderedAnchor);
        $this->assertStringContainsString('Tentang Sekolah', $renderedAnchor);

        // 2. As a button tag when href is null
        $renderedButton = Blade::render('<x-button type="submit" variant="secondary">Kirim</x-button>');
        $this->assertStringContainsString('<button type="submit"', $renderedButton);
        $this->assertStringContainsString('border-forest', $renderedButton);
        $this->assertStringContainsString('Kirim', $renderedButton);
    }

    /**
     * Test Badge component rendering with pill styling and color variants.
     */
    public function test_badge_component_renders_pill_with_accessible_tokens(): void
    {
        $renderedDefault = Blade::render('<x-badge>Akademik</x-badge>');
        $this->assertStringContainsString('rounded-full', $renderedDefault);
        $this->assertStringContainsString('bg-mint/40', $renderedDefault);
        $this->assertStringContainsString('Akademik', $renderedDefault);

        $renderedWarning = Blade::render('<x-badge variant="dummy" dot>Data Contoh</x-badge>');
        $this->assertStringContainsString('bg-amber-100', $renderedWarning);
        $this->assertStringContainsString('rounded-full', $renderedWarning);
        $this->assertStringContainsString('Data Contoh', $renderedWarning);
    }

    /**
     * Test Icon component renders inline SVG without external font dependencies.
     */
    public function test_icon_component_renders_inline_svg_without_external_fonts(): void
    {
        $rendered = Blade::render('<x-icon name="search" class="w-5 h-5 text-forest" />');
        $this->assertStringContainsString('<svg', $rendered);
        $this->assertStringContainsString('viewBox="0 0 24 24"', $rendered);
        $this->assertStringContainsString('aria-hidden="true"', $rendered);
        $this->assertStringContainsString('class="w-5 h-5 text-forest"', $rendered);
    }

    /**
     * Test Section Heading renders semantic heading tags and kicker badge.
     */
    public function test_section_heading_renders_semantic_heading_and_kicker(): void
    {
        $rendered = Blade::render(
            '<x-section-heading kicker="Tentang Kami" title="Membangun Karakter Islami" subtitle="Informasi seputar sekolah." align="center" />'
        );

        $this->assertStringContainsString('Tentang Kami', $rendered);
        $this->assertStringContainsString('Membangun Karakter Islami', $rendered);
        $this->assertStringContainsString('Informasi seputar sekolah.', $rendered);
        $this->assertStringContainsString('<h2', $rendered);
        $this->assertStringContainsString('text-center', $rendered);
    }

    /**
     * Test Breadcrumb renders accessible semantic navigation.
     */
    public function test_breadcrumb_renders_semantic_nav_and_accessibility(): void
    {
        $items = [
            ['label' => 'Profil', 'url' => '/profil'],
            ['label' => 'Fasilitas Sekolah', 'url' => ''],
        ];

        $rendered = Blade::render('<x-breadcrumb :items="$items" />', ['items' => $items]);

        $this->assertStringContainsString('<nav aria-label="Breadcrumb"', $rendered);
        $this->assertStringContainsString('Beranda', $rendered);
        $this->assertStringContainsString('href="/profil"', $rendered);
        $this->assertStringContainsString('aria-current="page"', $rendered);
        $this->assertStringContainsString('Fasilitas Sekolah', $rendered);
    }

    /**
     * Test News Card renders aspect ratio, date, and fallback placeholder.
     */
    public function test_news_card_renders_title_slug_and_aspect_ratio(): void
    {
        $rendered = Blade::render(
            '<x-news-card title="SMP Muhammadiyah 7 Raih Prestasi" slug="smp-muhammadiyah-7-raih-prestasi" date="24 Oktober 2026" summary="Ringkasan berita sekolah." />'
        );

        $this->assertStringContainsString('SMP Muhammadiyah 7 Raih Prestasi', $rendered);
        $this->assertStringContainsString('/berita/smp-muhammadiyah-7-raih-prestasi', $rendered);
        $this->assertStringContainsString('24 Oktober 2026', $rendered);
        $this->assertStringContainsString('Baca Selengkapnya', $rendered);
        $this->assertStringContainsString('aspect-video', $rendered);
    }

    /**
     * Test Announcement Card renders informative layout and importance indicator.
     */
    public function test_announcement_card_renders_important_badge_and_link(): void
    {
        $rendered = Blade::render(
            '<x-announcement-card title="Jadwal Asesmen Semester" slug="jadwal-asesmen-semester" date="25 Oktober 2026" summary="Pelaksanaan asesmen dimulai Senin." :important="true" />'
        );

        $this->assertStringContainsString('Jadwal Asesmen Semester', $rendered);
        $this->assertStringContainsString('/pengumuman/jadwal-asesmen-semester', $rendered);
        $this->assertStringContainsString('Penting', $rendered);
        $this->assertStringContainsString('Lihat Pengumuman', $rendered);
    }

    /**
     * Test Portal Card renders quick access card targeting same tab.
     */
    public function test_portal_card_renders_link_with_same_tab_target(): void
    {
        $rendered = Blade::render(
            '<x-portal-card title="Portal CBT" description="Sistem ujian berbasis komputer online." href="/cbt" icon="computer-desktop" badge="Ujian Online" />'
        );

        $this->assertStringContainsString('href="/cbt"', $rendered);
        $this->assertStringContainsString('target="_self"', $rendered);
        $this->assertStringContainsString('Portal CBT', $rendered);
        $this->assertStringContainsString('Ujian Online', $rendered);
        $this->assertStringContainsString('Buka Portal', $rendered);
    }

    /**
     * Test Directory Cards strictly comply with DIR-8 privacy requirements.
     * Absolutely NO NIP, NIK, NIS, NISN, phone, address, birthdate, grades, or photos.
     */
    public function test_directory_components_adhere_strictly_to_dir_8_privacy(): void
    {
        // 1. Teacher Card
        $renderedTeacher = Blade::render(
            '<x-teacher-card name="M. Reza Akbar, S.Pd." mapel="Pendidikan Agama Islam" jabatan="Kepala Sekolah" />'
        );

        $this->assertStringContainsString('M. Reza Akbar, S.Pd.', $renderedTeacher);
        $this->assertStringContainsString('Pendidikan Agama Islam', $renderedTeacher);
        $this->assertStringContainsString('Kepala Sekolah', $renderedTeacher);
        $this->assertStringContainsString('MR', $renderedTeacher); // Initials avatar

        // Strict DIR-8 Assertions: No private fields
        $this->assertStringNotContainsStringIgnoringCase('nip', $renderedTeacher);
        $this->assertStringNotContainsStringIgnoringCase('nik', $renderedTeacher);
        $this->assertStringNotContainsString('081', $renderedTeacher); // phone prefix
        $this->assertStringNotContainsString('tel:', $renderedTeacher);
        $this->assertStringNotContainsString('mailto:', $renderedTeacher);
        $this->assertStringNotContainsString('<img', $renderedTeacher); // no photo

        // 2. Student Card
        $renderedStudent = Blade::render(
            '<x-student-card name="Ahmad Dahlan Pratama" kelas="Kelas 7A" />'
        );

        $this->assertStringContainsString('Ahmad Dahlan Pratama', $renderedStudent);
        $this->assertStringContainsString('Kelas 7A', $renderedStudent);
        $this->assertStringContainsString('AD', $renderedStudent); // Initials avatar

        // Strict DIR-8 Assertions: No private fields
        $this->assertStringNotContainsStringIgnoringCase('nis', $renderedStudent);
        $this->assertStringNotContainsStringIgnoringCase('nisn', $renderedStudent);
        $this->assertStringNotContainsStringIgnoringCase('nik', $renderedStudent);
        $this->assertStringNotContainsString('081', $renderedStudent);
        $this->assertStringNotContainsString('<img', $renderedStudent); // no photo
    }

    /**
     * Test Search, Filter, Pagination, and Empty State components.
     */
    public function test_search_filter_pagination_and_empty_state_components(): void
    {
        // Search Input
        $renderedSearch = Blade::render('<x-search-input name="q" value="Budi" placeholder="Cari nama..." />');
        $this->assertStringContainsString('name="q"', $renderedSearch);
        $this->assertStringContainsString('value="Budi"', $renderedSearch);
        $this->assertStringContainsString('focus:outline-forest', $renderedSearch);
        $this->assertStringContainsString('sr-only', $renderedSearch);

        // Filter Select
        $options = ['7A' => 'Kelas 7A', '7B' => 'Kelas 7B'];
        $renderedFilter = Blade::render('<x-filter-select name="kelas" :options="$options" selected="7A" placeholder="Semua Kelas" />', ['options' => $options]);
        $this->assertStringContainsString('name="kelas"', $renderedFilter);
        $this->assertStringContainsString('value="7A" selected', $renderedFilter);
        $this->assertStringContainsString('Semua Kelas', $renderedFilter);

        // Empty State
        $renderedEmpty = Blade::render('<x-empty-state title="Data guru tidak ditemukan" resetUrl="/guru" />');
        $this->assertStringContainsString('Data guru tidak ditemukan', $renderedEmpty);
        $this->assertStringContainsString('href="/guru"', $renderedEmpty);
        $this->assertStringContainsString('Reset Filter / Pencarian', $renderedEmpty);

        // Pagination
        $renderedPagination = Blade::render('<x-pagination :currentPage="2" :lastPage="5" />');
        $this->assertStringContainsString('role="navigation"', $renderedPagination);
        $this->assertStringContainsString('Sebelumnya', $renderedPagination);
        $this->assertStringContainsString('Berikutnya', $renderedPagination);
    }

    /**
     * Test StatCard, GalleryCard, ContactItem, and ImagePlaceholder components.
     */
    public function test_additional_design_system_cards_and_items(): void
    {
        // Stat Card
        $renderedStat = Blade::render('<x-stat-card value="350+" label="Siswa Aktif" icon="user-group" description="Tahun Ajaran 2026/2027" />');
        $this->assertStringContainsString('350+', $renderedStat);
        $this->assertStringContainsString('Siswa Aktif', $renderedStat);
        $this->assertStringContainsString('Tahun Ajaran 2026/2027', $renderedStat);

        // Gallery Card
        $renderedGallery = Blade::render('<x-gallery-card title="Upacara Milad Muhammadiyah" category="Kegiatan" date="18 November 2026" />');
        $this->assertStringContainsString('Upacara Milad Muhammadiyah', $renderedGallery);
        $this->assertStringContainsString('Kegiatan', $renderedGallery);
        $this->assertStringContainsString('18 November 2026', $renderedGallery);

        // Contact Item
        $renderedContact = Blade::render('<x-contact-item icon="envelope" label="Email Resmi" value="smpm7medan@gmail.com" href="mailto:smpm7medan@gmail.com" />');
        $this->assertStringContainsString('Email Resmi', $renderedContact);
        $this->assertStringContainsString('smpm7medan@gmail.com', $renderedContact);
        $this->assertStringContainsString('mailto:smpm7medan@gmail.com', $renderedContact);

        // Image Placeholder
        $renderedPlaceholder = Blade::render('<x-image-placeholder aspect="video" label="Foto Kegiatan" />');
        $this->assertStringContainsString('aspect-video', $renderedPlaceholder);
        $this->assertStringContainsString('Foto Kegiatan', $renderedPlaceholder);
        $this->assertStringContainsString('SMP Muhammadiyah 7 Medan', $renderedPlaceholder);
    }

    /**
     * Test components preview route returns HTTP 200 OK and renders design system page.
     */
    public function test_components_preview_page_returns_ok(): void
    {
        $response = $this->get('/components-preview');
        $response->assertStatus(200);
        $response->assertSee('Design System Showcase');
        $response->assertSee('DIR-8 Approved');
    }
}
