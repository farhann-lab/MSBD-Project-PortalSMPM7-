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
    <x-page-hero
        title="Ekstrakurikuler"
        subtitle="Beragam pilihan kegiatan pengembangan minat, bakat, olahraga, seni, dan kepanduan bagi seluruh siswa."
        badge="Kesiswaan"
        badgeIcon="star"
        :image="asset('images/school/lapangan.jpg')"
        imageAlt="Lapangan dan Gedung SMP Muhammadiyah 7 Medan"
        imagePosition="center_35%"
    />

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($extracurriculars as $ekskul)
                    @php($gambarUrl = $ekskul->imageUrl(route('student-life.extracurriculars.image', $ekskul->id_ekstra)))
                    <div class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-border-soft bg-white transition-all duration-200 hover:shadow-md hover:border-sage/50">
                        <div>
                            @if($gambarUrl)
                                <div class="aspect-video w-full overflow-hidden bg-soft">
                                    <img
                                        src="{{ $gambarUrl }}"
                                        alt="{{ $ekskul->nama }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                </div>
                            @else
                                <div class="aspect-video w-full overflow-hidden">
                                    <x-image-placeholder
                                        :label="$ekskul->nama"
                                        icon="academic-cap"
                                        class="h-full w-full"
                                    />
                                </div>
                            @endif

                            <div class="p-5">
                                <h3 class="font-bold text-lg text-darkgreen group-hover:text-forest transition-colors leading-snug">
                                    {{ $ekskul->nama }}
                                </h3>
                                @if($ekskul->deskripsi)
                                    <p class="mt-2 whitespace-pre-line wrap-break-word text-sm leading-relaxed text-muted-text">{{ $ekskul->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <x-empty-state title="Belum ada ekstrakurikuler yang dipublikasikan"
                        message="Informasi kegiatan akan tampil setelah diterbitkan oleh sekolah."
                        icon="academic-cap" class="col-span-full" />
                @endforelse
            </div>

        </div>
    </section>

</x-layouts.public>
