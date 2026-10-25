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
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Informasi Resmi"
                title="Pengumuman Sekolah"
                subtitle="Edaran dan pengumuman resmi dari pihak SMP Muhammadiyah 7 Medan untuk siswa, orang tua, dan wali murid."
                align="left"
            />
        </div>
    </section>

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
