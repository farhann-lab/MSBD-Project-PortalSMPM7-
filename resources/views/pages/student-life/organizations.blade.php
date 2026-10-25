<x-layouts.public title="Organisasi Siswa" description="Organisasi dan kegiatan kesiswaan SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Kesiswaan', 'url' => ''],
                ['label' => 'Organisasi Siswa', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Page Header / Hero --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Kesiswaan"
                title="Organisasi Siswa"
                subtitle="Wadah pembentukan kepemimpinan, kemandirian, dan karakter Islami siswa SMP Muhammadiyah 7 Medan."
                align="left"
            />

            {{-- Data Sample Notice --}}
            <div class="mt-4">
                <x-badge variant="dummy" size="sm">Data Contoh</x-badge>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Organization Grid --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($organizations as $org)
                    @php
                        $orgName = $org['name'] ?? ($org['nama'] ?? 'Organisasi');
                    @endphp
                    <div class="group flex flex-col justify-between rounded-2xl border border-border-soft bg-white p-6 transition-all duration-200 hover:shadow-md hover:border-sage/50">

                        <div>
                            {{-- Header: Logo / Initial & Name --}}
                            <div class="mb-5 flex items-center gap-4">
                                @if(!empty($org['logo']))
                                    <img
                                        src="{{ $org['logo'] }}"
                                        alt="Logo {{ $orgName }}"
                                        class="h-14 w-14 rounded-2xl object-cover border border-border-soft"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-forest/10 border border-forest/15 text-forest text-lg font-bold select-none">
                                        {{ mb_strtoupper(mb_substr($orgName, 0, 2)) }}
                                    </div>
                                @endif

                                <div>
                                    <span class="text-xs font-semibold text-olive uppercase tracking-wider">Organisasi</span>
                                    <h3 class="font-bold text-xl leading-snug text-darkgreen group-hover:text-forest transition-colors">
                                        {{ $orgName }}
                                    </h3>
                                </div>
                            </div>

                            {{-- Description --}}
                            <p class="text-sm leading-relaxed text-muted-text">
                                {{ $org['description'] ?? ($org['deskripsi'] ?? '') }}
                            </p>

                            {{-- Divider --}}
                            <hr class="my-5 border-border-soft" />

                            {{-- Activities --}}
                            @if(!empty($org['activities']))
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-darkgreen mb-3">
                                        Kegiatan Utama
                                    </p>
                                    <ul class="space-y-2 text-sm text-muted-text">
                                        @foreach($org['activities'] as $activity)
                                            <li class="flex items-start gap-2">
                                                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-forest"></span>
                                                <span class="leading-snug">{{ $activity }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </section>

</x-layouts.public>
