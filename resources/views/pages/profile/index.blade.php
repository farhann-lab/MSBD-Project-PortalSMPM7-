<x-layouts.public title="Tentang Sekolah" description="Profil lengkap SMP Muhammadiyah 7 Medan — visi, misi, identitas sekolah, dan informasi kontak.">

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Profil',  'url' => '#'],
        ['label' => 'Tentang Sekolah', 'url' => route('profile.index')],
    ]" />

    {{-- ===================== HERO ===================== --}}
    <section class="bg-soft py-16 md:py-20">
        <div class="container mx-auto px-4 max-w-4xl text-center">
            <div class="flex flex-wrap justify-center gap-2 mb-6">
                <x-badge variant="forest" size="md">Berdiri Sejak 1979</x-badge>
                <x-badge variant="sage"   size="md">NPSN 10257325</x-badge>
            </div>
            <h1 class="text-3xl md:text-5xl font-bold text-darkgreen leading-tight mb-4">
                Tentang SMP Muhammadiyah 7 Medan
            </h1>
            <p class="text-lg text-muted-text max-w-2xl mx-auto leading-relaxed">
                Mengenal lebih dekat {{ config('sekolah.nama') }} — sejarah, visi &amp; misi,
                identitas resmi, dan informasi kontak yang kami sajikan untuk Anda.
            </p>
        </div>
    </section>

    {{-- ===================== VISI ===================== --}}
    <section class="py-14 md:py-20">
        <div class="container mx-auto px-4 max-w-4xl">
            <x-section-heading
                kicker="Visi"
                title="Visi Sekolah"
                align="left"
            />
            <div class="mt-8 bg-white border border-border-soft rounded-2xl p-8 shadow-sm flex gap-5">
                <div class="flex-shrink-0 mt-1">
                    <x-icon name="sparkles" class="w-8 h-8 text-forest" />
                </div>
                <p class="text-darkgreen text-lg leading-relaxed font-medium">
                    "Terwujudnya peserta didik yang unggul dalam prestasi, santun dalam budi pekerti,
                    mandiri dalam berpikir, dan peduli terhadap lingkungan berlandaskan nilai-nilai
                    Al-Islam dan Kemuhammadiyahan."
                </p>
            </div>
        </div>
    </section>

    {{-- ===================== MISI ===================== --}}
    <section class="bg-soft py-14 md:py-20">
        <div class="container mx-auto px-4 max-w-5xl">
            <x-section-heading
                kicker="Misi"
                title="Misi Sekolah"
                align="left"
            />
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-5">
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
                @foreach ($misi as $index => $item)
                    <div class="flex items-start gap-4 bg-white border border-border-soft rounded-xl p-5 shadow-sm">
                        <div class="flex-shrink-0 w-8 h-8 bg-forest rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ $index + 1 }}
                        </div>
                        <p class="text-olive leading-relaxed">{{ $item }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== IDENTITAS SEKOLAH ===================== --}}
    <section class="py-14 md:py-20">
        <div class="container mx-auto px-4 max-w-4xl">
            <x-section-heading
                kicker="Data Resmi"
                title="Identitas Sekolah"
                align="left"
            />
            <div class="mt-8 bg-white border border-border-soft rounded-2xl overflow-hidden shadow-sm">
                @php
                    $identitas = [
                        ['label' => 'Nama Sekolah',      'value' => config('sekolah.nama', 'SMP Muhammadiyah 7 Medan')],
                        ['label' => 'NPSN',              'value' => '10257325'],
                        ['label' => 'Status Sekolah',    'value' => 'Swasta'],
                        ['label' => 'Akreditasi',        'value' => 'A (Sangat Baik)'],
                        ['label' => 'Tahun Berdiri',     'value' => '1979'],
                        ['label' => 'NSS / NIS Sekolah', 'value' => '204076001007 / 200070'],
                    ];
                @endphp
                @foreach ($identitas as $row)
                    <div class="flex flex-col sm:flex-row sm:items-center px-6 py-4 border-b border-border-soft last:border-0 odd:bg-soft/40">
                        <span class="w-full sm:w-52 font-semibold text-darkgreen shrink-0">{{ $row['label'] }}</span>
                        <span class="text-olive mt-1 sm:mt-0">{{ $row['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== KONTAK ===================== --}}
    <section class="bg-soft py-14 md:py-20">
        <div class="container mx-auto px-4 max-w-5xl">
            <x-section-heading
                kicker="Hubungi Kami"
                title="Informasi Kontak"
                align="left"
            />
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <x-contact-item
                    icon="map-pin"
                    label="Alamat"
                    :value="config('sekolah.kontak.alamat_lengkap', 'Jl. Pertahanan No. 1, Medan')"
                />
                <x-contact-item
                    icon="phone"
                    label="Telepon"
                    :value="config('sekolah.kontak.telepon', '(061) 6621557')"
                    :href="'tel:' . config('sekolah.kontak.telepon_raw', '0616621557')"
                />
                <x-contact-item
                    icon="envelope"
                    label="Email"
                    :value="config('sekolah.kontak.email', 'smpm7medan@gmail.com')"
                    :href="'mailto:' . config('sekolah.kontak.email', 'smpm7medan@gmail.com')"
                />
                <x-contact-item
                    icon="clock"
                    label="Jam Layanan"
                    value="Senin – Jumat: 07.00 – 15.00 WIB"
                />
            </div>

            <div class="mt-10 flex justify-start">
                <x-button
                    href="{{ route('profile.principal') }}"
                    variant="primary"
                    size="md"
                    icon="arrow-right"
                    iconPosition="right"
                >
                    Lihat Kepala Sekolah
                </x-button>
            </div>
        </div>
    </section>

</x-layouts.public>
