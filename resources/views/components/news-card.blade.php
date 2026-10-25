@props([
    'title',
    'slug' => '#',
    'summary' => null,
    'date' => null,
    'image' => null,
    'category' => null,
])

@php
    $url = $slug !== '#' ? url('/berita/' . $slug) : '#';
@endphp

<article {{ $attributes->merge(['class' => 'group flex flex-col h-full overflow-hidden rounded-2xl border border-border-soft bg-white transition-all duration-200 hover:shadow-md hover:border-sage/50']) }}>
    {{-- Card Thumbnail --}}
    <a href="{{ $url }}" class="relative block overflow-hidden aspect-video bg-soft shrink-0" tabindex="-1" aria-hidden="true">
        @if($image)
            <img 
                src="{{ asset($image) }}" 
                alt="{{ $title }}" 
                loading="lazy" 
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                onerror="this.onerror=null; this.src='{{ asset('images/placeholders/neutral-placeholder.svg') }}';"
            />
        @else
            <x-image-placeholder aspect="video" :label="$title" icon="newspaper" class="w-full h-full" />
        @endif

        @if($category)
            <div class="absolute top-3 left-3">
                <x-badge variant="forest" size="sm">
                    {{ $category }}
                </x-badge>
            </div>
        @endif
    </a>

    {{-- Card Body --}}
    <div class="flex flex-col flex-1 p-5 sm:p-6 justify-between">
        <div>
            @if($date)
                <div class="flex items-center gap-1.5 text-xs text-muted-text mb-2.5">
                    <x-icon name="calendar" class="w-3.5 h-3.5 text-sage" />
                    <time datetime="{{ $date }}">{{ $date }}</time>
                </div>
            @endif

            <h3 class="text-lg sm:text-xl font-bold text-darkgreen group-hover:text-forest transition-colors line-clamp-2 leading-snug mb-2">
                <a href="{{ $url }}" class="focus-visible:outline-none focus-visible:underline">
                    {{ $title }}
                </a>
            </h3>

            @if($summary)
                <p class="text-sm text-muted-text line-clamp-2 sm:line-clamp-3 leading-relaxed">
                    {{ $summary }}
                </p>
            @endif
        </div>

        {{-- Card Footer Action --}}
        <div class="mt-5 pt-4 border-t border-border-soft/60 flex items-center justify-between">
            <a 
                href="{{ $url }}" 
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-forest hover:text-teal transition-colors group/link focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest rounded-sm"
            >
                <span>Baca Selengkapnya</span>
                <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-200 group-hover/link:translate-x-1" />
            </a>
        </div>
    </div>
</article>
