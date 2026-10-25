<x-layouts.public :title="$announcement['title']" :description="$announcement['summary'] ?? null">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Pengumuman', 'url' => url('/pengumuman')],
                ['label' => Str::limit($announcement['title'], 40), 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Announcement Content --}}
    <article class="py-10 sm:py-14">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">

            {{-- Back Button --}}
            <div class="mb-6">
                <x-button href="{{ url('/pengumuman') }}" variant="outline" size="sm" icon="arrow-left">
                    Kembali ke Daftar Pengumuman
                </x-button>
            </div>

            {{-- Important Banner --}}
            @if(isset($announcement['important']) && $announcement['important'])
                <div class="mb-6 flex items-start gap-3 bg-amber-50 border border-amber-300 rounded-xl p-4">
                    <x-icon name="sparkles" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-bold text-amber-800">Pengumuman Penting</p>
                        <p class="text-xs text-amber-700 mt-0.5">Harap dibaca dengan seksama dan segera ditindaklanjuti.</p>
                    </div>
                </div>
            @endif

            {{-- Announcement Header Card --}}
            <div class="bg-white rounded-2xl border border-border-soft p-6 sm:p-8 mb-8">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if(isset($announcement['tag']) && $announcement['tag'])
                        <x-badge variant="sage" size="md">{{ $announcement['tag'] }}</x-badge>
                    @endif
                    @if(isset($announcement['important']) && $announcement['important'])
                        <x-badge variant="warning" size="md" dot>Penting</x-badge>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-darkgreen tracking-tight leading-tight mb-4">
                    {{ $announcement['title'] }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-sm text-muted-text pt-4 border-t border-border-soft">
                    @if(isset($announcement['date']) && $announcement['date'])
                        <div class="flex items-center gap-1.5">
                            <x-icon name="calendar" class="w-4 h-4 text-sage" />
                            <time datetime="{{ $announcement['date'] }}">{{ $announcement['date'] }}</time>
                        </div>
                    @endif
                    <div class="flex items-center gap-1.5">
                        <x-icon name="building-office" class="w-4 h-4 text-sage" />
                        <span>{{ config('sekolah.nama') }}</span>
                    </div>
                </div>
            </div>

            {{-- Announcement Body --}}
            <div class="bg-white rounded-2xl border border-border-soft p-6 sm:p-8 text-base text-darkgreen leading-relaxed space-y-5">
                {!! $announcement['content'] !!}
            </div>

            {{-- Footer Navigation --}}
            <div class="mt-8 pt-6 border-t border-border-soft flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <p class="text-xs text-muted-text">
                    Untuk informasi lebih lanjut, hubungi <a href="tel:{{ config('sekolah.kontak.telepon_raw') }}" class="text-forest font-medium hover:underline">{{ config('sekolah.kontak.telepon') }}</a>
                    atau email <a href="mailto:{{ config('sekolah.kontak.email') }}" class="text-forest font-medium hover:underline">{{ config('sekolah.kontak.email') }}</a>
                </p>
                <x-button href="{{ url('/pengumuman') }}" variant="secondary" size="sm" icon="arrow-left">
                    Kembali ke Pengumuman
                </x-button>
            </div>
        </div>
    </article>

</x-layouts.public>
