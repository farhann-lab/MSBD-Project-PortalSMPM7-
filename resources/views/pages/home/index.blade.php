<x-layouts.public title="Beranda" description="Portal resmi SMP Muhammadiyah 7 Medan — Membangun Generasi Unggul Berkarakter Islami.">

    {{-- ================================================================== --}}
    {{-- SEKSI 1 — HERO                                                      --}}
    {{-- ================================================================== --}}
    <section class="relative bg-gradient-to-b from-white via-soft/60 to-soft py-14 sm:py-18 lg:py-20 overflow-hidden border-b border-border-soft/60">
        {{-- Subtle Islamic geometric / accent circles in background --}}
        <div class="absolute top-0 right-1/4 -translate-y-1/2 w-96 h-96 bg-mint/15 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-10 translate-y-1/3 w-80 h-80 bg-sage/10 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">

                {{-- Kolom Kiri: 7 cols --}}
                <div class="lg:col-span-7 flex flex-col gap-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <x-badge variant="forest" size="md">
                            SMP Muhammadiyah 7 Medan
                        </x-badge>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white border border-border-soft text-olive shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-forest animate-pulse"></span>
                            Terakreditasi A &bull; NPSN 10257325
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-[1.18] text-darkgreen">
                        Membangun Generasi <span class="text-forest">Unggul</span>, Mandiri &amp; Berakhlak Islami
                    </h1>

                    <p class="text-base sm:text-lg text-muted-text leading-relaxed max-w-xl">
                        Selamat datang di portal informasi resmi SIAKAD SMP Muhammadiyah 7 Medan. Wadah pendidikan Islam modern yang memadukan keunggulan akademik, pembiasaan adab Qur'ani, serta penguasaan teknologi digital.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <x-button href="/profil" variant="primary" size="lg" icon="arrow-right" iconPosition="right">
                            Profil Sekolah
                        </x-button>
                        <x-button href="/berita" variant="secondary" size="lg" icon="newspaper" iconPosition="left">
                            Kabar &amp; Berita
                        </x-button>
                        <x-button href="/login" variant="outline" size="lg" icon="academic-cap" iconPosition="left">
                            Login SIAKAD
                        </x-button>
                    </div>

                    {{-- Quick Trust Signals / Highlights --}}
                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-border-soft/80 mt-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-forest/10 flex items-center justify-center text-forest shrink-0">
                                <x-icon name="building-office" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-darkgreen">Gedung Sendiri</p>
                                <p class="text-[11px] text-muted-text">3 Lantai &amp; Fasilitas Lengkap</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-mint/40 flex items-center justify-center text-forest shrink-0">
                                <x-icon name="academic-cap" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-darkgreen">Kurikulum Merdeka</p>
                                <p class="text-[11px] text-muted-text">Integrasi AIK &amp; TIK</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sage/20 flex items-center justify-center text-forest shrink-0">
                                <x-icon name="trophy" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-darkgreen">Prestasi Gemilang</p>
                                <p class="text-[11px] text-muted-text">Kota, Provinsi &amp; Nasional</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: 5 cols — Foto Gedung Sekolah dengan Frame Eksklusif --}}
                <div class="lg:col-span-5 relative group">
                    {{-- Decorative backdrops --}}
                    <div class="absolute -inset-2 bg-gradient-to-tr from-forest/15 to-mint/30 rounded-3xl blur-lg opacity-70 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <div class="relative overflow-hidden rounded-2xl border-2 border-white shadow-xl bg-white aspect-[4/3]">
                        <img
                            src="{{ asset('images/school/gedung.jpg') }}"
                            alt="Gedung SMP Muhammadiyah 7 Medan"
                            class="w-full h-full object-cover object-[center_68%] transition-transform duration-700 group-hover:scale-105"
                            width="800"
                            height="600"
                            loading="eager"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-darkgreen/75 via-transparent to-transparent opacity-80 pointer-events-none"></div>

                        {{-- Floating Badge on Picture --}}
                        <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-white">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                                    <x-icon name="map-pin" class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-semibold leading-tight drop-shadow-xs">
                                    Jl. Pelita II No. 3-5, Medan
                                </span>
                            </div>
                            <span class="text-[11px] px-2.5 py-1 rounded-md bg-forest/90 backdrop-blur-xs font-semibold text-mint border border-mint/30">
                                Gedung Utama
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================================================================== --}}
    {{-- SEKSI 2 — PINTASAN PORTAL                                           --}}
    {{-- ================================================================== --}}
    <section class="bg-white border-t border-b border-border-soft py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                kicker="Informasi Terkini"
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
                kicker="Informasi Resmi"
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
                kicker="Fakta & Angka"
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

            @if (config('sekolah.statistik.is_dummy'))
                <p class="mt-8 text-center text-xs italic text-mint/80">
                    * Angka merupakan estimasi dummy dan akan diperbarui otomatis dari database akademik.
                </p>
            @endif

        </div>
    </section>

</x-layouts.public>
