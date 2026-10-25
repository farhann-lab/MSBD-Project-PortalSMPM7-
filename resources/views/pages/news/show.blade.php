<x-layouts.public :title="$article['title']" :description="$article['summary'] ?? null">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-border-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <x-breadcrumb :items="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => 'Berita', 'url' => url('/berita')],
                ['label' => Str::limit($article['title'], 40), 'url' => ''],
            ]" />
        </div>
    </div>

    {{-- Article Content --}}
    <article class="py-10 sm:py-14">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">

            {{-- Back Button Top --}}
            <div class="mb-6">
                <x-button href="{{ url('/berita') }}" variant="outline" size="sm" icon="arrow-left">
                    Kembali ke Daftar Berita
                </x-button>
            </div>

            {{-- Article Meta --}}
            <div class="space-y-4 mb-8">
                @if(isset($article['category']) && $article['category'])
                    <x-badge variant="forest" size="md">{{ $article['category'] }}</x-badge>
                @endif

                <h1 class="text-3xl sm:text-4xl font-extrabold text-darkgreen tracking-tight leading-tight">
                    {{ $article['title'] }}
                </h1>

                @if(isset($article['date']) && $article['date'])
                    <div class="flex items-center gap-2 text-sm text-muted-text">
                        <x-icon name="calendar" class="w-4 h-4 text-sage shrink-0" />
                        <time datetime="{{ $article['date'] }}">{{ $article['date'] }}</time>
                        <span class="text-border-soft">—</span>
                        <span>SMP Muhammadiyah 7 Medan</span>
                    </div>
                @endif
            </div>

            {{-- Article Hero Image --}}
            <div class="mb-8 rounded-2xl overflow-hidden">
                @if(isset($article['image']) && $article['image'])
                    <img
                        src="{{ asset($article['image']) }}"
                        alt="{{ $article['title'] }}"
                        loading="lazy"
                        class="w-full aspect-video object-cover"
                    />
                @else
                    <x-image-placeholder aspect="video" :label="$article['title']" icon="newspaper" class="w-full rounded-2xl" />
                @endif
            </div>

            {{-- Article Summary (Lead) --}}
            @if(isset($article['summary']) && $article['summary'])
                <p class="text-lg text-muted-text leading-relaxed font-medium border-l-4 border-forest pl-5 mb-8 py-2">
                    {{ $article['summary'] }}
                </p>
            @endif

            {{-- Article Body --}}
            <div class="prose-article text-base text-darkgreen leading-relaxed space-y-5">
                {!! $article['content'] !!}
            </div>

            {{-- Article Footer --}}
            <div class="mt-10 pt-8 border-t border-border-soft flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="text-xs text-muted-text">
                    <span class="font-medium text-darkgreen">{{ config('sekolah.nama') }}</span>
                    @if(isset($article['date']) && $article['date'])
                        &bull; Diterbitkan {{ $article['date'] }}
                    @endif
                </div>

                <x-button href="{{ url('/berita') }}" variant="secondary" size="sm" icon="arrow-left">
                    Kembali ke Daftar Berita
                </x-button>
            </div>
        </div>
    </article>

    {{-- Related / CTA Section --}}
    <section class="bg-soft border-t border-border-soft py-10 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center space-y-4">
            <p class="text-sm text-muted-text">Baca juga berita dan informasi lainnya dari SMP Muhammadiyah 7 Medan</p>
            <div class="flex flex-wrap justify-center gap-3">
                <x-button href="{{ url('/berita') }}" variant="primary" icon="arrow-right" iconPosition="right">
                    Semua Berita
                </x-button>
                <x-button href="{{ url('/pengumuman') }}" variant="outline">
                    Pengumuman Resmi
                </x-button>
            </div>
        </div>
    </section>

</x-layouts.public>
