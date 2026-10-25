<x-layouts.public title="Struktur Organisasi" description="Bagan struktur organisasi SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Beranda',              'url' => route('home')],
        ['label' => 'Profil',              'url' => '#'],
        ['label' => 'Struktur Organisasi', 'url' => route('profile.structure')],
    ]" />

    <section class="py-14 md:py-20">
        <div class="container mx-auto px-4 max-w-5xl">

            <x-section-heading
                kicker="Organisasi"
                title="Struktur Organisasi"
                subtitle="Susunan pejabat dan koordinator di lingkungan SMP Muhammadiyah 7 Medan."
                align="center"
            />

            {{-- Banner Data Dummy --}}
            <div class="mt-8 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-5 py-4">
                <x-icon name="sparkles" class="w-5 h-5 flex-shrink-0 mt-0.5 text-amber-500" />
                <div class="flex items-center gap-3 flex-wrap">
                    <x-badge variant="warning" size="sm">Data Contoh</x-badge>
                    <p class="text-sm leading-relaxed">
                        Data pejabat di bawah ini merupakan data contoh dan akan diperbarui setelah data resmi tersedia.
                    </p>
                </div>
            </div>

            {{-- ============================================================
                 BAGAN ORGANISASI — Desktop: vertikal bertingkat
                 Mobile: daftar kartu dengan garis kiri forest
                 ============================================================ --}}

            {{-- --- LEVEL 1: Kepala Sekolah --- --}}
            <div class="mt-12 hidden sm:flex justify-center">
                <div class="bg-forest text-white rounded-2xl px-8 py-5 text-center shadow-lg w-72">
                    <x-icon name="academic-cap" class="w-7 h-7 mx-auto mb-2 text-mint" />
                    <p class="text-xs font-semibold uppercase tracking-wider text-mint mb-1">Kepala Sekolah</p>
                    <p class="font-bold text-lg leading-snug">{{ config('sekolah.kepala_sekolah.nama', 'M. Reza Akbar, S.Pd.') }}</p>
                </div>
            </div>

            {{-- Connector Level 1 → 2 --}}
            <div class="hidden sm:flex justify-center">
                <div class="w-0.5 h-8 bg-forest"></div>
            </div>

            {{-- --- LEVEL 2: Wakil Kepsek & Ketua Komite --- --}}
            <div class="hidden sm:flex justify-center gap-16 relative">
                {{-- Garis horizontal penghubung --}}
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-80 h-0.5 bg-forest"></div>
                @php
                    $level2 = [
                        ['jabatan' => 'Wakil Kepala Sekolah', 'nama' => '[Nama Wakil Kepsek — Data Dummy]'],
                        ['jabatan' => 'Ketua Komite',         'nama' => '[Nama Ketua Komite — Data Dummy]'],
                    ];
                @endphp
                @foreach ($level2 as $item)
                    <div class="flex flex-col items-center">
                        <div class="w-0.5 h-8 bg-forest"></div>
                        <div class="bg-soft border-2 border-forest rounded-xl px-6 py-4 text-center w-52 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-forest mb-1">{{ $item['jabatan'] }}</p>
                            {{-- Data dummy — akan diganti dengan data resmi --}}
                            <p class="font-semibold text-darkgreen text-sm leading-snug">{{ $item['nama'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Connector Level 2 → 3 --}}
            <div class="hidden sm:flex justify-center">
                <div class="w-0.5 h-8 bg-border-soft"></div>
            </div>

            {{-- --- LEVEL 3: Koordinator --- --}}
            @php
                $level3 = [
                    ['jabatan' => 'Koordinator Kurikulum',          'nama' => '[Nama — Data Dummy]'],
                    ['jabatan' => 'Koordinator Kesiswaan',          'nama' => '[Nama — Data Dummy]'],
                    ['jabatan' => 'Koordinator Sarana & Prasarana', 'nama' => '[Nama — Data Dummy]'],
                    ['jabatan' => 'Kepala Tata Usaha',              'nama' => '[Nama — Data Dummy]'],
                ];
            @endphp
            <div class="hidden sm:grid grid-cols-4 gap-4 relative">
                {{-- Garis horizontal penghubung --}}
                <div class="absolute top-0 left-[12.5%] right-[12.5%] h-0.5 bg-border-soft"></div>
                @foreach ($level3 as $item)
                    <div class="flex flex-col items-center">
                        <div class="w-0.5 h-8 bg-border-soft"></div>
                        <div class="bg-white border border-border-soft rounded-xl px-4 py-4 text-center w-full shadow-sm">
                            <p class="text-xs font-semibold text-forest mb-1">{{ $item['jabatan'] }}</p>
                            {{-- Data dummy --}}
                            <p class="text-darkgreen text-xs leading-snug font-medium">{{ $item['nama'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Connector Level 3 → 4 --}}
            <div class="hidden sm:flex justify-center">
                <div class="w-0.5 h-8 bg-border-soft"></div>
            </div>

            {{-- --- LEVEL 4: Label saja --- --}}
            <div class="hidden sm:flex justify-center gap-6">
                @foreach (['Dewan Guru', 'Staf TU', 'Wali Kelas'] as $label)
                    <div class="flex flex-col items-center">
                        <div class="w-0.5 h-4 bg-border-soft"></div>
                        <span class="bg-mint/30 text-darkgreen text-xs font-semibold px-4 py-2 rounded-full border border-border-soft">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- ============================================================
                 TAMPILAN MOBILE — daftar kartu dengan garis kiri forest
                 ============================================================ --}}
            <div class="sm:hidden mt-8 space-y-4">
                {{-- Level 1 --}}
                <div class="border-l-4 border-forest bg-forest/10 rounded-r-xl px-5 py-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-forest">Kepala Sekolah</p>
                    <p class="font-bold text-darkgreen mt-1">{{ config('sekolah.kepala_sekolah.nama', 'M. Reza Akbar, S.Pd.') }}</p>
                </div>

                {{-- Level 2 --}}
                @foreach ($level2 as $item)
                    <div class="border-l-4 border-sage bg-soft rounded-r-xl px-5 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-sage">{{ $item['jabatan'] }}</p>
                        {{-- Data dummy --}}
                        <p class="font-semibold text-darkgreen mt-1 text-sm">{{ $item['nama'] }}</p>
                    </div>
                @endforeach

                {{-- Level 3 --}}
                @foreach ($level3 as $item)
                    <div class="border-l-4 border-border-soft bg-white rounded-r-xl px-5 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-olive">{{ $item['jabatan'] }}</p>
                        {{-- Data dummy --}}
                        <p class="text-darkgreen mt-1 text-sm">{{ $item['nama'] }}</p>
                    </div>
                @endforeach

                {{-- Level 4 --}}
                @foreach (['Dewan Guru', 'Staf TU', 'Wali Kelas'] as $label)
                    <div class="border-l-4 border-mint bg-mint/10 rounded-r-xl px-5 py-3">
                        <p class="text-sm font-semibold text-darkgreen">{{ $label }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Kembali --}}
            <div class="mt-14 flex">
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
