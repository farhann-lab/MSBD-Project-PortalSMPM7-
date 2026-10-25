<x-layouts.public title="Direktori Guru" description="Daftar tenaga pendidik aktif SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Direktori', 'url' => ''],
                ['label' => 'Direktori Guru', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Page Header / Hero --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Direktori Guru"
                title="Tenaga Pendidik Kami"
                subtitle="Daftar pendidik profesional dan berdedikasi tinggi di lingkungan SMP Muhammadiyah 7 Medan."
                align="left"
            />

            {{-- Privacy Disclaimer Banner (DIR-8) --}}
            <div class="mt-6 flex items-start gap-3 rounded-xl border border-mint/60 bg-mint/20 p-4 max-w-3xl">
                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-forest" />
                <p class="text-sm leading-relaxed text-darkgreen">
                    <span class="font-semibold">Informasi Privasi:</span>
                    Direktori ini hanya menampilkan nama dan mata pelajaran guru. Sesuai kebijakan perlindungan data pribadi sekolah (DIR-8), NIP, nomor kontak, dan alamat pribadi tidak dipublikasikan ke publik.
                </p>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filter & Search Form --}}
            <form method="GET" action="{{ url('/guru') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-wrap bg-white p-5 rounded-2xl border border-border-soft shadow-2xs">
                <div class="flex-1 min-w-[240px]">
                    <x-search-input name="q" :value="request('q', '')" placeholder="Cari nama guru..." />
                </div>

                <div class="min-w-[220px]">
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
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-muted-text">
                    Menampilkan <span class="font-semibold text-darkgreen">{{ count($teachers) }}</span> guru{{ request('q') || request('mapel') ? ' (hasil penyaringan)' : '' }}
                </p>
            </div>

            {{-- Teacher Grid --}}
            @if(!empty($teachers))
                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($teachers as $guru)
                        <x-teacher-card
                            :name="$guru['nama_lengkap']"
                            :mapel="$guru['mapel']"
                            :jabatan="$guru['jabatan']"
                        />
                    @endforeach
                </div>
            @else
                <div class="mt-8">
                    <x-empty-state
                        title="Guru Tidak Ditemukan"
                        message="Tidak ada data guru yang sesuai dengan kata kunci atau filter pencarian Anda. Silakan coba kata kunci lain atau hapus filter."
                        :resetUrl="url('/guru')"
                        icon="search"
                    />
                </div>
            @endif

        </div>
    </section>

</x-layouts.public>
