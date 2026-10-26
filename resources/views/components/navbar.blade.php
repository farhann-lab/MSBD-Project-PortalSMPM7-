@php
    $isHome = request()->is('/');
    $isProfile = request()->is('profil*');
    $isDirectory = request()->is('guru*') || request()->is('siswa*');
    $isStudentLife = request()->is('kesiswaan*');
    $isGallery = request()->is('galeri*');
    $isNews = request()->is('berita*');
    $isAnnouncements = request()->is('pengumuman*');

    $profileItems = [
        ['label' => 'Tentang Sekolah', 'url' => url('/profil'), 'active' => request()->is('profil') && !request()->is('profil/*'), 'desc' => 'Visi, misi, sejarah & profil sekolah'],
        ['label' => 'Kepala Sekolah', 'url' => url('/profil/kepala-sekolah'), 'active' => request()->is('profil/kepala-sekolah*'), 'desc' => 'Sambutan resmi pimpinan sekolah'],
        ['label' => 'Struktur Organisasi', 'url' => url('/profil/struktur'), 'active' => request()->is('profil/struktur*'), 'desc' => 'Bagan kepemimpinan & tata kelola'],
        ['label' => 'Sarana & Prasarana', 'url' => url('/profil/fasilitas'), 'active' => request()->is('profil/fasilitas*'), 'desc' => 'Fasilitas & sarana penunjang belajar'],
    ];

    $directoryItems = [
        ['label' => 'Direktori Guru', 'url' => url('/guru'), 'active' => request()->is('guru*'), 'desc' => 'Tenaga pendidik & mata pelajaran'],
        ['label' => 'Direktori Siswa', 'url' => url('/siswa'), 'active' => request()->is('siswa*'), 'desc' => 'Daftar peserta didik aktif per kelas'],
    ];

    $studentLifeItems = [
        ['label' => 'Organisasi Siswa', 'url' => url('/kesiswaan/organisasi'), 'active' => request()->is('kesiswaan/organisasi*'), 'desc' => 'Ikatan Pelajar Muhammadiyah & OSIS'],
        ['label' => 'Ekstrakurikuler', 'url' => url('/kesiswaan/ekstrakurikuler'), 'active' => request()->is('kesiswaan/ekstrakurikuler*'), 'desc' => 'Pengembangan minat, seni & olahraga'],
        ['label' => 'Prestasi Siswa', 'url' => url('/kesiswaan/prestasi'), 'active' => request()->is('kesiswaan/prestasi*'), 'desc' => 'Torehan prestasi & piala kejuaraan'],
    ];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20 lg:h-22">
        {{-- Left: Brand Logo --}}
        <div class="flex items-center shrink-0">
            <x-school-logo size="md" />
        </div>

        {{-- Center/Right: Desktop Navigation Menu (hidden on mobile/tablet) --}}
        <nav class="hidden lg:flex items-center gap-1 xl:gap-2" aria-label="Navigasi Utama">
            {{-- Beranda --}}
            <a href="{{ url('/') }}" 
               class="px-3 py-2 rounded-xl text-sm font-medium transition-colors duration-150 {{ $isHome ? 'text-forest font-semibold bg-forest/8' : 'text-darkgreen hover:text-forest hover:bg-soft' }}"
               {{ $isHome ? 'aria-current="page"' : '' }}>
                Beranda
            </a>

            {{-- Profil Sekolah (Desktop Dropdown) --}}
            <div class="relative dropdown-container" data-dropdown="profile">
                <button type="button" 
                        class="dropdown-toggle px-3 py-2 rounded-xl text-sm font-medium transition-colors duration-150 inline-flex items-center gap-1.5 cursor-pointer {{ $isProfile ? 'text-forest font-semibold bg-forest/8' : 'text-darkgreen hover:text-forest hover:bg-soft' }}"
                        aria-expanded="false" 
                        aria-haspopup="true">
                    <span>Profil Sekolah</span>
                    <x-icon name="chevron-down" class="w-4 h-4 text-muted-text dropdown-chevron transition-transform duration-200" />
                </button>

                <div class="dropdown-menu absolute top-full left-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-border-soft p-2 z-50 opacity-0 pointer-events-none invisible transform -translate-y-2 transition-all duration-200 origin-top-left"
                     role="menu" 
                     aria-label="Submenu Profil Sekolah">
                    @foreach($profileItems as $item)
                        <a href="{{ $item['url'] }}" 
                           class="flex flex-col px-3.5 py-2.5 rounded-xl transition-all duration-150 {{ $item['active'] ? 'bg-forest/10 text-forest font-semibold' : 'hover:bg-soft text-darkgreen hover:text-forest' }}"
                           role="menuitem"
                           {{ $item['active'] ? 'aria-current="page"' : '' }}>
                            <span class="text-sm {{ $item['active'] ? 'font-bold text-forest' : 'font-medium' }}">{{ $item['label'] }}</span>
                            <span class="text-xs text-muted-text font-normal mt-0.5 leading-snug">{{ $item['desc'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Direktori (Desktop Dropdown) --}}
            <div class="relative dropdown-container" data-dropdown="directory">
                <button type="button" 
                        class="dropdown-toggle px-3 py-2 rounded-xl text-sm font-medium transition-colors duration-150 inline-flex items-center gap-1.5 cursor-pointer {{ $isDirectory ? 'text-forest font-semibold bg-forest/8' : 'text-darkgreen hover:text-forest hover:bg-soft' }}"
                        aria-expanded="false" 
                        aria-haspopup="true">
                    <span>Direktori</span>
                    <x-icon name="chevron-down" class="w-4 h-4 text-muted-text dropdown-chevron transition-transform duration-200" />
                </button>

                <div class="dropdown-menu absolute top-full left-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-border-soft p-2 z-50 opacity-0 pointer-events-none invisible transform -translate-y-2 transition-all duration-200 origin-top-left"
                     role="menu" 
                     aria-label="Submenu Direktori">
                    @foreach($directoryItems as $item)
                        <a href="{{ $item['url'] }}" 
                           class="flex flex-col px-3.5 py-2.5 rounded-xl transition-colors duration-150 {{ $item['active'] ? 'bg-forest/10 text-forest font-semibold' : 'hover:bg-soft text-darkgreen' }}"
                           role="menuitem"
                           {{ $item['active'] ? 'aria-current="page"' : '' }}>
                            <span class="text-sm {{ $item['active'] ? 'font-semibold text-forest' : 'font-medium' }}">{{ $item['label'] }}</span>
                            <span class="text-xs text-muted-text font-normal mt-0.5">{{ $item['desc'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Kesiswaan (Desktop Dropdown) --}}
            <div class="relative dropdown-container" data-dropdown="student-life">
                <button type="button" 
                        class="dropdown-toggle px-3 py-2 rounded-xl text-sm font-medium transition-colors duration-150 inline-flex items-center gap-1.5 cursor-pointer {{ $isStudentLife ? 'text-forest font-semibold bg-forest/8' : 'text-darkgreen hover:text-forest hover:bg-soft' }}"
                        aria-expanded="false" 
                        aria-haspopup="true">
                    <span>Kesiswaan</span>
                    <x-icon name="chevron-down" class="w-4 h-4 text-muted-text dropdown-chevron transition-transform duration-200" />
                </button>

                <div class="dropdown-menu absolute top-full left-0 mt-2 w-76 bg-white rounded-2xl shadow-xl border border-border-soft p-2 z-50 opacity-0 pointer-events-none invisible transform -translate-y-2 transition-all duration-200 origin-top-left"
                     role="menu" 
                     aria-label="Submenu Kesiswaan">
                    @foreach($studentLifeItems as $item)
                        <a href="{{ $item['url'] }}" 
                           class="flex flex-col px-3.5 py-2.5 rounded-xl transition-colors duration-150 {{ $item['active'] ? 'bg-forest/10 text-forest font-semibold' : 'hover:bg-soft text-darkgreen' }}"
                           role="menuitem"
                           {{ $item['active'] ? 'aria-current="page"' : '' }}>
                            <span class="text-sm {{ $item['active'] ? 'font-semibold text-forest' : 'font-medium' }}">{{ $item['label'] }}</span>
                            <span class="text-xs text-muted-text font-normal mt-0.5">{{ $item['desc'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Galeri --}}
            <a href="{{ url('/galeri') }}" 
               class="px-3 py-2 rounded-xl text-sm font-medium transition-colors duration-150 {{ $isGallery ? 'text-forest font-semibold bg-forest/8' : 'text-darkgreen hover:text-forest hover:bg-soft' }}"
               {{ $isGallery ? 'aria-current="page"' : '' }}>
                Galeri
            </a>

            {{-- Berita --}}
            <a href="{{ url('/berita') }}" 
               class="px-3 py-2 rounded-xl text-sm font-medium transition-colors duration-150 {{ $isNews ? 'text-forest font-semibold bg-forest/8' : 'text-darkgreen hover:text-forest hover:bg-soft' }}"
               {{ $isNews ? 'aria-current="page"' : '' }}>
                Berita
            </a>

            {{-- Pengumuman --}}
            <a href="{{ url('/pengumuman') }}" 
               class="px-3 py-2 rounded-xl text-sm font-medium transition-colors duration-150 {{ $isAnnouncements ? 'text-forest font-semibold bg-forest/8' : 'text-darkgreen hover:text-forest hover:bg-soft' }}"
               {{ $isAnnouncements ? 'aria-current="page"' : '' }}>
                Pengumuman
            </a>

            {{-- CTA Tombol Login SIAKAD --}}
            <div class="ml-2 xl:ml-3 pl-2 xl:pl-3 border-l border-border-soft">
                <x-button href="{{ url('/login') }}" variant="primary" size="md" icon="arrow-right" iconPosition="right">
                    Login SIAKAD
                </x-button>
            </div>
        </nav>

        {{-- Mobile Hamburger Trigger Button (visible < 1024px) --}}
        <div class="flex items-center gap-2 lg:hidden">
            <x-button href="{{ url('/login') }}" variant="primary" size="sm" class="sm:inline-flex hidden">
                Login SIAKAD
            </x-button>

            <button type="button" 
                    id="mobile-nav-toggle"
                    class="inline-flex items-center justify-center w-11 h-11 min-h-11 min-w-11 rounded-xl border border-border-soft text-darkgreen hover:text-forest hover:bg-soft focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest transition-colors cursor-pointer select-none"
                    aria-label="Buka menu navigasi" 
                    aria-expanded="false" 
                    aria-controls="mobile-nav-drawer">
                <span class="sr-only">Buka menu utama</span>
                <x-icon name="bars-3" id="icon-hamburger" class="w-6 h-6 transition-transform" />
                <x-icon name="x-mark" id="icon-close" class="w-6 h-6 hidden transition-transform" />
            </button>
        </div>
    </div>
</div>

{{-- Mobile Navigation Overlay Backdrop --}}
<div id="mobile-nav-backdrop" 
     class="fixed inset-0 bg-darkgreen/40 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" 
     aria-hidden="true"></div>

{{-- Mobile Navigation Drawer Panel --}}
<aside id="mobile-nav-drawer" 
       class="fixed top-0 right-0 bottom-0 w-full max-w-sm sm:max-w-md bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col lg:hidden"
       role="dialog" 
       aria-modal="true" 
       aria-label="Navigasi Seluler SMP Muhammadiyah 7 Medan">

    {{-- Mobile Drawer Header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-border-soft shrink-0">
        <x-school-logo size="sm" />

        <button type="button" 
                id="mobile-nav-drawer-close"
                class="inline-flex items-center justify-center w-11 h-11 min-h-11 min-w-11 rounded-xl text-muted-text hover:text-darkgreen hover:bg-soft focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest transition-colors cursor-pointer"
                aria-label="Tutup menu navigasi">
            <x-icon name="x-mark" class="w-6 h-6" />
        </button>
    </div>

    {{-- Mobile Drawer Navigation Links (Scrollable) --}}
    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5 overscroll-contain" aria-label="Menu Navigasi Seluler">
        {{-- Beranda --}}
        <a href="{{ url('/') }}" 
           class="flex items-center justify-between min-h-11 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ $isHome ? 'bg-forest/10 text-forest font-semibold' : 'text-darkgreen hover:bg-soft' }}"
           {{ $isHome ? 'aria-current="page"' : '' }}>
            <span>Beranda</span>
            <x-icon name="chevron-right" class="w-4 h-4 text-muted-text" />
        </a>

        {{-- Profil Sekolah Accordion --}}
        <div class="mobile-accordion rounded-xl border border-border-soft overflow-hidden">
            <button type="button" 
                    class="mobile-accordion-btn flex items-center justify-between w-full min-h-11 px-3.5 py-2.5 text-sm font-medium text-left cursor-pointer transition-colors {{ $isProfile ? 'text-forest font-semibold bg-forest/5' : 'text-darkgreen hover:bg-soft' }}"
                    aria-expanded="{{ $isProfile ? 'true' : 'false' }}">
                <span>Profil Sekolah</span>
                <x-icon name="chevron-down" class="mobile-accordion-icon w-4 h-4 text-muted-text transition-transform duration-200 {{ $isProfile ? 'rotate-180' : '' }}" />
            </button>
            <div class="mobile-accordion-content {{ $isProfile ? '' : 'hidden' }} bg-soft/50 px-2 py-1.5 space-y-1 border-t border-border-soft/60">
                @foreach($profileItems as $item)
                    <a href="{{ $item['url'] }}" 
                       class="flex items-center min-h-10 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ $item['active'] ? 'bg-forest text-white font-semibold' : 'text-darkgreen hover:bg-white' }}"
                       {{ $item['active'] ? 'aria-current="page"' : '' }}>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Direktori Accordion --}}
        <div class="mobile-accordion rounded-xl border border-border-soft overflow-hidden">
            <button type="button" 
                    class="mobile-accordion-btn flex items-center justify-between w-full min-h-11 px-3.5 py-2.5 text-sm font-medium text-left cursor-pointer transition-colors {{ $isDirectory ? 'text-forest font-semibold bg-forest/5' : 'text-darkgreen hover:bg-soft' }}"
                    aria-expanded="{{ $isDirectory ? 'true' : 'false' }}">
                <span>Direktori</span>
                <x-icon name="chevron-down" class="mobile-accordion-icon w-4 h-4 text-muted-text transition-transform duration-200 {{ $isDirectory ? 'rotate-180' : '' }}" />
            </button>
            <div class="mobile-accordion-content {{ $isDirectory ? '' : 'hidden' }} bg-soft/50 px-2 py-1.5 space-y-1 border-t border-border-soft/60">
                @foreach($directoryItems as $item)
                    <a href="{{ $item['url'] }}" 
                       class="flex items-center min-h-10 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ $item['active'] ? 'bg-forest text-white font-semibold' : 'text-darkgreen hover:bg-white' }}"
                       {{ $item['active'] ? 'aria-current="page"' : '' }}>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Kesiswaan Accordion --}}
        <div class="mobile-accordion rounded-xl border border-border-soft overflow-hidden">
            <button type="button" 
                    class="mobile-accordion-btn flex items-center justify-between w-full min-h-11 px-3.5 py-2.5 text-sm font-medium text-left cursor-pointer transition-colors {{ $isStudentLife ? 'text-forest font-semibold bg-forest/5' : 'text-darkgreen hover:bg-soft' }}"
                    aria-expanded="{{ $isStudentLife ? 'true' : 'false' }}">
                <span>Kesiswaan</span>
                <x-icon name="chevron-down" class="mobile-accordion-icon w-4 h-4 text-muted-text transition-transform duration-200 {{ $isStudentLife ? 'rotate-180' : '' }}" />
            </button>
            <div class="mobile-accordion-content {{ $isStudentLife ? '' : 'hidden' }} bg-soft/50 px-2 py-1.5 space-y-1 border-t border-border-soft/60">
                @foreach($studentLifeItems as $item)
                    <a href="{{ $item['url'] }}" 
                       class="flex items-center min-h-10 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ $item['active'] ? 'bg-forest text-white font-semibold' : 'text-darkgreen hover:bg-white' }}"
                       {{ $item['active'] ? 'aria-current="page"' : '' }}>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Galeri --}}
        <a href="{{ url('/galeri') }}" 
           class="flex items-center justify-between min-h-11 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ $isGallery ? 'bg-forest/10 text-forest font-semibold' : 'text-darkgreen hover:bg-soft' }}"
           {{ $isGallery ? 'aria-current="page"' : '' }}>
            <span>Galeri</span>
            <x-icon name="chevron-right" class="w-4 h-4 text-muted-text" />
        </a>

        {{-- Berita --}}
        <a href="{{ url('/berita') }}" 
           class="flex items-center justify-between min-h-11 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ $isNews ? 'bg-forest/10 text-forest font-semibold' : 'text-darkgreen hover:bg-soft' }}"
           {{ $isNews ? 'aria-current="page"' : '' }}>
            <span>Berita</span>
            <x-icon name="chevron-right" class="w-4 h-4 text-muted-text" />
        </a>

        {{-- Pengumuman --}}
        <a href="{{ url('/pengumuman') }}" 
           class="flex items-center justify-between min-h-11 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ $isAnnouncements ? 'bg-forest/10 text-forest font-semibold' : 'text-darkgreen hover:bg-soft' }}"
           {{ $isAnnouncements ? 'aria-current="page"' : '' }}>
            <span>Pengumuman</span>
            <x-icon name="chevron-right" class="w-4 h-4 text-muted-text" />
        </a>
    </nav>

    {{-- Mobile Drawer Footer (CTA & Contact info) --}}
    <div class="p-4 border-t border-border-soft bg-soft/30 space-y-3 shrink-0">
        <x-button href="{{ url('/login') }}" variant="primary" size="lg" class="w-full shadow-sm" icon="arrow-right" iconPosition="right">
            Login SIAKAD
        </x-button>

        <div class="text-[11px] text-muted-text text-center space-y-1 pt-1">
            <p class="font-medium text-darkgreen">{{ config('sekolah.kontak.alamat_lengkap') }}</p>
            <p>Telp: {{ config('sekolah.kontak.telepon') }} &bull; {{ config('sekolah.kontak.email') }}</p>
        </div>
    </div>
</aside>
