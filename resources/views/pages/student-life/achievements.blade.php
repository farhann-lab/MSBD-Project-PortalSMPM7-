<x-layouts.public title="Prestasi Siswa" description="Daftar prestasi siswa SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Kesiswaan', 'url' => ''],
                ['label' => 'Prestasi Siswa', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Page Header / Hero --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Kesiswaan"
                title="Prestasi Siswa"
                subtitle="Catatan capaian prestasi, kejuaraan, dan kebanggaan siswa SMP Muhammadiyah 7 Medan di berbagai bidang."
                align="left"
            />

            {{-- Mandatory Data Sample Warning Banner (PRD KSW-3) --}}
            <div class="mt-6 flex items-start gap-3 rounded-xl border border-amber-300 bg-amber-50 p-4 max-w-3xl">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <div>
                    <p class="font-bold text-amber-900">Perhatian: Data Contoh</p>
                    <p class="mt-0.5 text-sm leading-relaxed text-amber-800">
                        Data prestasi di bawah ini merupakan data contoh untuk keperluan demonstrasi portal dan belum diverifikasi secara resmi. Data definitif akan diperbarui setelah validasi dari pihak sekolah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Achievements Grid --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($achievements as $achievement)

                    @php
                        $tingkatVariant = match($achievement['tingkat'] ?? '') {
                            'Provinsi' => 'teal',
                            'Nasional' => 'forest',
                            default    => 'sage',
                        };
                    @endphp

                    <div class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-border-soft bg-white transition-all duration-200 hover:shadow-md hover:border-sage/50">

                        <div>
                            {{-- Card Header --}}
                            <div class="flex items-center justify-between border-b border-border-soft/60 bg-forest/[.04] px-5 py-3.5">
                                <x-badge :variant="$tingkatVariant" size="md">
                                    Tingkat {{ $achievement['tingkat'] ?? 'Umum' }}
                                </x-badge>
                                <span class="text-xs font-semibold text-muted-text">{{ $achievement['tahun'] ?? '' }}</span>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5">
                                @if(!empty($achievement['jenis']))
                                    <div class="mb-2">
                                        <x-badge variant="mint" size="sm">{{ $achievement['jenis'] }}</x-badge>
                                    </div>
                                @endif

                                <h3 class="font-bold text-lg text-darkgreen group-hover:text-forest transition-colors leading-snug">
                                    {{ $achievement['name'] ?? ($achievement['title'] ?? 'Prestasi Siswa') }}
                                </h3>

                                <p class="mt-2 text-sm leading-relaxed text-muted-text">
                                    {{ $achievement['description'] ?? ($achievement['deskripsi'] ?? '') }}
                                </p>
                            </div>
                        </div>

                        {{-- Peraih Prestasi Footer --}}
                        @if(!empty($achievement['peraih']))
                            <div class="px-5 pb-5 pt-0">
                                <div class="flex items-center gap-2 border-t border-border-soft pt-3 text-xs text-olive font-medium">
                                    <x-icon name="user" class="h-4 w-4 shrink-0 text-sage" />
                                    <span>Peraih: <strong class="text-darkgreen font-semibold">{{ $achievement['peraih'] }}</strong></span>
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>
    </section>

</x-layouts.public>
