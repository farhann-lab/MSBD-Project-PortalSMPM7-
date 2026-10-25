<x-layouts.public title="Prestasi Siswa" description="Daftar prestasi siswa SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Beranda',        'url' => url('/')],
        ['label' => 'Kesiswaan',      'url' => '#'],
        ['label' => 'Prestasi Siswa', 'url' => url('/kesiswaan/prestasi')],
    ]" />

    {{-- ⚠ Mandatory Data Sample Warning Banner (PRD KSW-3) --}}
    <div class="mt-4 flex items-start gap-3 rounded-xl border border-amber-300 bg-amber-50 p-4">
        {{-- Warning triangle icon --}}
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
        <div>
            <p class="font-bold text-amber-800">Perhatian: Data Contoh</p>
            <p class="mt-0.5 text-sm leading-relaxed text-amber-700">
                Data prestasi di bawah ini merupakan data contoh yang belum diverifikasi secara resmi.
                Data akan diperbarui setelah mendapat persetujuan dari pihak sekolah.
            </p>
        </div>
    </div>

    {{-- Section Heading --}}
    <x-section-heading
        kicker="Kesiswaan"
        title="Prestasi Siswa"
        subtitle="Catatan pencapaian dan kebanggaan siswa SMP Muhammadiyah 7 Medan di berbagai bidang"
        align="left"
    />

    {{-- Achievements Grid --}}
    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($achievements as $achievement)

            @php
                // Badge variant per tingkat
                $tingkatVariant = match($achievement['tingkat'] ?? '') {
                    'Provinsi' => 'teal',
                    'Nasional' => 'forest',
                    default    => 'sage',   // Kota / lainnya
                };
            @endphp

            <div class="group flex flex-col overflow-hidden rounded-2xl border border-border-soft bg-white transition-shadow hover:shadow-md">

                {{-- Card Header --}}
                <div class="flex items-center justify-between rounded-t-2xl bg-forest/[.08] p-4">
                    <x-badge :variant="$tingkatVariant" size="md">
                        {{ $achievement['tingkat'] ?? 'Umum' }}
                    </x-badge>
                    <span class="text-sm text-muted-text">{{ $achievement['tahun'] ?? '' }}</span>
                </div>

                {{-- Card Body --}}
                <div class="flex flex-1 flex-col p-5">
                    {{-- Type badge --}}
                    @if(!empty($achievement['jenis']))
                        <x-badge variant="mint" size="sm">{{ $achievement['jenis'] }}</x-badge>
                    @endif

                    {{-- Title --}}
                    <h3 class="mt-2 text-lg font-bold leading-snug text-darkgreen">
                        {{ $achievement['name'] }}
                    </h3>

                    {{-- Description --}}
                    @if(!empty($achievement['description']))
                        <p class="mt-2 text-sm leading-relaxed text-muted-text">
                            {{ $achievement['description'] }}
                        </p>
                    @endif
                </div>

                {{-- Card Footer --}}
                <div class="border-t border-border-soft px-5 py-3">
                    <x-badge variant="dummy" size="sm">Data Contoh</x-badge>
                </div>

            </div>
        @endforeach
    </div>

</x-layouts.public>
