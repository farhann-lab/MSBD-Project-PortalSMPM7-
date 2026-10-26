<x-layouts.public title="Kepala Sekolah" description="Sambutan dan profil Kepala Sekolah SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => route('home')],
                ['label' => 'Profil', 'url' => route('profile.index')],
                ['label' => 'Kepala Sekolah', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Hero / Page Header --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Pimpinan Sekolah"
                title="Kepala Sekolah"
                subtitle="Mengenal lebih dekat pimpinan dan sambutan resmi Kepala Sekolah SMP Muhammadiyah 7 Medan."
                align="left"
            />
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

                {{-- Kolom Kiri: Foto & Profil Singkat (4 cols) --}}
                <div class="lg:col-span-4 space-y-5">
                    <div class="rounded-3xl overflow-hidden border border-border-soft shadow-sm bg-white aspect-[4/3] flex items-end justify-center p-3">
                        <img
                            src="{{ asset('images/principal/kepala-sekolah.png') }}"
                            alt="Foto {{ config('sekolah.kepala_sekolah.nama', 'Kepala Sekolah SMP Muhammadiyah 7 Medan') }}"
                            class="w-full h-full object-contain object-bottom"
                            width="400"
                            height="300"
                            loading="eager"
                        />
                    </div>

                    {{-- Kartu Info Singkat Pejabat --}}
                    <div class="bg-white border border-border-soft rounded-3xl p-6 shadow-xs space-y-4">
                        <div class="border-b border-border-soft pb-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Nama Lengkap</span>
                            <p class="text-darkgreen font-extrabold text-lg mt-0.5">{{ config('sekolah.kepala_sekolah.nama', 'M. Reza Akbar, S.Pd.') }}</p>
                        </div>
                        <div class="border-b border-border-soft pb-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Jabatan Struktural</span>
                            <p class="text-olive font-medium mt-0.5">Kepala Sekolah SMP Muhammadiyah 7 Medan</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-muted-text">Masa Amanah:</span>
                            <x-badge variant="sage" size="sm">Periode 2024 – Sekarang</x-badge>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Sambutan Resmi (8 cols) --}}
                <div class="lg:col-span-8 bg-white border border-border-soft rounded-3xl p-6 sm:p-10 shadow-xs">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border-soft">
                        <div class="w-10 h-10 rounded-xl bg-forest/10 flex items-center justify-center text-forest shrink-0">
                            <x-icon name="academic-cap" class="w-6 h-6" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-darkgreen">Sambutan Kepala Sekolah</h2>
                            <p class="text-xs text-muted-text mt-0.5">Pesan dan harapan pimpinan bagi civitas akademika</p>
                        </div>
                    </div>

                    <div class="space-y-5 text-darkgreen/90 leading-relaxed text-base sm:text-lg">
                        <p>
                            <em>Assalamu'alaikum Warahmatullahi Wabarakatuh.</em> Puji dan syukur senantiasa kita panjatkan ke hadirat Allah SWT yang telah melimpahkan rahmat, hidayah, dan inayah-Nya kepada kita semua. Shalawat beriring salam semoga senantiasa tercurahkan kepada junjungan alam Nabi Muhammad SAW beserta keluarga dan para sahabatnya.
                        </p>
                        <p>
                            Selamat datang di Portal Informasi Resmi SIAKAD SMP Muhammadiyah 7 Medan. Lembaga pendidikan kami berdiri teguh dengan komitmen menghadirkan layanan pendidikan bermutu tinggi yang memadukan keunggulan akademik, penguasaan sains dan teknologi, serta penanaman adab dan nilai-nilai luhur Al-Islam dan Kemuhammadiyahan.
                        </p>
                        <blockquote class="pl-5 border-l-4 border-forest bg-soft/60 p-4 rounded-r-2xl italic text-darkgreen text-base">
                            &ldquo;{{ config('sekolah.kepala_sekolah.sambutan_singkat') }}&rdquo;
                        </blockquote>
                        <p>
                            Kami terus berikhtiar menciptakan atmosfer belajar yang ramah, kreatif, dan inspiratif. Melalui kurikulum terintegrasi, kegiatan ekstrakurikuler yang beragam, serta kolaborasi yang erat bersama orang tua dan masyarakat, kami yakin tunas-tunas muda ini akan tumbuh menjadi pemimpin berakhlak mulia di masa depan.
                        </p>
                        <p>
                            Mari bersama-sama melangkah dengan semangat <em>Fastabiqul Khairat</em> — berlomba-lomba dalam kebaikan. <em>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</em>
                        </p>
                    </div>

                    {{-- Tanda tangan area --}}
                    <div class="mt-10 border-t border-border-soft pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-xs text-muted-text">Medan, Sumatera Utara</p>
                            <p class="font-extrabold text-darkgreen text-base mt-0.5">{{ config('sekolah.kepala_sekolah.nama', 'M. Reza Akbar, S.Pd.') }}</p>
                            <p class="text-xs text-olive font-medium">Kepala Sekolah SMP Muhammadiyah 7 Medan</p>
                        </div>

                        <x-button
                            href="{{ route('profile.index') }}"
                            variant="outline"
                            size="md"
                            icon="arrow-left"
                            iconPosition="left"
                        >
                            Kembali ke Profil
                        </x-button>
                    </div>
                </div>

            </div>

        </div>
    </section>

</x-layouts.public>
