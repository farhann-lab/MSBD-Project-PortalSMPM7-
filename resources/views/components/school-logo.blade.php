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
    {{-- Emblem / Logo Image --}}
    <div class="{{ $badgeSizes }} {{ $crestBg }} rounded-xl flex items-center justify-center shrink-0 p-1 shadow-2xs overflow-hidden">
        <img 
            src="{{ asset('images/school/logo.png') }}" 
            alt="Logo {{ config('sekolah.nama', 'SMP Muhammadiyah 7 Medan') }}" 
            class="w-full h-full object-contain"
            width="48"
            height="48"
        />
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
