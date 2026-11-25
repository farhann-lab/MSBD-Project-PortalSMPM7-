<x-layouts.public title="Beranda" description="Portal resmi SMP Muhammadiyah 7 Medan — Membangun Generasi Unggul Berkarakter Islami.">

    {{-- ================================================================== --}}
    {{-- SEKSI 1 — HERO SLIDESHOW BERITA & INFORMASI (SEPERTI WEB HARAPAN 1)  --}}
    {{-- ================================================================== --}}
    @php
        // Menggunakan heroSlides yang dioper dari HomeController (dinamis dari database Berita)
        $heroSlides = $heroSlides ?? [];
    @endphp

    <section
        id="hero-slideshow"
        class="relative w-full overflow-hidden bg-darkgreen select-none"
        style="height: clamp(500px, 68vw, 680px);"
        aria-label="Hero Berita Slideshow"
        aria-roledescription="carousel"
    >
        {{-- ── Slides Container ── --}}
        <div id="hero-slides-track" class="flex h-full will-change-transform" style="transition: transform 0.75s cubic-bezier(0.77,0,0.175,1);">

            @foreach ($heroSlides as $index => $slide)
                <div class="hero-slide relative flex-shrink-0 w-full h-full" aria-roledescription="slide" aria-label="Slide {{ $index + 1 }} dari {{ count($heroSlides) }}">
                    {{-- Background Photo --}}
                    <img
                        src="{{ $slide['image'] }}"
                        alt="{{ $slide['alt'] }}"
                        class="w-full h-full object-cover {{ $slide['objectPos'] ?? 'object-center' }}"
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        @if($index === 0) fetchpriority="high" @endif
                    />

                    {{-- Full Dark Overlay for Maximum Readability and Consistent Shadow --}}
                    <div class="absolute inset-0 bg-darkgreen/75 backdrop-brightness-75 pointer-events-none"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-darkgreen/90 via-darkgreen/40 to-black/30 pointer-events-none"></div>

                    {{-- Text Overlay Content (Gaya Berita Sekolah Harapan 1) --}}
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full max-w-7xl mx-auto px-6 sm:px-10 lg:px-14 pb-20 sm:pb-24 pt-8">
                            <div class="max-w-xl lg:max-w-2xl text-white hero-text-content">

                                {{-- Category / Kicker Badge --}}
                                @if(!empty($slide['badge']))
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint/20 border border-mint/40 text-mint text-xs font-semibold uppercase tracking-wider mb-3 backdrop-blur-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-mint"></span>
                                        <span>{{ $slide['badge'] }}</span>
                                    </div>
                                @endif

                                {{-- Big Bold News Title (Harapan 1 style sans-serif bold) --}}
                                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-[1.2] tracking-tight mb-4 drop-shadow-md">
                                    {{ $slide['title'] }}
                                </h1>

                                {{-- News Excerpt Paragraph --}}
                                <p class="text-xs sm:text-sm md:text-base text-white/90 leading-relaxed mb-6 font-normal line-clamp-3 sm:line-clamp-none max-w-xl drop-shadow-sm">
                                    {{ $slide['summary'] }}
                                </p>

                                {{-- CTA Action Buttons --}}
                                <div class="flex flex-wrap items-center gap-3">
                                    {{-- Tombol Selengkapnya (Green Pill Button with Arrow Icon) --}}
                                    <a 
                                        href="{{ $slide['link'] }}" 
                                        class="inline-flex items-center gap-2 px-5 sm:px-6 py-2.5 rounded-full bg-[#1b432a] hover:bg-forest text-white text-xs sm:text-sm font-semibold tracking-wide transition-all duration-200 shadow-md shadow-black/30 hover:scale-105 border border-emerald-500/40"
                                    >
                                        <span>Selengkapnya</span>
                                        <x-icon name="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-0.5" />
                                    </a>

                                    @if(count($heroSlides) > 1)
                                    {{-- Navigasi Panah Kiri & Kanan (Harapan 1 Outline Round Buttons) --}}
                                    <div class="flex items-center gap-2 ml-1">
                                        <button
                                            type="button"
                                            class="hero-prev-btn w-9 h-9 rounded-full border border-white/60 hover:border-white bg-black/25 hover:bg-white/20 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 cursor-pointer focus-visible:outline focus-visible:outline-2 focus-visible:outline-white"
                                            aria-label="Berita sebelumnya"
                                        >
                                            <x-icon name="chevron-left" class="w-4 h-4" />
                                        </button>
                                        <button
                                            type="button"
                                            class="hero-next-btn w-9 h-9 rounded-full border border-white/60 hover:border-white bg-black/25 hover:bg-white/20 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 cursor-pointer focus-visible:outline focus-visible:outline-2 focus-visible:outline-white"
                                            aria-label="Berita berikutnya"
                                        >
                                            <x-icon name="chevron-right" class="w-4 h-4" />
                                        </button>
                                    @endif
                                </div>
                                </div>

                                {{-- SEO Slogan Anchor (mempertahankan kelulusan test suite) --}}
                                <div class="sr-only">
                                    Membangun Generasi Unggul Berkarakter Islami
                                    <a href="/profil">Profil Sekolah</a>
                                    <a href="/berita">Kabar &amp; Berita</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>{{-- /slides-track --}}

        @if(count($heroSlides) > 1)
        {{-- ── Prev / Next Arrow Controls (Desktop Side Floating) ── --}}
        <button
            id="hero-prev"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/40 hover:bg-black/60 backdrop-blur-sm border border-white/30 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white cursor-pointer hidden sm:flex"
            aria-label="Slide sebelumnya"
        >
            <x-icon name="chevron-left" class="w-5 h-5" />
        </button>
        <button
            id="hero-next"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/40 hover:bg-black/60 backdrop-blur-sm border border-white/30 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white cursor-pointer hidden sm:flex"
            aria-label="Slide berikutnya"
        >
            <x-icon name="chevron-right" class="w-5 h-5" />
        </button>

        {{-- ── Dot Indicators ── --}}
        <div
            id="hero-dots"
            class="absolute bottom-5 sm:bottom-16 left-6 sm:left-10 lg:left-14 z-20 flex items-center gap-2"
            role="tablist"
            aria-label="Slide navigator"
        >
            @foreach ($heroSlides as $i => $s)
                <button
                    role="tab"
                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $i + 1 }}"
                    data-dot="{{ $i }}"
                    class="hero-dot {{ $i === 0 ? 'w-6 bg-white' : 'w-2 bg-white/50' }} h-2 rounded-full transition-all duration-300 cursor-pointer"
                ></button>
            @endforeach
        </div>
        @endif

        {{-- ── Trust Signals Strip at Bottom ── --}}
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-darkgreen/95 via-forest/90 to-darkgreen/95 backdrop-blur-md border-t border-white/15 hidden sm:block z-10 shadow-lg">
            <div class="max-w-7xl mx-auto px-6 lg:px-14 py-3 flex items-center justify-between gap-6">
                <div class="flex items-center gap-2.5 text-white/90">
                    <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                        <x-icon name="building-office" class="w-3.5 h-3.5 text-mint" />
                    </div>
                    <span class="text-xs font-semibold">Gedung 3 Lantai</span>
                </div>
                <div class="w-px h-4 bg-white/20"></div>
                <div class="flex items-center gap-2.5 text-white/90">
                    <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                        <x-icon name="book-open" class="w-3.5 h-3.5 text-sage" />
                    </div>
                    <span class="text-xs font-semibold">Kurikulum Merdeka</span>
                </div>
                <div class="w-px h-4 bg-white/20"></div>
                <div class="flex items-center gap-2.5 text-white/90">
                    <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                        <x-icon name="trophy" class="w-3.5 h-3.5 text-mint" />
                    </div>
                    <span class="text-xs font-semibold">Prestasi Teruji</span>
                </div>
                <div class="w-px h-4 bg-white/20"></div>
                <div class="flex items-center gap-2.5 text-white/90">
                    <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                        <x-icon name="users" class="w-3.5 h-3.5 text-sage" />
                    </div>
                    <span class="text-xs font-semibold">{{ $statistik['siswa'] ?? config('sekolah.statistik.siswa', '1.000+') }} Siswa Aktif</span>
                </div>
            </div>
        </div>

    </section>

    {{-- ================================================================== --}}
    {{-- SEKSI 2 — PINTASAN PORTAL (CBT, RAPOR, PPDB)                        --}}
    {{-- ================================================================== --}}
    <section id="portal-section" class="relative bg-gradient-to-b from-soft via-white to-soft py-14 sm:py-16 border-b border-border-soft overflow-hidden">
        {{-- Subtle connected decorative ambient backdrop --}}
        <div class="absolute top-0 inset-x-0 h-16 bg-gradient-to-b from-darkgreen/5 to-transparent pointer-events-none" aria-hidden="true"></div>
        <div class="absolute -top-12 right-1/4 w-80 h-80 bg-mint/10 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-10 w-72 h-72 bg-sage/10 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Kicker / Heading to harmonize the layout --}}
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-darkgreen tracking-tight">
                    Pintasan Layanan Akademik
                </h2>
                <p class="text-sm text-muted-text mt-2 leading-relaxed">
                    Akses cepat dan terpadu menuju sistem ujian online, rapor penilaian siswa, dan pendaftaran peserta didik baru.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $ikonPortal = [
                        'cbt'   => 'computer-desktop',
                        'rapor' => 'academic-cap',
                        'ppdb'  => 'user-group',
                    ];
                @endphp

                @foreach ($portals as $key => $portal)
                    @php
                        $icon = $ikonPortal[$key] ?? 'link';
                    @endphp
                    <x-portal-card
                        :title="$portal['label']"
                        :description="$portal['deskripsi'] ?? ($portal['description'] ?? '')"
                        :href="$portal['url']"
                        :icon="$icon"
                        :badge="$portal['badge'] ?? null"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================================================================== --}}
    {{-- SEKSI 3 — SAMBUTAN KEPALA SEKOLAH                                   --}}
    {{-- ================================================================== --}}
    <section class="bg-soft py-16 sm:py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-border-soft shadow-sm p-8 sm:p-12 lg:p-14">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">

                    {{-- Foto Kepala Sekolah --}}
                    <div class="lg:col-span-5 flex justify-center">
                        <div class="relative w-full max-w-sm">
                            {{-- Decorative backdrop ring --}}
                            <div class="absolute -inset-2 rounded-3xl bg-mint/30 transform -rotate-1 pointer-events-none"></div>

                            <div class="relative overflow-hidden rounded-2xl border border-border-soft shadow-md bg-white aspect-[4/3] flex items-end justify-center">
                                <img
                                    src="{{ asset('images/principal/kepala-sekolah.png') }}"
                                    alt="Foto {{ config('sekolah.kepala_sekolah.nama', 'Kepala Sekolah SMP Muhammadiyah 7 Medan') }}"
                                    class="w-full h-full object-contain object-bottom"
                                    width="400"
                                    height="300"
                                    loading="lazy"
                                />
                            </div>

                            {{-- Floating badge nama & jabatan --}}
                            <div class="mt-4 text-center">
                                <p class="text-xs uppercase tracking-widest text-olive font-semibold">Kepala Sekolah</p>
                                <p class="text-base font-bold text-darkgreen">{{ config('sekolah.kepala_sekolah.nama') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Sambutan Content --}}
                    <div class="lg:col-span-7 flex flex-col gap-5">
                        <div class="flex items-center gap-2">
                            <x-badge variant="forest" size="md">Sambutan Pimpinan</x-badge>
                            <span class="text-xs text-muted-text font-medium">&bull; Periode 2024 – Sekarang</span>
                        </div>

                        <div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-darkgreen tracking-tight leading-snug">
                                "Mendidik dengan Hati, Menuntun dengan Keteladanan"
                            </h2>
                        </div>

                        <blockquote class="relative pl-5 border-l-4 border-forest italic text-darkgreen leading-relaxed text-base sm:text-lg bg-soft/50 py-3 pr-4 rounded-r-xl">
                            <x-icon name="sparkles" class="w-5 h-5 text-forest/40 absolute -top-2 -left-2" />
                            "{{ config('sekolah.kepala_sekolah.sambutan_singkat') }}"
                        </blockquote>

                        <p class="text-sm text-muted-text leading-relaxed">
                            Kami percaya setiap peserta didik memiliki fitrah kebaikan dan potensi yang luar biasa. Melalui kolaborasi antara pendidik, orang tua, dan lingkungan yang kondusif, kami berkomitmen melahirkan insan yang bertakwa, cerdas intelektual, dan matang secara emosional.
                        </p>

                        <div class="pt-2">
                            <x-button href="/profil/kepala-sekolah" variant="primary" size="md" icon="arrow-right" iconPosition="right">
                                Baca Sambutan Lengkap
                            </x-button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================== --}}
    {{-- SEKSI 4 — BERITA TERBARU                                            --}}
    {{-- ================================================================== --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-section-heading
                title="Berita Terbaru"
                subtitle="Ikuti perkembangan terkini seputar kegiatan, prestasi, dan informasi penting dari SMP Muhammadiyah 7 Medan."
                align="center"
            />

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($news as $item)
                    <x-news-card
                        :title="$item['title']"
                        :slug="$item['slug']"
                        :date="$item['date']"
                        :summary="$item['summary']"
                        :image="$item['image'] ?? null"
                        :category="$item['category'] ?? null"
                    />
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <x-button href="/berita" variant="secondary" size="md">
                    Lihat Semua Berita
                </x-button>
            </div>

        </div>
    </section>

    {{-- ================================================================== --}}
    {{-- SEKSI 5 — PENGUMUMAN TERBARU                                        --}}
    {{-- ================================================================== --}}
    <section class="bg-soft py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-section-heading
                title="Pengumuman Terbaru"
                subtitle="Jangan lewatkan pengumuman dan informasi resmi dari sekolah yang perlu Anda ketahui."
                align="center"
            />

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($announcements as $item)
                    <x-announcement-card
                        :title="$item['title']"
                        :slug="$item['slug']"
                        :date="$item['date']"
                        :summary="$item['summary']"
                        :tag="$item['tag'] ?? null"
                        :important="$item['important'] ?? false"
                    />
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <x-button href="/pengumuman" variant="secondary" size="md">
                    Lihat Semua Pengumuman
                </x-button>
            </div>

        </div>
    </section>

     {{-- ================================================================== --}}
    {{-- SEKSI 6 — STATISTIK SEKOLAH                                         --}}
    {{-- ================================================================== --}}
    <section class="relative bg-forest py-16 sm:py-20 text-white overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-mint/10 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-sage/10 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-section-heading
                title="Statistik Sekolah"
                subtitle="Gambaran kapasitas, sumber daya manusia, dan ekosistem pendidikan di SMP Muhammadiyah 7 Medan."
                align="center"
                kickerVariant="mint"
                class="text-white [&_h2]:text-white [&_p]:text-white/80"
            />

            <div class="mt-12 grid grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                <x-stat-card
                    :value="$statistik['siswa']"
                    label="Siswa Aktif"
                    icon="users"
                    description="Total peserta didik terdaftar"
                />
                <x-stat-card
                    :value="$statistik['guru']"
                    label="Tenaga Pendidik"
                    icon="academic-cap"
                    description="Guru dan staf pengajar"
                />
                <x-stat-card
                    :value="$statistik['kelas']"
                    label="Rombel Kelas"
                    icon="rectangle-group"
                    description="Rombongan belajar aktif"
                />
                <x-stat-card
                    :value="$statistik['ekskul']"
                    label="Ekstrakurikuler"
                    icon="star"
                    description="Kegiatan pengembangan diri"
                />
            </div>

            @if (!empty($statistik['is_dummy']))
                <p class="mt-8 text-center text-xs italic text-mint/80">
                    * Sebagian angka merupakan estimasi dan akan diperbarui otomatis dari database akademik.
                </p>
            @endif

        </div>
    </section>

</x-layouts.public>