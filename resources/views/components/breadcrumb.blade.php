@props([
    'items' => [],
])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'py-3 text-xs sm:text-sm']) }}>
    <ol class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-muted-text">
        <li class="inline-flex items-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-muted-text hover:text-forest transition-colors font-medium">
                <x-icon name="school" class="w-4 h-4 text-sage" />
                <span>Beranda</span>
            </a>
        </li>

        @foreach($items as $item)
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
        @endforeach
    </ol>
</nav>
