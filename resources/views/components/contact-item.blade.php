@props([
    'icon' => 'map-pin',
    'label',
    'value',
    'href' => null,
])

<div {{ $attributes->merge(['class' => 'flex items-start gap-3.5 p-4 rounded-xl border border-border-soft bg-white']) }}>
    <div class="w-10 h-10 rounded-lg bg-mint/30 border border-mint/50 flex items-center justify-center text-forest shrink-0 mt-0.5">
        <x-icon :name="$icon" class="w-5 h-5 text-forest" />
    </div>

    <div class="flex-1 min-w-0">
        <span class="block text-xs font-semibold text-olive uppercase tracking-wider">
            {{ $label }}
        </span>

        @if($href)
            <a 
                href="{{ $href }}" 
                class="block text-sm font-medium text-darkgreen hover:text-forest transition-colors break-words mt-0.5 focus-visible:outline-2 focus-visible:outline-forest rounded-xs"
            >
                {{ $value }}
            </a>
        @else
            <p class="text-sm font-medium text-darkgreen break-words mt-0.5">
                {{ $value }}
            </p>
        @endif
    </div>
</div>
