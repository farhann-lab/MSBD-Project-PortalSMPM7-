@props([
    'value',
    'label',
    'icon' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex items-center gap-4 p-5 sm:p-6 rounded-2xl border border-white/15 bg-white/95 backdrop-blur-xs transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-mint/50']) }}>
    @if($icon)
        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-forest/10 border border-forest/15 flex items-center justify-center text-forest shrink-0">
            <x-icon :name="$icon" class="w-6 h-6 sm:w-7 sm:h-7" />
        </div>
    @endif

    <div class="min-w-0">
        <div class="text-2xl sm:text-3xl font-extrabold text-darkgreen tracking-tight">
            {{ $value }}
        </div>
        <div class="text-sm font-bold text-forest mt-0.5 truncate">
            {{ $label }}
        </div>
        @if($description)
            <div class="text-[11px] sm:text-xs text-muted-text mt-0.5 truncate">
                {{ $description }}
            </div>
        @endif
    </div>
</div>
