<x-layouts.public title="Struktur Organisasi" description="Bagan struktur organisasi SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => route('home')],
                ['label' => 'Profil', 'url' => route('profile.index')],
                ['label' => 'Struktur Organisasi', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Hero / Page Header --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Tata Kelola Lembaga"
                title="Struktur Organisasi"
                subtitle="Bagan susunan kepemimpinan, komite, dewan guru, dan tata usaha di lingkungan SMP Muhammadiyah 7 Medan."
                align="left"
            />

            {{-- Banner Data Dummy --}}
            <div class="mt-6 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl px-5 py-4 max-w-3xl">
                <x-icon name="sparkles" class="w-5 h-5 flex-shrink-0 mt-0.5 text-amber-600" />
                <div class="flex items-center gap-3 flex-wrap">
                    <x-badge variant="warning" size="sm">Data Contoh</x-badge>
                    <p class="text-xs sm:text-sm leading-relaxed text-amber-800">
                        Data nama pejabat di bawah ini merupakan data contoh demonstrasi portal dan akan diperbarui setelah data SK resmi diverifikasi pihak sekolah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ============================================================
                 BAGAN ORGANISASI — Desktop: bertingkat rapi
                 Mobile: kartu bergaris pembatas halus
                 ============================================================ --}}

            <div class="bg-white border border-border-soft rounded-3xl p-6 sm:p-10 shadow-xs">

                {{-- --- LEVEL 1: Kepala Sekolah --- --}}
                <div class="flex justify-center">
                    <div class="bg-forest text-white rounded-2xl px-8 py-5 text-center shadow-md w-full max-w-xs transition-transform hover:-translate-y-0.5">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mx-auto mb-2 text-mint">
                            <x-icon name="academic-cap" class="w-6 h-6" />
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-mint mb-1">Kepala Sekolah</p>
                        <p class="font-extrabold text-lg leading-snug">{{ config('sekolah.kepala_sekolah.nama', 'M. Reza Akbar, S.Pd.') }}</p>
                    </div>
                </div>

                {{-- Connector Level 1 → 2 --}}
                <div class="flex justify-center">
                    <div class="w-0.5 h-8 bg-forest/40"></div>
                </div>

                {{-- --- LEVEL 2: Wakil Kepsek & Ketua Komite --- --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 max-w-2xl mx-auto">
                    {{-- Wakil Kepala Sekolah --}}
                    <div class="bg-soft border border-border-soft rounded-2xl p-5 text-center transition-shadow hover:shadow-xs">
                        <div class="w-8 h-8 rounded-lg bg-mint/40 text-forest flex items-center justify-center mx-auto mb-2">
                            <x-icon name="user-group" class="w-4 h-4" />
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-text mb-1">Wakil Kepala Sekolah</p>
                        <p class="font-bold text-darkgreen text-base">[Nama Wakasek — Data Dummy]</p>
                        <p class="text-xs text-olive mt-1">Bidang Kurikulum &amp; Kesiswaan</p>
                    </div>

                    {{-- Komite Sekolah --}}
                    <div class="bg-soft border border-border-soft rounded-2xl p-5 text-center transition-shadow hover:shadow-xs">
                        <div class="w-8 h-8 rounded-lg bg-mint/40 text-forest flex items-center justify-center mx-auto mb-2">
                            <x-icon name="building-office" class="w-4 h-4" />
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-text mb-1">Komite Sekolah</p>
                        <p class="font-bold text-darkgreen text-base">[Nama Ketua — Data Dummy]</p>
                        <p class="text-xs text-olive mt-1">Perwakilan Orang Tua &amp; Tokoh</p>
                    </div>
                </div>

                {{-- Connector Level 2 → 3 --}}
                <div class="flex justify-center">
                    <div class="w-0.5 h-8 bg-forest/40"></div>
                </div>

                {{-- --- LEVEL 3: Tata Usaha, Guru, dan Koordinator --- --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 max-w-4xl mx-auto">
                    {{-- Tata Usaha --}}
                    <div class="bg-white border border-border-soft rounded-2xl p-5 text-center shadow-2xs hover:border-sage/60 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center mx-auto mb-2">
                            <x-icon name="clipboard-document-list" class="w-4 h-4" />
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-text mb-1">Kepala Tata Usaha</p>
                        <p class="font-bold text-darkgreen text-sm">[Nama KTU — Data Dummy]</p>
                        <p class="text-xs text-olive mt-0.5">Administrasi &amp; Arsip</p>
                    </div>

                    {{-- Dewan Guru --}}
                    <div class="bg-white border border-border-soft rounded-2xl p-5 text-center shadow-2xs hover:border-sage/60 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center mx-auto mb-2">
                            <x-icon name="book-open" class="w-4 h-4" />
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-text mb-1">Dewan Guru</p>
                        <p class="font-bold text-darkgreen text-sm">Tenaga Pendidik</p>
                        <p class="text-xs text-olive mt-0.5">Seluruh Guru Mata Pelajaran</p>
                    </div>

                    {{-- Koordinator Bidang --}}
                    <div class="bg-white border border-border-soft rounded-2xl p-5 text-center shadow-2xs hover:border-sage/60 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center mx-auto mb-2">
                            <x-icon name="sparkles" class="w-4 h-4" />
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-text mb-1">Koordinator Bidang</p>
                        <p class="font-bold text-darkgreen text-sm">Koordinator Terpadu</p>
                        <p class="text-xs text-olive mt-0.5">Sarpras, AIK, &amp; BK</p>
                    </div>
                </div>

                {{-- Koordinator Spesifik List --}}
                <div class="mt-8 pt-8 border-t border-border-soft">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-darkgreen mb-4 text-center sm:text-left">
                        Daftar Koordinator &amp; Penanggung Jawab Unit
                    </h4>
                    @php
                        $koordinators = [
                            ['jabatan' => 'Koordinator Al-Islam & Kemuhammadiyahan', 'nama' => '[Nama — Data Dummy]', 'bidang' => 'Pembinaan Keagamaan & Sholat Berjamaah'],
                            ['jabatan' => 'Koordinator Bimbingan Konseling (BK)',     'nama' => '[Nama — Data Dummy]', 'bidang' => 'Layanan Bimbingan Konseling Siswa'],
                            ['jabatan' => 'Koordinator Sarana & Prasarana',           'nama' => '[Nama — Data Dummy]', 'bidang' => 'Pemeliharaan Gedung & Fasilitas'],
                            ['jabatan' => 'Koordinator Perpustakaan',                 'nama' => '[Nama — Data Dummy]', 'bidang' => 'Pengelolaan Koleksi & Literasi'],
                            ['jabatan' => 'Koordinator Laboratorium Komputer',        'nama' => '[Nama — Data Dummy]', 'bidang' => 'Pengelolaan Jaringan & Lab TIK'],
                        ];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($koordinators as $k)
                            <div class="p-4 rounded-xl bg-soft/60 border border-border-soft flex flex-col justify-between">
                                <div>
                                    <span class="text-xs font-bold text-forest">{{ $k['jabatan'] }}</span>
                                    <p class="text-xs text-muted-text mt-0.5">{{ $k['bidang'] }}</p>
                                </div>
                                <p class="text-sm font-semibold text-darkgreen mt-2 pt-2 border-t border-border-soft/60">{{ $k['nama'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Tombol Kembali --}}
            <div class="mt-8 flex justify-between items-center flex-wrap gap-4">
                <x-button
                    href="{{ route('profile.index') }}"
                    variant="outline"
                    size="md"
                    icon="arrow-left"
                    iconPosition="left"
                >
                    Kembali ke Profil
                </x-button>

                <x-button
                    href="{{ route('profile.facilities') }}"
                    variant="primary"
                    size="md"
                    icon="arrow-right"
                    iconPosition="right"
                >
                    Lihat Sarana &amp; Prasarana
                </x-button>
            </div>

        </div>
    </section>

</x-layouts.public>
