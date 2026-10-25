<x-layouts.public title="Kepala Sekolah" description="Sambutan dan profil Kepala Sekolah SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Beranda',        'url' => route('home')],
        ['label' => 'Profil',         'url' => '#'],
        ['label' => 'Kepala Sekolah', 'url' => route('profile.principal')],
    ]" />

    <section class="py-14 md:py-20">
        <div class="container mx-auto px-4 max-w-6xl">

            <x-section-heading
                kicker="Pimpinan Sekolah"
                title="Kepala Sekolah"
                subtitle="Mengenal lebih dekat pimpinan SMP Muhammadiyah 7 Medan."
                align="left"
            />

            <div class="mt-10 grid grid-cols-1 lg:grid-cols-5 gap-10 items-start">

                {{-- Kolom Kiri: Foto --}}
                <div class="lg:col-span-2">
                    <div class="rounded-2xl overflow-hidden border border-border-soft shadow-md bg-white aspect-[4/3] flex items-end justify-center">
                        <img
                            src="{{ asset('images/principal/kepala-sekolah.png') }}"
                            alt="Foto {{ config('sekolah.kepala_sekolah.nama', 'Kepala Sekolah SMP Muhammadiyah 7 Medan') }}"
                            class="w-full h-full object-contain object-bottom"
                            width="400"
                            height="300"
                            loading="eager"
                        />
                    </div>
                    {{-- Kartu info singkat --}}
                    <div class="mt-5 bg-soft border border-border-soft rounded-2xl p-5 space-y-3">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Nama</span>
                            <p class="text-darkgreen font-bold text-lg mt-0.5">{{ config('sekolah.kepala_sekolah.nama', 'M. Reza Akbar, S.Pd.') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Jabatan</span>
                            <p class="text-olive mt-0.5">Kepala Sekolah SMP Muhammadiyah 7 Medan</p>
                        </div>
                        <div>
                            <x-badge variant="sage" size="sm">Periode 2024 – Sekarang</x-badge>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Sambutan --}}
                <div class="lg:col-span-3">
                    <div class="flex items-center gap-3 mb-6">
                        <x-icon name="academic-cap" class="w-7 h-7 text-forest flex-shrink-0" />
                        <h2 class="text-2xl font-bold text-darkgreen">Sambutan Kepala Sekolah</h2>
                    </div>

                    <div class="space-y-5 max-w-prose text-olive leading-relaxed">
                        <p>
                            Assalamu'alaikum Warahmatullahi Wabarakatuh. Puji syukur kita panjatkan ke hadirat Allah SWT
                            yang telah melimpahkan rahmat dan hidayah-Nya kepada kita semua. Shalawat dan salam semoga
                            senantiasa tercurahkan kepada junjungan kita Nabi Muhammad SAW beserta keluarga dan para
                            sahabatnya.
                        </p>
                        <p>
                            Selamat datang di Website Resmi SMP Muhammadiyah 7 Medan. Kami senantiasa berkomitmen
                            menghadirkan layanan pendidikan bermutu tinggi yang memadukan keunggulan akademik dengan
                            penanaman nilai-nilai Al-Islam dan Kemuhammadiyahan untuk membekali masa depan peserta
                            didik yang beriman, berilmu, dan berakhlak mulia.
                        </p>
                        <p>
                            SMP Muhammadiyah 7 Medan terus berupaya meningkatkan kualitas pendidikan melalui
                            program-program unggulan, pengembangan profesionalisme tenaga pendidik, dan penguatan
                            karakter peserta didik. Kami mengundang seluruh pemangku kepentingan — orang tua,
                            wali murid, alumni, dan masyarakat — untuk bersama-sama membangun sekolah yang lebih baik.
                        </p>
                        <p>
                            Dengan semangat <em>Fastabiqul Khairat</em>, mari kita berlomba-lomba dalam kebaikan
                            demi terwujudnya cita-cita besar: mencetak generasi penerus bangsa yang berkualitas,
                            berakhlak mulia, dan siap menghadapi tantangan zaman. Wassalamu'alaikum Warahmatullahi
                            Wabarakatuh.
                        </p>
                    </div>

                    {{-- Tanda tangan area --}}
                    <div class="mt-8 border-t border-border-soft pt-6">
                        <p class="text-sm text-muted-text">Medan, {{ date('Y') }}</p>
                        <p class="font-bold text-darkgreen mt-1">{{ config('sekolah.kepala_sekolah.nama', 'M. Reza Akbar, S.Pd.') }}</p>
                        <p class="text-sm text-olive">Kepala Sekolah SMP Muhammadiyah 7 Medan</p>
                    </div>
                </div>
            </div>

            {{-- Tombol Kembali --}}
            <div class="mt-12 flex">
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
    </section>

</x-layouts.public>
