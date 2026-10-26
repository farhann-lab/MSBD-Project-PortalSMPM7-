@props([
    'items' => [],
])

@php
    // If the items array doesn't start with Beranda, prepend it;
    // but if the caller already passed Beranda as first item, use it as is to prevent duplicate "Beranda > Beranda".
    $firstItem = $items[0] ?? null;
    $hasHomeFirst = $firstItem && strtolower(trim($firstItem['label'] ?? '')) === 'beranda';

    $normalizedItems = $hasHomeFirst
        ? $items
        : array_merge([['label' => 'Beranda', 'url' => url('/')]], $items);
@endphp

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'py-3 text-xs sm:text-sm']) }}>
    <ol class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-muted-text">
        @foreach($normalizedItems as $item)
            @if($loop->first)
                {{-- First item (Beranda) --}}
                <li class="inline-flex items-center">
                    @if(!empty($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" class="inline-flex items-center gap-1 text-muted-text hover:text-forest transition-colors font-medium">
                            <x-icon name="school" class="w-4 h-4 text-sage" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1 font-semibold text-darkgreen" aria-current="page">
                            <x-icon name="school" class="w-4 h-4 text-sage" />
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @else
                <li class="inline-flex items-center gap-1.5 sm:gap-2">
                    <x-icon name="chevron-right" class="w-3.5 h-3.5 text-sage/70 shrink-0" />

                    @if($loop->last || empty($item['url']))
                        <span class="font-semibold text-darkgreen max-w-[220px] sm:max-w-md truncate" aria-current="page">
                            {{ $item['label'] }}
                        </span>
                    @else
                        <a href="{{ $item['url'] }}" class="text-muted-text hover:text-forest transition-colors font-medium max-w-[160px] sm:max-w-xs truncate">
                            {{ $item['label'] }}
                        </a>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>
