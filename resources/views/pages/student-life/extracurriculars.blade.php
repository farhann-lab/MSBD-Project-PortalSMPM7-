<x-layouts.public title="Ekstrakurikuler" description="Kegiatan ekstrakurikuler SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Beranda',         'url' => url('/')],
        ['label' => 'Kesiswaan',       'url' => '#'],
        ['label' => 'Ekstrakurikuler', 'url' => url('/kesiswaan/ekstrakurikuler')],
    ]" />

    {{-- Section Heading --}}
    <x-section-heading
        kicker="Kesiswaan"
        title="Ekstrakurikuler"
        subtitle="Berbagai pilihan kegiatan pengembangan bakat dan minat siswa SMP Muhammadiyah 7 Medan"
        align="left"
    />

    {{-- Extracurricular Grid --}}
    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach($extracurriculars as $ekskul)
            <div class="group overflow-hidden rounded-2xl border border-border-soft bg-white transition-shadow hover:shadow-md">

                {{-- Image Area --}}
                @if(!empty($ekskul['image']))
                    <div class="aspect-video w-full overflow-hidden">
                        <img
                            src="{{ $ekskul['image'] }}"
                            alt="{{ $ekskul['name'] }}"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            loading="lazy"
                        />
                    </div>
                @else
                    {{-- Placeholder --}}
                    <div class="aspect-video w-full overflow-hidden">
                        <x-image-placeholder class="h-full w-full" />
                    </div>
                @endif

                {{-- Card Body --}}
                <div class="p-5">
                    {{-- Category Badge --}}
                    @if(!empty($ekskul['kategori']))
                        <div class="mb-2">
                            <x-badge variant="sage" size="sm">{{ $ekskul['kategori'] }}</x-badge>
                        </div>
                    @endif

                    {{-- Name --}}
                    <h3 class="font-bold text-darkgreen">{{ $ekskul['name'] }}</h3>

                    {{-- Description --}}
                    <p class="mt-1 line-clamp-2 text-sm text-muted-text">{{ $ekskul['description'] }}</p>

                    {{-- Schedule --}}
                    @if(!empty($ekskul['jadwal']))
                        <div class="mt-3 flex items-center gap-1.5 border-t border-border-soft pt-3 text-xs text-forest">
                            <x-icon name="clock" class="h-3.5 w-3.5 shrink-0" />
                            {{ $ekskul['jadwal'] }}
                        </div>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

</x-layouts.public>
