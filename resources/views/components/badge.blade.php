@props([
    'variant' => 'default',
    'size' => 'md',
    'dot' => false,
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center font-medium rounded-full transition-colors';

    $sizeClasses = [
        'sm' => 'px-2.5 py-0.5 text-[11px] gap-1',
        'md' => 'px-3 py-1 text-xs gap-1.5',
        'lg' => 'px-3.5 py-1.5 text-sm gap-2',
    ][$size] ?? 'px-3 py-1 text-xs gap-1.5';

    $variantClasses = [
        'default' => 'bg-mint/40 text-forest border border-mint/60',
        'mint' => 'bg-mint/40 text-forest border border-mint/60',
        'sage' => 'bg-sage/20 text-darkgreen border border-sage/30',
        'forest' => 'bg-forest text-white border border-forest',
        'darkgreen' => 'bg-darkgreen text-white border border-darkgreen',
        'soft' => 'bg-soft text-darkgreen border border-border-soft',
        'outline' => 'bg-white text-darkgreen border border-border-soft',
        'teal' => 'bg-teal/15 text-teal border border-teal/30',
        'warning' => 'bg-amber-50 text-amber-800 border border-amber-200',
        'dummy' => 'bg-amber-100/80 text-amber-900 border border-amber-300 font-semibold',
    ][$variant] ?? 'bg-mint/40 text-forest border border-mint/60';

    $dotColorClasses = [
        'default' => 'bg-forest',
        'mint' => 'bg-forest',
        'sage' => 'bg-forest',
        'forest' => 'bg-white',
        'darkgreen' => 'bg-mint',
        'soft' => 'bg-forest',
        'outline' => 'bg-sage',
        'teal' => 'bg-teal',
        'warning' => 'bg-amber-500',
        'dummy' => 'bg-amber-600',
    ][$variant] ?? 'bg-forest';
@endphp

<span {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}>
    @if($dot)
        <span class="inline-block w-1.5 h-1.5 rounded-full {{ $dotColorClasses }}" aria-hidden="true"></span>
    @endif

    @if($icon)
        <x-icon :name="$icon" class="w-3.5 h-3.5 shrink-0" />
    @endif

    <span>{{ $slot }}</span>
</span>
