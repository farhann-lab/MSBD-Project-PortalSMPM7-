<x-layouts.public title="Direktori Siswa" description="Daftar siswa aktif SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Beranda',         'url' => url('/')],
        ['label' => 'Direktori',       'url' => '#'],
        ['label' => 'Direktori Siswa', 'url' => url('/siswa')],
    ]" />

    {{-- Section Heading --}}
    <x-section-heading
        kicker="Direktori Siswa"
        title="Data Siswa Aktif"
        subtitle="Daftar siswa aktif SMP Muhammadiyah 7 Medan"
        align="left"
    />

    {{-- Privacy Disclaimer Banner --}}
    <div class="mt-6 flex items-start gap-3 rounded-xl border border-mint/60 bg-mint/20 p-4">
        <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-teal-600" />
        <p class="text-sm leading-relaxed text-darkgreen">
            <span class="font-semibold">Informasi Privasi:</span>
            Direktori ini hanya menampilkan nama dan kelas siswa. Data pribadi seperti NIS, NISN, foto, alamat, dan
            kontak tidak ditampilkan sesuai kebijakan privasi sekolah.
        </p>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ url('/siswa') }}" class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <x-search-input name="q" :value="request('q', '')" placeholder="Cari nama siswa..." />
        </div>

        <div class="min-w-[200px]">
            <x-filter-select
                name="kelas"
                :options="$kelasOptions"
                :selected="request('kelas', '')"
                label="Filter Kelas"
            />
        </div>

        <div class="flex items-center gap-2">
            <x-button type="submit" variant="primary" size="md" icon="search" iconPosition="left">
                Cari
            </x-button>

            @if(request('q') || request('kelas'))
                <x-button href="{{ url('/siswa') }}" variant="outline" size="md">
                    Reset
                </x-button>
            @endif
        </div>
    </form>

    {{-- Result Count --}}
    <p class="mt-5 text-sm text-muted-text">
        Menampilkan <span class="font-semibold text-darkgreen">{{ count($students) }}</span> siswa{{ request('q') || request('kelas') ? ' (hasil filter)' : '' }}
    </p>

    {{-- Student Grid --}}
    @if(!empty($students))
        <div class="mt-4 grid grid-cols-1 gap-3.5 sm:grid-cols-2">
            @foreach($students as $siswa)
                <x-student-card
                    :name="$siswa['nama_lengkap']"
                    :kelas="$siswa['kelas']"
                />
            @endforeach
        </div>
    @else
        <div class="mt-6">
            <x-empty-state
                title="Siswa Tidak Ditemukan"
                message="Tidak ada siswa yang cocok dengan pencarian atau filter yang Anda terapkan. Coba ubah kata kunci atau hapus filter."
                :resetUrl="url('/siswa')"
                icon="search"
            />
        </div>
    @endif

</x-layouts.public>
