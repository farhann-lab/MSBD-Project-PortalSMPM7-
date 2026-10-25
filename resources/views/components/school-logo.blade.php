@props([
    'size' => 'md',
    'variant' => 'default', // 'default' (for light bg), 'white' (for dark bg)
    'showText' => true,
    'href' => '/',
])

@php
    $badgeSizes = [
        'sm' => 'w-9 h-9',
        'md' => 'w-11 h-11',
        'lg' => 'w-14 h-14',
    ][$size] ?? 'w-11 h-11';

    $textSizes = [
        'sm' => 'text-sm font-bold leading-tight',
        'md' => 'text-base font-bold leading-snug',
        'lg' => 'text-xl font-extrabold leading-tight',
    ][$size] ?? 'text-base font-bold leading-snug';

    $subtextSizes = [
        'sm' => 'text-[11px] leading-tight',
        'md' => 'text-xs leading-tight',
        'lg' => 'text-sm leading-tight',
    ][$size] ?? 'text-xs leading-tight';

    $titleColor = $variant === 'white' ? 'text-white' : 'text-darkgreen group-hover:text-forest transition-colors';
    $subtitleColor = $variant === 'white' ? 'text-mint/80' : 'text-muted-text';
    $crestBg = $variant === 'white' ? 'bg-white/10 ring-1 ring-white/20' : 'bg-forest/10 ring-1 ring-forest/20 group-hover:bg-forest/15 transition-colors';
    $crestIconColor = $variant === 'white' ? 'text-mint' : 'text-forest';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 group focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-forest rounded-xl select-none']) }}>
    {{-- Emblem / Seal --}}
    <div class="{{ $badgeSizes }} {{ $crestBg }} rounded-xl flex items-center justify-center shrink-0 p-1.5 shadow-2xs">
        <svg viewBox="0 0 48 48" class="w-full h-full {{ $crestIconColor }}" fill="currentColor" aria-hidden="true">
            {{-- Outer Radiating Sun Rays (Muhammadiyah 12 Rays) --}}
            <path d="M24 2l2.5 6.5 6.8-2-1 6.9 6.7 1.8-4.2 5.5 5 4.8-6.3 3 2.1 6.6-6.8-.7-1.3 6.9-5.1-4.7-5.1 4.7-1.3-6.9-6.8.7 2.1-6.6-6.3-3 5-4.8-4.2-5.5 6.7-1.8-1-6.9 6.8 2z" opacity="0.35"/>
            {{-- Inner Circle Ring --}}
            <circle cx="24" cy="24" r="14" fill="none" stroke="currentColor" stroke-width="2.5" />
            {{-- Central Muhammadiyah Sun Star / Symbol --}}
            <circle cx="24" cy="24" r="8" opacity="0.2"/>
            <path d="M24 18l1.6 4.4 4.4.3-3.4 3 1 4.3-3.6-2.5-3.6 2.5 1-4.3-3.4-3 4.4-.3z" fill="currentColor"/>
            {{-- Small Crescent & Star Detail --}}
            <circle cx="24" cy="24" r="2.5" fill="none" stroke="currentColor" stroke-width="1.2"/>
        </svg>
    </div>

    {{-- Brand Text --}}
    @if($showText)
        <div class="flex flex-col">
            <span class="{{ $textSizes }} {{ $titleColor }} tracking-tight">
                {{ config('sekolah.nama_pendek', 'SMP Muhammadiyah 7') }}
            </span>
            <span class="{{ $subtextSizes }} {{ $subtitleColor }} font-medium">
                Kota Medan &bull; NPSN 10257325
            </span>
        </div>
    @endif
</a>
