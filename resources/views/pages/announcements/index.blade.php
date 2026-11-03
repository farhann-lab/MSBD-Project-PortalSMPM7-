<x-layouts.public title="Pengumuman Resmi Sekolah" description="Pengumuman dan edaran resmi dari SMP Muhammadiyah 7 Medan untuk siswa, orang tua, dan warga sekolah.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Pengumuman', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Header --}}
    <x-page-hero
        title="Pengumuman Sekolah"
        subtitle="Edaran dan pengumuman resmi dari pihak SMP Muhammadiyah 7 Medan untuk siswa, orang tua, dan wali murid."
        badge="Informasi Resmi"
        badgeIcon="megaphone"
        :image="asset('images/school/gerbang.png')"
        imageAlt="Gerbang SMP Muhammadiyah 7 Medan"
        imagePosition="center_10%"
    />

    {{-- Announcements --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(!empty($announcements))
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($announcements as $item)
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
            @else
                <x-empty-state
                    title="Belum Ada Pengumuman"
                    message="Pengumuman resmi sekolah akan ditampilkan di sini. Silakan pantau secara berkala."
                    icon="megaphone"
                />
            @endif
        </div>
    </section>

</x-layouts.public>
