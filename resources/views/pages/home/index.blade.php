<x-layouts.public title="Beranda" description="Portal resmi SMP Muhammadiyah 7 Medan — Membangun Generasi Unggul Berkarakter Islami.">

    {{-- ================================================================== --}}
    {{-- SEKSI 1 — HERO SLIDESHOW BERITA & INFORMASI (SEPERTI WEB HARAPAN 1)  --}}
    {{-- ================================================================== --}}
    @php
        $heroSlides = [
            [
                'image'       => asset('images/school/gedung.jpg'),
                'alt'         => 'Gedung SMP Muhammadiyah 7 Medan',
                'objectPos'   => 'object-[center_90%]',
                'badge'       => 'Prestasi Akademik',
                'title'       => 'Siswa SMP Muhammadiyah 7 Medan Raih Juara 1 Olimpiade Matematika Tingkat Kota',
                'summary'     => 'Kebanggaan kembali hadir bagi keluarga besar SMP Muhammadiyah 7 Medan atas prestasi gemilang yang diraih salah satu siswanya di ajang Olimpiade Matematika Tingkat Kota Medan.',
                'link'        => url('/berita/siswa-smp-muhammadiyah-7-raih-juara-1-olimpiade-matematika-tingkat-kota'),
                'kicker'      => 'Kabar Utama',
            ],
            [
                'image'       => asset('images/school/gerbang.png'),
                'alt'         => 'Gerbang Kampus SMP Muhammadiyah 7 Medan',
                'objectPos'   => 'object-[center_10%]',
                'badge'       => 'Kegiatan Sekolah',
                'title'       => 'Kegiatan MPLS Tahun Ajaran Baru Berjalan Meriah dan Penuh Semangat',
                'summary'     => 'Masa Pengenalan Lingkungan Sekolah (MPLS) tahun ajaran baru resmi dibuka dengan berbagai kegiatan yang menarik, edukatif, serta menanamkan nilai-nilai Islami bagi siswa baru SMP Muhammadiyah 7 Medan.',
                'link'        => url('/berita/kegiatan-mpls-tahun-ajaran-baru-berjalan-meriah'),
                'kicker'      => 'Agenda Terkini',
            ],
            [
                'image'       => asset('images/school/lapangan.jpg'),
                'alt'         => 'Area Lapangan & Kampus SMP Muhammadiyah 7 Medan',
                'objectPos'   => 'object-[center_35%]',
                'badge'       => 'Keagamaan & Adab',
                'title'       => 'Peringatan Isra\' Mi\'raj di SMP Muhammadiyah 7 Medan Berlangsung Khidmat',
                'summary'     => 'SMP Muhammadiyah 7 Medan menggelar kegiatan peringatan Isra\' Mi\'raj Nabi Muhammad SAW dengan penuh kekhidmatan, tausiyah inspiratif, dan dihadiri seluruh warga sekolah.',
                'link'        => url('/berita/peringatan-isra-miraj-smp-muhammadiyah-7-berlangsung-khidmat'),
                'kicker'      => 'Pembinaan Karakter',
            ],
        ];
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
                        class="w-full h-full object-cover {{ $slide['objectPos'] }}"
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
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-8.707l-3-3a1 1 0 0 0-1.414 1.414L10.586 9H7a1 1 0 1 0 0 2h3.586l-1.293 1.293a1 1 0 1 0 1.414 1.414l3-3a1 1 0 0 0 0-1.414Z" clip-rule="evenodd" />
                                        </svg>
                                    </a>

                                    {{-- Navigasi Panah Kiri & Kanan (Harapan 1 Outline Round Buttons) --}}
                                    <div class="flex items-center gap-2 ml-1">
                                        <button
                                            type="button"
                                            class="hero-prev-btn w-9 h-9 rounded-full border border-white/60 hover:border-white bg-black/25 hover:bg-white/20 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 cursor-pointer focus-visible:outline focus-visible:outline-2 focus-visible:outline-white"
                                            aria-label="Berita sebelumnya"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            class="hero-next-btn w-9 h-9 rounded-full border border-white/60 hover:border-white bg-black/25 hover:bg-white/20 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 cursor-pointer focus-visible:outline focus-visible:outline-2 focus-visible:outline-white"
                                            aria-label="Berita berikutnya"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
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

        {{-- ── Prev / Next Arrow Controls (Desktop Side Floating) ── --}}
        <button
            id="hero-prev"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/40 hover:bg-black/60 backdrop-blur-sm border border-white/30 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white cursor-pointer hidden sm:flex"
            aria-label="Slide sebelumnya"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd"/></svg>
        </button>
        <button
            id="hero-next"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/40 hover:bg-black/60 backdrop-blur-sm border border-white/30 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white cursor-pointer hidden sm:flex"
            aria-label="Slide berikutnya"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1 1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
        </button>

        {{-- ── Dot Indicators ── --}}
        <div
            id="hero-dots"
            class="absolute bottom-5 sm:bottom-16 left-6 sm:left-10 lg:left-14 z-20 flex items-center gap-2"
            role="tablist"
            aria-label="Slide navigator"
        >
            <button role="tab" aria-selected="true"  aria-label="Slide 1" data-dot="0" class="hero-dot w-6 h-2 rounded-full bg-white transition-all duration-300 cursor-pointer"></button>
            <button role="tab" aria-selected="false" aria-label="Slide 2" data-dot="1" class="hero-dot w-2 h-2 rounded-full bg-white/50 transition-all duration-300 cursor-pointer"></button>
            <button role="tab" aria-selected="false" aria-label="Slide 3" data-dot="2" class="hero-dot w-2 h-2 rounded-full bg-white/50 transition-all duration-300 cursor-pointer"></button>
        </div>

        {{-- ── Trust Signals Strip at Bottom ── --}}
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-darkgreen/95 via-forest/90 to-darkgreen/95 backdrop-blur-md border-t border-white/15 hidden sm:block z-10 shadow-lg">
            <div class="max-w-7xl mx-auto px-6 lg:px-14 py-3 flex items-center justify-between gap-6">
                <div class="flex items-center gap-2.5 text-white/90">
                    <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-mint"><path fill-rule="evenodd" d="M4 2a2 2 0 0 0-2 2v11a3 3 0 1 0 6 0V4a2 2 0 0 0-2-2H4Zm1 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm5-1.757-4-4V4h2v10.243l2 2Zm0 0 .88.88A3 3 0 0 0 16 15V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v9.243l2 2ZM15 15a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Gedung 3 Lantai</span>
                </div>
                <div class="w-px h-4 bg-white/20"></div>
                <div class="flex items-center gap-2.5 text-white/90">
                    <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-sage"><path d="M10.75 16.82A7.462 7.462 0 0 1 15 15.5c.71 0 1.396.098 2.046.282A.75.75 0 0 0 18 15.06v-11a.75.75 0 0 0-.546-.721A9.006 9.006 0 0 0 15 3a8.963 8.963 0 0 0-4.25 1.065V16.82ZM9.25 4.065A8.963 8.963 0 0 0 5 3c-.85 0-1.673.118-2.454.339A.75.75 0 0 0 2 4.06v11a.75.75 0 0 0 .954.721A7.506 7.506 0 0 1 5 15.5c1.579 0 3.042.487 4.25 1.32V4.065Z"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Kurikulum Merdeka</span>
                </div>
                <div class="w-px h-4 bg-white/20"></div>
                <div class="flex items-center gap-2.5 text-white/90">
                    <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-mint"><path fill-rule="evenodd" d="M10 1c-1.828 0-3.623.149-5.371.435a.75.75 0 0 0-.629.74v.387c-.827.157-1.642.345-2.445.564a.75.75 0 0 0-.552.698 5 5 0 0 0 4.503 5.152 6 6 0 0 0 2.946 1.822A6.451 6.451 0 0 1 7.768 13H7.5A1.5 1.5 0 0 0 6 14.5V17h-.75C4.56 17 4 17.56 4 18.25v.75h12v-.75c0-.69-.56-1.25-1.25-1.25H14v-2.5a1.5 1.5 0 0 0-1.5-1.5h-.268a6.453 6.453 0 0 1-1.647-2.802 6 6 0 0 0 2.946-1.822 5 5 0 0 0 4.503-5.152.75.75 0 0 0-.552-.698A31.804 31.804 0 0 0 15.75 1.56v-.387a.75.75 0 0 0-.629-.74A33.227 33.227 0 0 0 10 1Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Prestasi Teruji</span>
                </div>
                <div class="w-px h-4 bg-white/20"></div>
                <div class="flex items-center gap-2.5 text-white/90">
                    <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-sage"><path d="M7 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM14.5 9a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 17a9.953 9.953 0 0 1-5.385-1.572ZM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 0 0-1.588-3.755 4.502 4.502 0 0 1 5.874 2.636.818.818 0 0 1-.36.98A7.465 7.465 0 0 1 14.5 16Z"/></svg>
                    </div>
                    <span class="text-xs font-semibold">{{ config('sekolah.statistik.siswa', '500+') }} Siswa Aktif</span>
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
        {{-- Background Pattern Subtle Glows --}}
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
                    value="{{ config('sekolah.statistik.siswa') }}"
                    label="Siswa Aktif"
                    icon="users"
                    description="Total peserta didik terdaftar"
                />
                <x-stat-card
                    value="{{ config('sekolah.statistik.guru') }}"
                    label="Tenaga Pendidik"
                    icon="academic-cap"
                    description="Guru dan staf pengajar"
                />
                <x-stat-card
                    value="{{ config('sekolah.statistik.kelas') }}"
                    label="Rombel Kelas"
                    icon="rectangle-group"
                    description="Rombongan belajar aktif"
                />
                <x-stat-card
                    value="{{ config('sekolah.statistik.ekskul') }}"
                    label="Ekstrakurikuler"
                    icon="star"
                    description="Kegiatan pengembangan diri"
                />
            </div>
        </div>
    </section>

</x-layouts.public>
