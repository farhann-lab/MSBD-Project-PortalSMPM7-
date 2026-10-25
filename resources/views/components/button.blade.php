@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'iconPosition' => 'left',
])

@php
    $tag = $href ? 'a' : 'button';

    $baseClasses = 'inline-flex items-center justify-center font-semibold text-center select-none transition-all duration-150 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed disabled:pointer-events-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest';

    $sizeClasses = [
        'sm' => 'min-h-9 px-3.5 py-1.5 text-xs rounded-lg gap-1.5',
        'md' => 'min-h-11 px-5 py-2.5 text-sm rounded-xl gap-2',
        'lg' => 'min-h-12 px-6 py-3 text-base rounded-xl gap-2.5',
    ][$size] ?? 'min-h-11 px-5 py-2.5 text-sm rounded-xl gap-2';

    $variantClasses = [
        'primary' => 'bg-forest text-white hover:bg-teal active:bg-darkgreen shadow-xs',
        'secondary' => 'border border-forest text-forest bg-white hover:bg-soft active:bg-mint/20',
        'outline' => 'border border-border-soft text-darkgreen bg-white hover:border-sage hover:bg-soft/70 active:bg-border-soft/40',
        'soft' => 'bg-soft text-forest hover:bg-mint/30 active:bg-mint/50 border border-border-soft',
        'ghost' => 'text-forest hover:bg-forest/10 active:bg-forest/15',
        'mint' => 'bg-mint/50 text-darkgreen hover:bg-mint/80 active:bg-mint border border-mint/70',
        'white' => 'bg-white text-forest hover:bg-soft shadow-xs active:bg-border-soft',
    ][$variant] ?? 'bg-forest text-white hover:bg-teal active:bg-darkgreen shadow-xs';

    $iconSizes = [
        'sm' => 'w-3.5 h-3.5',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
    ][$size] ?? 'w-4 h-4';
@endphp

@if($tag === 'a')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}>
        @if($icon && $iconPosition === 'left')
            <x-icon :name="$icon" class="{{ $iconSizes }} shrink-0" />
        @endif

        <span>{{ $slot }}</span>

        @if($icon && $iconPosition === 'right')
            <x-icon :name="$icon" class="{{ $iconSizes }} shrink-0" />
        @endif
    </a>
@else
    <button type="{{ $attributes->get('type', 'button') }}" {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}>
        @if($icon && $iconPosition === 'left')
            <x-icon :name="$icon" class="{{ $iconSizes }} shrink-0" />
        @endif

        <span>{{ $slot }}</span>

        @if($icon && $iconPosition === 'right')
            <x-icon :name="$icon" class="{{ $iconSizes }} shrink-0" />
        @endif
    </button>
@endif
