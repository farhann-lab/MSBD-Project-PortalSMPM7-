<x-layouts.public title="Direktori Siswa" description="Daftar siswa aktif SMP Muhammadiyah 7 Medan.">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Direktori', 'url' => ''],
                ['label' => 'Direktori Siswa', 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Page Header / Hero --}}
    <section class="bg-gradient-to-b from-white to-soft py-10 sm:py-14 border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                kicker="Direktori Siswa"
                title="Data Siswa Aktif"
                subtitle="Daftar peserta didik aktif tahun ajaran berjalan di SMP Muhammadiyah 7 Medan."
                align="left"
            />

            {{-- Privacy Disclaimer Banner (DIR-8) --}}
            <div class="mt-6 flex items-start gap-3 rounded-xl border border-mint/60 bg-mint/20 p-4 max-w-3xl">
                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-forest" />
                <p class="text-sm leading-relaxed text-darkgreen">
                    <span class="font-semibold">Informasi Privasi:</span>
                    Direktori ini hanya menampilkan nama dan kelas peserta didik aktif. Sesuai kebijakan perlindungan privasi peserta didik (DIR-8), NIS, NISN, NIK, alamat rumah, nomor telepon, dan data pribadi lainnya tidak dipublikasikan ke publik.
                </p>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filter & Search Form --}}
            <form method="GET" action="{{ url('/siswa') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-wrap bg-white p-5 rounded-2xl border border-border-soft shadow-2xs">
                <div class="flex-1 min-w-[240px]">
                    <x-search-input name="q" :value="request('q', '')" placeholder="Cari nama siswa..." />
                </div>

                <div class="min-w-[220px]">
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
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-muted-text">
                    Menampilkan <span class="font-semibold text-darkgreen">{{ count($students) }}</span> siswa{{ request('q') || request('kelas') ? ' (hasil penyaringan)' : '' }}
                </p>
            </div>

            {{-- Student Grid --}}
            @if(!empty($students))
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($students as $siswa)
                        <x-student-card
                            :name="$siswa['nama_lengkap']"
                            :kelas="$siswa['kelas']"
                        />
                    @endforeach
                </div>
            @else
                <div class="mt-8">
                    <x-empty-state
                        title="Siswa Tidak Ditemukan"
                        message="Tidak ada data siswa yang sesuai dengan kata kunci atau filter kelas yang Anda pilih. Silakan coba kata kunci lain atau hapus filter."
                        :resetUrl="url('/siswa')"
                        icon="search"
                    />
                </div>
            @endif

        </div>
    </section>

</x-layouts.public>
