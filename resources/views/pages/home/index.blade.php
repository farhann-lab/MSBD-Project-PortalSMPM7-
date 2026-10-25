<x-layouts.public title="Beranda" description="Portal resmi SMP Muhammadiyah 7 Medan — Membangun Generasi Unggul Berkarakter Islami.">

    {{-- ================================================================== --}}
    {{-- SEKSI 1 — HERO                                                      --}}
    {{-- ================================================================== --}}
    <section class="bg-gradient-to-b from-white to-soft py-16 sm:py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Kolom Kiri --}}
                <div class="flex flex-col gap-6">
                    <div>
                        <x-badge variant="sage" size="md">SMP Muhammadiyah 7 Medan</x-badge>
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-darkgreen">
                        Membangun Generasi Unggul Berkarakter Islami
                    </h1>

                    <p class="text-base text-muted-text leading-relaxed max-w-lg">
                        SMP Muhammadiyah 7 Medan hadir sebagai lembaga pendidikan Islam yang berkomitmen
                        mencetak generasi berilmu, berakhlak mulia, dan berdaya saing tinggi di era modern.
                        Bergabunglah bersama kami dalam perjalanan membangun masa depan yang gemilang.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <x-button href="/profil" variant="primary" size="md">
                            Tentang Sekolah
                        </x-button>
                        <x-button href="/berita" variant="secondary" size="md">
                            Lihat Berita
                        </x-button>
                    </div>
                </div>

                {{-- Kolom Kanan — Gambar Placeholder --}}
                <div>
                    <x-image-placeholder
                        aspect="video"
                        label="Gedung SMP Muhammadiyah 7 Medan"
                        icon="building-office"
                        class="rounded-2xl"
                    />
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
    <section class="bg-soft py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Foto Portrait Placeholder --}}
                <div class="flex justify-center lg:justify-start">
                    <div class="w-full max-w-sm">
                        <x-image-placeholder
                            aspect="4/3"
                            label="Foto Kepala Sekolah"
                            icon="user-circle"
                            class="rounded-2xl"
                        />
                    </div>
                </div>

                {{-- Sambutan --}}
                <div class="flex flex-col gap-6">
                    <div>
                        <x-badge variant="forest" size="md">Sambutan Kepala Sekolah</x-badge>
                    </div>

                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-darkgreen">
                            {{ config('sekolah.kepala_sekolah.nama') }}
                        </h2>
                        <p class="text-muted-text text-sm mt-1">Kepala Sekolah SMP Muhammadiyah 7 Medan</p>
                    </div>

                    <blockquote class="border-l-4 border-forest pl-4 italic text-darkgreen leading-relaxed text-base">
                        "{{ config('sekolah.kepala_sekolah.sambutan_singkat') }}"
                    </blockquote>

                    <div>
                        <x-button href="/profil/kepala-sekolah" variant="outline" size="md">
                            Baca Sambutan Lengkap
                        </x-button>
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
    <section class="bg-forest py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-section-heading
                kicker="Fakta & Angka"
                title="Statistik Sekolah"
                subtitle="Gambaran umum kondisi dan kapasitas SMP Muhammadiyah 7 Medan saat ini."
                align="center"
                class="text-white [&_*]:text-white"
            />

            <div class="mt-10 grid grid-cols-2 lg:grid-cols-4 gap-6">
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
                    * Angka merupakan estimasi dummy dan akan diperbarui dari database.
                </p>
            @endif

        </div>
    </section>

</x-layouts.public>
