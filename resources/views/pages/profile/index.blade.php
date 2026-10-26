<x-layouts.public title="Tentang Sekolah" description="Profil lengkap SMP Muhammadiyah 7 Medan — visi, misi, identitas resmi, dan informasi kontak.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => route('home')],
                ['label' => 'Profil',  'url' => ''],
                ['label' => 'Tentang Sekolah', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- ===================== HERO HEADER ===================== --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <x-badge variant="forest" size="md">Berdiri Sejak 1979</x-badge>
                <x-badge variant="sage" size="md">NPSN 10257325</x-badge>
                <x-badge variant="mint" size="md">Terakreditasi A</x-badge>
            </div>
            <x-section-heading
                kicker="Profil Sekolah"
                title="Tentang SMP Muhammadiyah 7 Medan"
                subtitle="Mengenal lebih dekat sejarah, arah perjuangan, visi &amp; misi, identitas resmi lembaga, serta saluran informasi kontak kami."
                align="left"
            />
        </div>
    </section>

    {{-- ===================== VISI & MISI (SEJAJAR 2 KOLOM PADAT & RAPI) ===================== --}}
    <section class="py-10 sm:py-14 bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

                {{-- Kolom Kiri: VISI SEKOLAH (lg: 5 cols) --}}
                <div class="lg:col-span-5 flex flex-col justify-between bg-gradient-to-br from-soft to-white rounded-3xl border border-border-soft p-6 sm:p-8 shadow-xs">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-5">
                            <x-badge variant="forest" size="md">Visi Sekolah</x-badge>
                            <div class="w-10 h-10 rounded-xl bg-forest/10 border border-forest/15 flex items-center justify-center text-forest shrink-0">
                                <x-icon name="sparkles" class="w-5 h-5" />
                            </div>
                        </div>

                        <h3 class="text-2xl font-extrabold text-darkgreen tracking-tight leading-snug mb-4">
                            Visi Pendidikan Kami
                        </h3>

                        <blockquote class="relative pl-5 border-l-4 border-forest bg-white/80 p-5 rounded-r-2xl border border-border-soft/60 shadow-2xs">
                            <p class="text-darkgreen text-base sm:text-lg leading-relaxed font-semibold italic">
                                &ldquo;Terwujudnya peserta didik yang unggul dalam prestasi, santun dalam budi pekerti, mandiri dalam berpikir, dan peduli terhadap lingkungan berlandaskan nilai-nilai Al-Islam dan Kemuhammadiyahan.&rdquo;
                            </p>
                        </blockquote>

                        <div class="mt-6 space-y-3">
                            <div class="flex items-center gap-2.5 text-xs font-semibold text-olive">
                                <x-icon name="check-circle" class="w-4 h-4 text-forest shrink-0" />
                                <span>Berorientasi Prestasi Akademik &amp; Non-Akademik</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs font-semibold text-olive">
                                <x-icon name="check-circle" class="w-4 h-4 text-forest shrink-0" />
                                <span>Penguatan Karakter Al-Islam &amp; Kemuhammadiyahan</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs font-semibold text-olive">
                                <x-icon name="check-circle" class="w-4 h-4 text-forest shrink-0" />
                                <span>Kemandirian Berpikir &amp; Kepedulian Lingkungan</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-5 border-t border-border-soft/80 flex items-center justify-between text-xs text-muted-text">
                        <span>Falsafah Pendidikan Muhammadiyah</span>
                        <span class="font-bold text-forest">Unggul &bull; Berakhlak</span>
                    </div>
                </div>

                {{-- Kolom Kanan: MISI SEKOLAH (lg: 7 cols) --}}
                <div class="lg:col-span-7 flex flex-col justify-between bg-white rounded-3xl border border-border-soft p-6 sm:p-8 shadow-xs">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-5">
                            <x-badge variant="sage" size="md">Misi Sekolah</x-badge>
                            <div class="w-10 h-10 rounded-xl bg-mint/30 border border-mint/50 flex items-center justify-center text-forest shrink-0">
                                <x-icon name="academic-cap" class="w-5 h-5" />
                            </div>
                        </div>

                        <h3 class="text-2xl font-extrabold text-darkgreen tracking-tight leading-snug mb-4">
                            6 Pilar Misi Strategis
                        </h3>

                        @php
                            $misi = [
                                'Menyelenggarakan pembelajaran aktif, inovatif, kreatif, efektif, dan menyenangkan (PAIKEM).',
                                'Menumbuhkan semangat berprestasi secara intensif kepada seluruh warga sekolah.',
                                'Mengembangkan potensi, minat, dan bakat siswa melalui kegiatan ekstrakurikuler yang beragam.',
                                'Membentuk karakter dan kepribadian yang berakhlak mulia berlandaskan ajaran Islam.',
                                'Membangun kerjasama yang harmonis antara sekolah, orang tua, dan masyarakat.',
                                'Mewujudkan lingkungan sekolah yang bersih, sehat, indah, aman, dan kondusif.',
                            ];
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            @foreach ($misi as $index => $item)
                                <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-soft/60 border border-border-soft hover:bg-white hover:shadow-2xs hover:border-sage/50 transition-all duration-200">
                                    <div class="flex-shrink-0 w-7 h-7 bg-forest rounded-xl flex items-center justify-center text-white text-xs font-bold shadow-2xs">
                                        {{ $index + 1 }}
                                    </div>
                                    <p class="text-xs sm:text-sm text-darkgreen leading-relaxed font-medium">
                                        {{ $item }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 pt-5 border-t border-border-soft/80 flex items-center justify-between text-xs text-muted-text">
                        <span>Penerapan Terintegrasi di Pembelajaran Sehari-hari</span>
                        <span class="font-semibold text-olive">6 Indikator Capaian</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== IDENTITAS RESMI SEKOLAH ===================== --}}
    <section class="py-10 sm:py-14 bg-soft border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Data Resmi"
                title="Identitas Resmi Lembaga"
                subtitle="Legalitas dan data kelembagaan resmi SMP Muhammadiyah 7 Medan yang terdaftar di Kementerian."
                align="left"
            />

            <div class="mt-8 bg-white border border-border-soft rounded-3xl overflow-hidden shadow-xs">
                @php
                    $identitas = [
                        ['label' => 'Nama Sekolah Resmi', 'value' => config('sekolah.nama', 'SMP Muhammadiyah 7 Medan'), 'icon' => 'building-office'],
                        ['label' => 'NPSN',                'value' => '10257325',                                          'icon' => 'academic-cap'],
                        ['label' => 'Status Lembaga',      'value' => 'Swasta (Muhammadiyah)',                             'icon' => 'users'],
                        ['label' => 'Peringkat Akreditasi', 'value' => 'A (Amat Baik) — BAN-SM',                          'icon' => 'sparkles'],
                        ['label' => 'Tahun Berdiri',       'value' => '1979 (Lebih dari 4 dekade mengabdi)',               'icon' => 'calendar'],
                        ['label' => 'NSS / NIS Sekolah',   'value' => '204076001007 / 200070',                             'icon' => 'rectangle-group'],
                        ['label' => 'Penyelenggara',       'value' => 'Majelis Dikdasmen PCM Medan Perjuangan',            'icon' => 'user-group'],
                    ];
                @endphp

                <div class="divide-y divide-border-soft">
                    @foreach ($identitas as $row)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 sm:px-8 py-4.5 hover:bg-soft/40 transition-colors">
                            <div class="flex items-center gap-3 sm:w-72 shrink-0">
                                <div class="w-8 h-8 rounded-lg bg-mint/30 flex items-center justify-center text-forest shrink-0">
                                    <x-icon :name="$row['icon']" class="w-4 h-4" />
                                </div>
                                <span class="font-bold text-sm text-darkgreen">{{ $row['label'] }}</span>
                            </div>
                            <span class="text-sm font-semibold text-olive mt-1 sm:mt-0 sm:text-right">
                                {{ $row['value'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== INFORMASI KONTAK & PINTASAN LAIN ===================== --}}
    <section class="py-10 sm:py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Hubungi Kami"
                title="Informasi Kontak &amp; Pelayanan"
                subtitle="Saluran komunikasi resmi untuk keperluan administrasi, pendaftaran, dan informasi umum sekolah."
                align="left"
            />

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <x-contact-item
                    icon="map-pin"
                    label="Alamat Kampus"
                    :value="config('sekolah.kontak.alamat_lengkap', 'Jl. Pelita II No. 3-5, Medan')"
                />
                <x-contact-item
                    icon="phone"
                    label="Telepon Kantor"
                    :value="config('sekolah.kontak.telepon', '(061) 6621557')"
                    :href="'tel:' . config('sekolah.kontak.telepon_raw', '0616621557')"
                />
                <x-contact-item
                    icon="envelope"
                    label="Email Resmi"
                    :value="config('sekolah.kontak.email', 'smpm7medan@gmail.com')"
                    :href="'mailto:' . config('sekolah.kontak.email', 'smpm7medan@gmail.com')"
                />
                <x-contact-item
                    icon="clock"
                    label="Jam Layanan"
                    value="Senin – Sabtu: 07.30 – 14.30 WIB"
                />
            </div>

            {{-- Pintasan Navigasi Profil Terkait --}}
            <div class="mt-10 p-6 rounded-3xl bg-soft border border-border-soft flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h4 class="font-bold text-darkgreen text-base">Ingin Mengetahui Lebih Jauh?</h4>
                    <p class="text-xs text-muted-text mt-0.5">Lihat sambutan pimpinan sekolah atau jelajahi fasilitas penunjang belajar kami.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-button
                        href="{{ route('profile.principal') }}"
                        variant="primary"
                        size="md"
                        icon="arrow-right"
                        iconPosition="right"
                    >
                        Kepala Sekolah
                    </x-button>
                    <x-button
                        href="{{ route('profile.facilities') }}"
                        variant="outline"
                        size="md"
                    >
                        Sarana &amp; Prasarana
                    </x-button>
                </div>
            </div>
        </div>
    </section>

</x-layouts.public>
