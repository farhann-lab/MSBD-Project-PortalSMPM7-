@props([
    'title',
    'slug' => '#',
    'summary' => null,
    'date' => null,
    'tag' => null,
    'important' => false,
])

@php
    $url = $slug !== '#' ? url('/pengumuman/' . $slug) : '#';
@endphp

<article {{ $attributes->merge(['class' => 'group flex flex-col justify-between h-full rounded-2xl border border-border-soft bg-white p-5 sm:p-6 transition-all duration-200 hover:shadow-md hover:border-sage/50 relative overflow-hidden']) }}>
    @if($important)
        <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500" aria-hidden="true"></div>
    @endif

    <div>
        {{-- Header Meta --}}
        <div class="flex items-center justify-between gap-3 mb-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-mint/30 flex items-center justify-center text-forest shrink-0">
                    <x-icon name="megaphone" class="w-4 h-4 text-forest" />
                </span>
                @if($date)
                    <div class="flex items-center gap-1.5 text-xs text-muted-text font-medium">
                        <time datetime="{{ $date }}">{{ $date }}</time>
                    </div>
                @endif
            </div>

            @if($important)
                <x-badge variant="warning" size="sm" dot>
                    Penting
                </x-badge>
            @elseif($tag)
                <x-badge variant="sage" size="sm">
                    {{ $tag }}
                </x-badge>
            @endif
        </div>

        {{-- Title --}}
        <h3 class="text-lg sm:text-xl font-bold text-darkgreen group-hover:text-forest transition-colors line-clamp-2 leading-snug mb-2.5">
            <a href="{{ $url }}" class="focus-visible:outline-none focus-visible:underline">
                {{ $title }}
            </a>
        </h3>

        {{-- Summary --}}
        @if($summary)
            <p class="text-sm text-muted-text line-clamp-3 leading-relaxed mb-4">
                {{ $summary }}
            </p>
        @endif
    </div>

    {{-- Footer Action --}}
    <div class="mt-4 pt-4 border-t border-border-soft/60 flex items-center justify-between">
        <a 
            href="{{ $url }}" 
            class="inline-flex items-center gap-1.5 text-sm font-semibold text-forest hover:text-teal transition-colors group/link focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest rounded-sm"
        >
            <span>Lihat Pengumuman</span>
            <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-200 group-hover/link:translate-x-1" />
        </a>
    </div>
</article>
