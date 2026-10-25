<!DOCTYPE html>
<html lang="id" class="h-full bg-soft">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Design System Showcase — SMP Muhammadiyah 7 Medan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans antialiased text-darkgreen p-6 sm:p-10 space-y-12 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="border-b border-border-soft pb-6">
        <x-badge variant="forest" size="md">Milestone 1 — Design System Library</x-badge>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-darkgreen mt-3">
            Komponen Desain SIAKAD SMP Muhammadiyah 7 Medan
        </h1>
        <p class="text-muted-text mt-2 text-base">
            Showcase visual seluruh komponen Blade yang telah diimplementasikan pada Phase 1 sesuai spesifikasi DESIGN.md dan TASKS.md.
        </p>
    </div>

    {{-- Breadcrumb --}}
    <section class="space-y-4">
        <h2 class="text-lg font-bold text-forest border-b border-border-soft pb-2">1. Breadcrumb Component</h2>
        <x-breadcrumb :items="[
            ['label' => 'Profil Sekolah', 'url' => '/profil'],
            ['label' => 'Direktori', 'url' => '/direktori'],
            ['label' => 'Direktori Guru', 'url' => ''],
        ]" />
    </section>

    {{-- Section Heading --}}
    <section class="space-y-4">
        <h2 class="text-lg font-bold text-forest border-b border-border-soft pb-2">2. Section Heading Component</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-2xl border border-border-soft">
            <x-section-heading 
                kicker="Identitas Sekolah" 
                title="Pendidikan Karakter & Prestasi Unggul" 
                subtitle="Mewujudkan generasi berakhlak mulia, cerdas, dan mandiri berlandaskan nilai-nilai Islam."
            />
            <x-section-heading 
                kicker="Kategori Kesiswaan" 
                kickerVariant="mint"
                title="Ekstrakurikuler Pilihan" 
                subtitle="Wadah pengembangan bakat dan minat siswa."
                align="center"
            />
        </div>
    </section>

    {{-- Badges & Buttons --}}
    <section class="space-y-4">
        <h2 class="text-lg font-bold text-forest border-b border-border-soft pb-2">3. Badges & Buttons</h2>
        <div class="bg-white p-6 rounded-2xl border border-border-soft space-y-6">
            <div>
                <h3 class="text-sm font-semibold text-muted-text uppercase tracking-wider mb-3">Varian Badge</h3>
                <div class="flex flex-wrap gap-2.5 items-center">
                    <x-badge variant="forest">Forest</x-badge>
                    <x-badge variant="sage">Sage Green</x-badge>
                    <x-badge variant="mint">Mint Green</x-badge>
                    <x-badge variant="teal">Teal Green</x-badge>
                    <x-badge variant="darkgreen">Dark Green</x-badge>
                    <x-badge variant="soft">Soft Background</x-badge>
                    <x-badge variant="outline">Outline</x-badge>
                    <x-badge variant="warning" dot>Warning Dot</x-badge>
                    <x-badge variant="dummy">Data Contoh (PRD KSW-3)</x-badge>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-muted-text uppercase tracking-wider mb-3">Varian Tombol (Touch Target Min 44px)</h3>
                <div class="flex flex-wrap gap-3 items-center">
                    <x-button variant="primary" icon="academic-cap">Primary Forest</x-button>
                    <x-button variant="secondary" icon="arrow-right" iconPosition="right">Secondary Outline</x-button>
                    <x-button variant="outline" icon="search">Outline Soft</x-button>
                    <x-button variant="mint">Mint Action</x-button>
                    <x-button variant="ghost">Ghost Button</x-button>
                    <x-button variant="primary" size="sm">Small</x-button>
                    <x-button variant="primary" size="lg">Large CTA</x-button>
                </div>
            </div>
        </div>
    </section>

    {{-- Content Cards --}}
    <section class="space-y-4">
        <h2 class="text-lg font-bold text-forest border-b border-border-soft pb-2">4. Content Cards (News, Announcement, Portal)</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-news-card 
                title="SMP Muhammadiyah 7 Medan Juara 1 Turnamen Futsal Pelajar Kota Medan"
                slug="smp-muhammadiyah-7-medan-juara-1-turnamen-futsal"
                date="24 Oktober 2026"
                category="Prestasi"
                summary="Tim futsal SMP Muhammadiyah 7 Medan berhasil menorehkan prestasi gemilang dengan meraih juara pertama dalam ajang kejuaraan antar-pelajar tingkat kota."
            />

            <x-announcement-card 
                title="Pelaksanaan Penilaian Akhir Semester (PAS) Ganjil Tahun Ajaran 2026/2027"
                slug="pelaksanaan-pas-ganjil-2026"
                date="25 Oktober 2026"
                summary="Diberitahukan kepada seluruh siswa dan orang tua murid bahwa PAS akan dilaksanakan mulai tanggal 1 Desember 2026."
                :important="true"
            />

            <x-portal-card 
                title="Portal CBT Online"
                description="Layanan ujian berbasis komputer (Computer Based Test) untuk evaluasi belajar siswa SMP Muhammadiyah 7."
                href="/cbt"
                icon="computer-desktop"
                badge="Sistem Ujian"
            />
        </div>
    </section>

    {{-- Directory Cards (DIR-8 Compliant) --}}
    <section class="space-y-4">
        <div class="flex items-center justify-between border-b border-border-soft pb-2">
            <h2 class="text-lg font-bold text-forest">5. Directory Cards (Strict DIR-8 Privacy: Tanpa NIP/NIS/Kontak Pribadi/Foto)</h2>
            <x-badge variant="sage" size="sm">DIR-8 Approved</x-badge>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-darkgreen">Kartu Guru (teacher-card)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-teacher-card 
                        name="M. Reza Akbar, S.Pd."
                        mapel="Pendidikan Agama Islam"
                        jabatan="Kepala Sekolah"
                    />
                    <x-teacher-card 
                        name="Dra. Hj. Siti Rahmah, M.Pd."
                        mapel="Bahasa Indonesia"
                        jabatan="Wakil Kepala Sekolah"
                    />
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold text-darkgreen">Kartu Siswa (student-card)</h3>
                <div class="space-y-3">
                    <x-student-card 
                        name="Ahmad Fauzan Lubis"
                        kelas="Kelas 7A"
                    />
                    <x-student-card 
                        name="Nurul Aisyah Siregar"
                        kelas="Kelas 8B"
                    />
                    <x-student-card 
                        name="Muhammad Rizky Pratama"
                        kelas="Kelas 9C"
                    />
                </div>
            </div>
        </div>
    </section>

    {{-- Form Controls & Pagination & Empty State --}}
    <section class="space-y-4">
        <h2 class="text-lg font-bold text-forest border-b border-border-soft pb-2">6. Search, Filter, Pagination, & Empty State</h2>
        <div class="bg-white p-6 rounded-2xl border border-border-soft space-y-6">
            {{-- Search & Filter Toolbar --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <x-search-input name="q" placeholder="Cari nama guru atau mata pelajaran..." />
                <x-filter-select 
                    name="mapel" 
                    placeholder="Semua Mata Pelajaran" 
                    :options="[
                        'pai' => 'Pendidikan Agama Islam',
                        'bindo' => 'Bahasa Indonesia',
                        'bing' => 'Bahasa Inggris',
                        'mtk' => 'Matematika',
                        'ipa' => 'Ilmu Pengetahuan Alam'
                    ]" 
                />
                <x-button variant="primary" icon="search">Cari</x-button>
            </div>

            {{-- Pagination Demo --}}
            <div class="border-t border-border-soft pt-4">
                <h3 class="text-xs font-semibold text-muted-text uppercase tracking-wider mb-2">Pagination Component</h3>
                <x-pagination :currentPage="2" :lastPage="8" baseUrl="/components-preview" />
            </div>

            {{-- Empty State Demo --}}
            <div class="border-t border-border-soft pt-4">
                <h3 class="text-xs font-semibold text-muted-text uppercase tracking-wider mb-2">Empty State Component</h3>
                <x-empty-state 
                    title="Tidak ada data siswa ditemukan" 
                    message="Coba periksa kembali ejaan kata kunci pencarian Anda atau ubah filter kelas."
                    resetUrl="/components-preview"
                />
            </div>
        </div>
    </section>

    {{-- Stat Cards, Gallery Cards, Contact Items --}}
    <section class="space-y-4">
        <h2 class="text-lg font-bold text-forest border-b border-border-soft pb-2">7. Stat Cards, Gallery, & Contact Items</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card value="350+" label="Siswa Aktif" icon="user-group" description="Tahun Ajaran 2026/2027" />
            <x-stat-card value="28" label="Tenaga Pendidik & Staf" icon="academic-cap" description="Guru Berpengalaman" />
            <x-stat-card value="12" label="Ruang Kelas & Fasilitas" icon="building-office" description="Fasilitas Standar Nasional" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
            <x-gallery-card 
                title="Kegiatan Upacara Bendera dan Milad Muhammadiyah"
                category="Kegiatan Sekolah"
                date="18 November 2026"
            />
            <x-gallery-card 
                title="Praktikum Laboratorium IPA Siswa Kelas 8"
                category="Akademik"
                date="12 Oktober 2026"
            />
            <x-gallery-card 
                title="Latihan Rutin Tapak Suci Putera Muhammadiyah"
                category="Ekstrakurikuler"
                date="05 Oktober 2026"
            />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
            <x-contact-item 
                icon="map-pin" 
                label="Alamat Sekolah" 
                value="Jl. Pelita II No. 3-5, Medan Perjuangan, Kota Medan" 
            />
            <x-contact-item 
                icon="phone" 
                label="Telepon Resmi" 
                value="(061) 6621557" 
                href="tel:0616621557" 
            />
            <x-contact-item 
                icon="envelope" 
                label="Email Resmi" 
                value="smpm7medan@gmail.com" 
                href="mailto:smpm7medan@gmail.com" 
            />
        </div>
    </section>

    {{-- Footer Info --}}
    <div class="text-center text-xs text-muted-text border-t border-border-soft pt-6 pb-10">
        &copy; {{ date('Y') }} SMP Muhammadiyah 7 Medan — Phase 1 Design System Library Complete.
    </div>

</body>
</html>
