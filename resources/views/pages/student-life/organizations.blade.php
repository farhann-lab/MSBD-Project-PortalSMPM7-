<x-layouts.public title="Organisasi Siswa" description="Organisasi dan kegiatan kesiswaan SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Beranda',           'url' => url('/')],
        ['label' => 'Kesiswaan',         'url' => '#'],
        ['label' => 'Organisasi Siswa',  'url' => url('/kesiswaan/organisasi')],
    ]" />

    {{-- Data Sample Notice --}}
    <div class="mb-4 mt-2">
        <x-badge variant="dummy" size="sm">Data Contoh</x-badge>
    </div>

    {{-- Section Heading --}}
    <x-section-heading
        kicker="Kesiswaan"
        title="Organisasi Siswa"
        subtitle="Wadah pengembangan diri, kepemimpinan, dan kreativitas siswa SMP Muhammadiyah 7 Medan"
        align="left"
    />

    {{-- Organization Grid --}}
    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($organizations as $org)
            @php
                $orgName = $org['name'] ?? ($org['nama'] ?? 'Organisasi');
            @endphp
            <div class="group rounded-2xl border border-border-soft bg-white p-6 transition-shadow hover:shadow-md">

                {{-- Logo / Initial --}}
                <div class="mb-4 flex items-center gap-4">
                    @if(!empty($org['logo']))
                        <img
                            src="{{ $org['logo'] }}"
                            alt="Logo {{ $orgName }}"
                            class="h-14 w-14 rounded-full object-cover"
                            loading="lazy"
                        />
                    @else
                        {{-- Fallback: circle with initials --}}
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-forest text-white text-lg font-bold select-none">
                            {{ mb_strtoupper(mb_substr($orgName, 0, 2)) }}
                        </div>
                    @endif

                    <h3 class="font-bold text-xl leading-snug text-darkgreen">
                        {{ $orgName }}
                    </h3>
                </div>

                {{-- Description --}}
                <p class="text-sm leading-relaxed text-muted-text">
                    {{ $org['description'] ?? ($org['deskripsi'] ?? '') }}
                </p>

                {{-- Divider --}}
                <hr class="my-4 border-border-soft" />

                {{-- Activities --}}
                <p class="mb-2 text-sm font-semibold text-forest">Kegiatan Utama:</p>
                <ul class="space-y-1.5">
                    @foreach(($org['activities'] ?? ($org['kegiatan'] ?? [])) as $kegiatan)
                        <li class="flex items-start gap-2 text-sm text-muted-text">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-forest" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                            {{ $kegiatan }}
                        </li>
                    @endforeach
                </ul>

            </div>
        @endforeach
    </div>

</x-layouts.public>
