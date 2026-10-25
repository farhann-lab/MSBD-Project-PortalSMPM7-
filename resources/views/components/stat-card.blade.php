@props([
    'value',
    'label',
    'icon' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex items-center gap-4 p-5 sm:p-6 rounded-2xl border border-border-soft bg-white transition-all duration-200 hover:shadow-xs hover:border-sage/40']) }}>
    @if($icon)
        <div class="w-13 h-13 rounded-xl bg-mint/30 border border-mint/50 flex items-center justify-center text-forest shrink-0">
            <x-icon :name="$icon" class="w-6 h-6" />
        </div>
    @endif

    <div>
        <div class="text-2xl sm:text-3xl font-extrabold text-darkgreen tracking-tight">
            {{ $value }}
        </div>
        <div class="text-sm font-semibold text-forest mt-0.5">
            {{ $label }}
        </div>
        @if($description)
            <div class="text-xs text-muted-text mt-0.5">
                {{ $description }}
            </div>
        @endif
    </div>
</div>
