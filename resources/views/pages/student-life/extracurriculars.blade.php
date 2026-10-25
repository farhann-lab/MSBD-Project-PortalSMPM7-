<x-layouts.public title="Ekstrakurikuler" description="Kegiatan ekstrakurikuler SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Kesiswaan', 'url' => ''],
                ['label' => 'Ekstrakurikuler', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Page Header / Hero --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Kesiswaan"
                title="Ekstrakurikuler"
                subtitle="Beragam pilihan kegiatan pengembangan minat, bakat, olahraga, seni, dan kepanduan bagi seluruh siswa."
                align="left"
            />
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Extracurricular Grid --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($extracurriculars as $ekskul)
                    <div class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-border-soft bg-white transition-all duration-200 hover:shadow-md hover:border-sage/50">

                        <div>
                            {{-- Image Area --}}
                            @if(!empty($ekskul['image']))
                                <div class="aspect-video w-full overflow-hidden bg-soft">
                                    <img
                                        src="{{ $ekskul['image'] }}"
                                        alt="{{ $ekskul['name'] }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                </div>
                            @else
                                <div class="aspect-video w-full overflow-hidden">
                                    <x-image-placeholder
                                        :label="$ekskul['name']"
                                        icon="academic-cap"
                                        class="h-full w-full"
                                    />
                                </div>
                            @endif

                            {{-- Card Body --}}
                            <div class="p-5">
                                {{-- Category Badge --}}
                                @if(!empty($ekskul['kategori']))
                                    <div class="mb-2.5">
                                        <x-badge variant="sage" size="sm">{{ $ekskul['kategori'] }}</x-badge>
                                    </div>
                                @endif

                                {{-- Name --}}
                                <h3 class="font-bold text-lg text-darkgreen group-hover:text-forest transition-colors leading-snug">
                                    {{ $ekskul['name'] }}
                                </h3>

                                {{-- Description --}}
                                <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-muted-text">
                                    {{ $ekskul['description'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Schedule Footer --}}
                        @if(!empty($ekskul['jadwal']))
                            <div class="px-5 pb-5 pt-0">
                                <div class="flex items-center gap-2 border-t border-border-soft pt-3 text-xs text-forest font-medium">
                                    <x-icon name="clock" class="h-4 w-4 shrink-0 text-sage" />
                                    <span>{{ $ekskul['jadwal'] }}</span>
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>
    </section>

</x-layouts.public>
