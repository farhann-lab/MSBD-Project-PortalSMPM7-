<x-layouts.public title="Galeri Foto Sekolah" description="Dokumentasi foto kegiatan, fasilitas, dan momen berharga SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Galeri Dokumentasi', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Hero Header --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <x-section-heading
                kicker="Dokumentasi Visual"
                title="Galeri Foto Kegiatan Sekolah"
                subtitle="Kumpulan dokumentasi foto kegiatan akademik, keagamaan, olahraga, seni, dan fasilitas SMP Muhammadiyah 7 Medan."
                align="center"
            />
        </div>
    </section>

    {{-- Gallery Grid --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(!empty($gallery))
                {{-- Category Filter Tabs --}}
                @php
                    $categories = collect($gallery)->pluck('category')->unique()->sort()->prepend('Semua')->values();
                @endphp
                <div class="flex flex-wrap gap-2 mb-8" role="list" aria-label="Filter kategori galeri">
                    @foreach($categories as $cat)
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-medium
                            {{ $cat === 'Semua' ? 'bg-forest text-white' : 'bg-white border border-border-soft text-darkgreen hover:bg-soft' }}
                            cursor-default transition-colors">
                            {{ $cat }}
                        </span>
                    @endforeach
                </div>

                {{-- Photo Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($gallery as $foto)
                        <x-gallery-card
                            :title="$foto['title']"
                            :image="$foto['image'] ?? null"
                            :category="$foto['category'] ?? null"
                            :date="$foto['date'] ?? null"
                        />
                    @endforeach
                </div>

                {{-- Dummy Notice --}}
                <div class="mt-10 flex items-center justify-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium">
                        <x-icon name="sparkles" class="w-4 h-4 text-amber-500 shrink-0" />
                        <span>Galeri menampilkan foto ilustrasi sementara. Foto dokumentasi resmi akan segera ditambahkan.</span>
                    </div>
                </div>
            @else
                <x-empty-state
                    title="Galeri Masih Kosong"
                    message="Dokumentasi foto kegiatan sekolah akan segera ditambahkan. Pantau terus halaman ini untuk pembaruan."
                    icon="photo"
                />
            @endif
        </div>
    </section>

</x-layouts.public>
