<x-layouts.public title="Berita & Informasi" description="Kumpulan berita, liputan kegiatan, dan informasi terkini dari SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Berita', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Header --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Kabar Sekolah"
                title="Berita & Informasi Terkini"
                subtitle="Liputan kegiatan akademik, prestasi, dan momen berkesan dari keluarga besar SMP Muhammadiyah 7 Medan."
                align="left"
            />
        </div>
    </section>

    {{-- News Grid --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(!empty($news))
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($news as $item)
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

                {{-- Pagination placeholder (Phase 4 akan diaktifkan dengan data nyata) --}}
                @if(count($news) >= 6)
                    <div class="mt-10 flex items-center justify-center gap-2">
                        <x-button variant="outline" size="md" icon="arrow-left" class="opacity-40 pointer-events-none">
                            Sebelumnya
                        </x-button>
                        <span class="px-4 py-2 rounded-xl border border-border-soft bg-white text-sm font-medium text-forest">
                            Halaman 1
                        </span>
                        <x-button variant="outline" size="md" icon="arrow-right" iconPosition="right" class="opacity-40 pointer-events-none">
                            Berikutnya
                        </x-button>
                    </div>
                @endif
            @else
                <x-empty-state
                    title="Belum Ada Berita"
                    message="Berita dan informasi terkini akan segera dipublikasikan. Kunjungi kembali halaman ini secara berkala."
                    icon="newspaper"
                />
            @endif
        </div>
    </section>

</x-layouts.public>
