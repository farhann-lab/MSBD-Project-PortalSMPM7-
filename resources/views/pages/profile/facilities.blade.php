<x-layouts.public title="Sarana & Prasarana" description="Fasilitas dan sarana prasarana yang tersedia di SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => route('home')],
                ['label' => 'Profil', 'url' => route('profile.index')],
                ['label' => 'Sarana & Prasarana', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Hero / Page Header --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Fasilitas Sekolah"
                title="Sarana & Prasarana"
                subtitle="Berbagai fasilitas penunjang kegiatan belajar-mengajar, keagamaan, dan pengembangan bakat peserta didik SMP Muhammadiyah 7 Medan."
                align="left"
            />
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Grid Fasilitas --}}
            @if(isset($facilities) && count($facilities) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($facilities as $facility)
                        <div class="bg-white border border-border-soft rounded-2xl overflow-hidden hover:shadow-md transition-shadow duration-200 flex flex-col">

                            {{-- Gambar atau Placeholder --}}
                            @if (!empty($facility['image']))
                                <img
                                    src="{{ asset($facility['image']) }}"
                                    alt="{{ $facility['name'] }}"
                                    loading="lazy"
                                    class="w-full h-48 object-cover"
                                >
                            @else
                                <x-image-placeholder
                                    aspect="video"
                                    :label="$facility['name']"
                                    :icon="$facility['icon'] ?? 'building-office'"
                                    class="h-48 w-full"
                                />
                            @endif

                            {{-- Body --}}
                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    @if (!empty($facility['icon']))
                                        <x-icon :name="$facility['icon']" class="w-5 h-5 text-forest flex-shrink-0" />
                                    @endif
                                    <h3 class="font-bold text-darkgreen">{{ $facility['name'] }}</h3>
                                </div>
                                @if (!empty($facility['description']))
                                    <p class="text-muted-text text-sm leading-relaxed flex-1">{{ $facility['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- State kosong --}}
                <div class="mt-10 flex flex-col items-center justify-center py-20 text-center">
                    <x-icon name="building-office" class="w-14 h-14 text-border-soft mb-4" />
                    <p class="text-lg font-semibold text-darkgreen">Data Fasilitas Belum Tersedia</p>
                    <p class="text-muted-text mt-1 max-w-sm">Informasi sarana dan prasarana sekolah akan segera kami tambahkan.</p>
                </div>
            @endif

            {{-- Tombol Kembali --}}
            <div class="mt-12 flex">
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
