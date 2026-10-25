@props([
    'title',
    'image' => null,
    'category' => null,
    'date' => null,
    'aspect' => '4/3',
])

@php
    $aspectClasses = [
        '4/3' => 'aspect-4/3',
        'video' => 'aspect-video',
        'square' => 'aspect-square',
    ][$aspect] ?? 'aspect-4/3';
@endphp

<article {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-2xl border border-border-soft bg-white transition-all duration-200 hover:shadow-md hover:border-sage/50']) }}>
    <div class="relative overflow-hidden {{ $aspectClasses }} bg-soft">
        @if($image)
            <img 
                src="{{ asset($image) }}" 
                alt="{{ $title }}" 
                loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                onerror="this.onerror=null; this.src='{{ asset('images/placeholders/neutral-placeholder.svg') }}';"
            />
        @else
            <x-image-placeholder :aspect="$aspect" :label="$title" icon="photo" class="w-full h-full" />
        @endif

        @if($category)
            <div class="absolute top-3 left-3">
                <x-badge variant="forest" size="sm">
                    {{ $category }}
                </x-badge>
            </div>
        @endif
    </div>

    <div class="p-4 sm:p-5 flex flex-col justify-between flex-1">
        <h4 class="text-sm sm:text-base font-bold text-darkgreen group-hover:text-forest transition-colors line-clamp-2 leading-snug">
            {{ $title }}
        </h4>

        @if($date)
            <div class="flex items-center gap-1.5 text-xs text-muted-text mt-3 pt-2.5 border-t border-border-soft/60">
                <x-icon name="calendar" class="w-3.5 h-3.5 text-sage" />
                <time datetime="{{ $date }}">{{ $date }}</time>
            </div>
        @endif
    </div>
</article>
