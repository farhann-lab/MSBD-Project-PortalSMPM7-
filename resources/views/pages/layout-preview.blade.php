<x-layouts.public title="Pratinjau Shell Layout & Navigasi" description="Verifikasi fungsional dan visual Milestone 2 — Master Layout, Navbar Responsif, Mobile Drawer, dan Footer 4-Kolom.">

    {{-- Breadcrumb Showcase --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'SIAKAD Publik', 'url' => url('/')],
                ['label' => 'Milestone 2', 'url' => url('/layout-preview')],
                ['label' => 'Global Layout & Navigation Shell', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Hero Section / Banner --}}
    <section class="bg-gradient-to-b from-white to-soft py-12 lg:py-16 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <x-badge variant="forest" size="md">Milestone 2: Global Shell Active</x-badge>
                    <x-badge variant="sage" size="md">Phase 2: Selesai</x-badge>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-darkgreen tracking-tight leading-tight">
                    Fondasi Layout Publik & Shell Navigasi Responsif
                </h1>

                <p class="text-base sm:text-lg text-muted-text leading-relaxed">
                    Halaman ini mendemonstrasikan Master Layout publik (<code class="bg-soft px-2 py-0.5 rounded text-forest font-mono text-sm">layouts/public.blade.php</code>), Navbar desktop dengan submenu dropdown, Mobile Navigation Drawer dengan accordion (<code class="bg-soft px-2 py-0.5 rounded text-forest font-mono text-sm">&lt;x-navbar /&gt;</code>), serta Footer 4-kolom (<code class="bg-soft px-2 py-0.5 rounded text-forest font-mono text-sm">&lt;x-footer /&gt;</code>).
                </p>

                <div class="flex flex-wrap gap-3 pt-2">
                    <x-button href="{{ url('/components-preview') }}" variant="secondary" icon="arrow-right" iconPosition="right">
                        Lihat Showcase Komponen (Phase 1)
                    </x-button>
                    <x-button href="#feature-checklist" variant="outline">
                        Periksa Kriteria Penerimaan
                    </x-button>
                </div>
            </div>
        </div>
    </section>

    {{-- Content Demonstration Section --}}
    <section id="feature-checklist" class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            {{-- Feature Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- FE-LAYOUT-001 --}}
                <div class="bg-white rounded-2xl p-6 border border-border-soft shadow-xs space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-forest/10 text-forest flex items-center justify-center">
                        <x-icon name="building-office" class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-bold text-darkgreen">FE-LAYOUT-001</h3>
                    <p class="text-sm font-semibold text-forest">Master Public Layout</p>
                    <p class="text-xs text-muted-text leading-relaxed">
                        HTML semantik, dynamic page title & meta SEO, skip-to-content keyboard link, dan Vite assets loader.
                    </p>
                    <x-badge variant="forest" size="sm" dot>Lolos & Terintegrasi</x-badge>
                </div>

                {{-- FE-LAYOUT-002 --}}
                <div class="bg-white rounded-2xl p-6 border border-border-soft shadow-xs space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-forest/10 text-forest flex items-center justify-center">
                        <x-icon name="user-group" class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-bold text-darkgreen">FE-LAYOUT-002</h3>
                    <p class="text-sm font-semibold text-forest">Desktop Navbar & Dropdowns</p>
                    <p class="text-xs text-muted-text leading-relaxed">
                        Submenu bertingkat untuk Profil Sekolah, Direktori, dan Kesiswaan dengan dukungan navigasi mouse & keyboard.
                    </p>
                    <x-badge variant="forest" size="sm" dot>Lolos & Terintegrasi</x-badge>
                </div>

                {{-- FE-LAYOUT-003 --}}
                <div class="bg-white rounded-2xl p-6 border border-border-soft shadow-xs space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-forest/10 text-forest flex items-center justify-center">
                        <x-icon name="bars-3" class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-bold text-darkgreen">FE-LAYOUT-003</h3>
                    <p class="text-sm font-semibold text-forest">Mobile Navigation Drawer</p>
                    <p class="text-xs text-muted-text leading-relaxed">
                        Slide-in drawer, scroll lock, backdrop blur, accordion submenus, tombol Login SIAKAD, dan tombol Escape support.
                    </p>
                    <x-badge variant="forest" size="sm" dot>Lolos & Terintegrasi</x-badge>
                </div>

                {{-- FE-LAYOUT-004 --}}
                <div class="bg-white rounded-2xl p-6 border border-border-soft shadow-xs space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-forest/10 text-forest flex items-center justify-center">
                        <x-icon name="sparkles" class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-bold text-darkgreen">FE-LAYOUT-004</h3>
                    <p class="text-sm font-semibold text-forest">Global 4-Column Footer</p>
                    <p class="text-xs text-muted-text leading-relaxed">
                        Identitas sekolah, navigasi cepat, akses portal eksternal, rincian kontak lengkap, dan tombol back-to-top dinamis.
                    </p>
                    <x-badge variant="forest" size="sm" dot>Lolos & Terintegrasi</x-badge>
                </div>
            </div>

            {{-- Interactive Test Card --}}
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-border-soft shadow-xs space-y-6">
                <div class="border-b border-border-soft pb-4">
                    <h2 class="text-xl sm:text-2xl font-bold text-darkgreen">Pengujian Interaksi Navigasi</h2>
                    <p class="text-sm text-muted-text mt-1">Uji kelayakan responsif dan fitur navigasi secara langsung pada browser.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-soft/70 rounded-xl p-5 border border-border-soft space-y-2">
                        <h4 class="font-bold text-sm text-forest flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-forest" />
                            <span>1. Uji Menu Dropdown Desktop</span>
                        </h4>
                        <p class="text-xs text-muted-text">
                            Arahkan kursor atau gunakan tombol Tab untuk menjelajahi menu Profil Sekolah, Direktori, dan Kesiswaan di navbar atas. Tekan Escape untuk menutup.
                        </p>
                    </div>

                    <div class="bg-soft/70 rounded-xl p-5 border border-border-soft space-y-2">
                        <h4 class="font-bold text-sm text-forest flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-forest" />
                            <span>2. Uji Layar Seluler (&lt; 1024px)</span>
                        </h4>
                        <p class="text-xs text-muted-text">
                            Perkecil lebar jendela browser atau aktifkan Mobile Emulation di DevTools. Tekan tombol hamburger di kanan atas untuk membuka panel navigasi.
                        </p>
                    </div>

                    <div class="bg-soft/70 rounded-xl p-5 border border-border-soft space-y-2">
                        <h4 class="font-bold text-sm text-forest flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-forest" />
                            <span>3. Uji Footer &amp; Back-To-Top</span>
                        </h4>
                        <p class="text-xs text-muted-text">
                            Gulir ke bagian paling bawah halaman untuk melihat 4 kolom footer. Klik tombol "Ke Atas" di baris hak cipta untuk kembali ke bagian atas dengan mulus.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.public>
