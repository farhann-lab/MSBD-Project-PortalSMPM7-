<x-layouts.public title="Direktori Guru" description="Daftar tenaga pendidik aktif SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => url('/')],
        ['label' => 'Direktori',  'url' => '#'],
        ['label' => 'Direktori Guru', 'url' => url('/guru')],
    ]" />

    {{-- Section Heading --}}
    <x-section-heading
        kicker="Direktori Guru"
        title="Tenaga Pendidik Kami"
        subtitle="Daftar tenaga pendidik aktif SMP Muhammadiyah 7 Medan"
        align="left"
    />

    {{-- Privacy Disclaimer Banner --}}
    <div class="mt-6 flex items-start gap-3 rounded-xl border border-mint/60 bg-mint/20 p-4">
        <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-teal-600" />
        <p class="text-sm leading-relaxed text-darkgreen">
            <span class="font-semibold">Informasi Privasi:</span>
            Direktori ini hanya menampilkan nama dan mata pelajaran guru. Data pribadi seperti NIP, alamat, dan kontak
            tidak ditampilkan sesuai kebijakan privasi sekolah.
        </p>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ url('/guru') }}" class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <x-search-input name="q" :value="request('q', '')" placeholder="Cari nama guru..." />
        </div>

        <div class="min-w-[200px]">
            <x-filter-select
                name="mapel"
                :options="$mapelOptions"
                :selected="request('mapel', '')"
                label="Filter Mata Pelajaran"
            />
        </div>

        <div class="flex items-center gap-2">
            <x-button type="submit" variant="primary" size="md" icon="search" iconPosition="left">
                Cari
            </x-button>

            @if(request('q') || request('mapel'))
                <x-button href="{{ url('/guru') }}" variant="outline" size="md">
                    Reset
                </x-button>
            @endif
        </div>
    </form>

    {{-- Result Count --}}
    <p class="mt-5 text-sm text-muted-text">
        Menampilkan <span class="font-semibold text-darkgreen">{{ count($teachers) }}</span> guru{{ request('q') || request('mapel') ? ' (hasil filter)' : '' }}
    </p>

    {{-- Teacher Grid --}}
    @if(!empty($teachers))
        <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($teachers as $guru)
                <x-teacher-card
                    :name="$guru['nama_lengkap']"
                    :mapel="$guru['mapel']"
                    :jabatan="$guru['jabatan']"
                />
            @endforeach
        </div>
    @else
        <div class="mt-6">
            <x-empty-state
                title="Guru Tidak Ditemukan"
                message="Tidak ada guru yang cocok dengan pencarian atau filter yang Anda terapkan. Coba ubah kata kunci atau hapus filter."
                :resetUrl="url('/guru')"
                icon="search"
            />
        </div>
    @endif

</x-layouts.public>
