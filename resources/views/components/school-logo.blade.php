@props([
    'size' => 'md',
    'variant' => 'default', // 'default' (for light bg), 'white' (for dark bg)
    'showText' => true,
    'href' => '/',
])

@php
    $badgeSizes = [
        'sm' => 'w-9 h-9',
        'md' => 'w-10 h-10 sm:w-11 sm:h-11',
        'lg' => 'w-14 h-14',
    ][$size] ?? 'w-10 h-10 sm:w-11 sm:h-11';

    $isWhite = $variant === 'white';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 sm:gap-3 group focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-forest rounded-xl select-none']) }}>
    {{-- Emblem / Logo Image --}}
    <div class="{{ $badgeSizes }} shrink-0 flex items-center justify-center">
        <img 
            src="{{ asset('images/school/logo.png') }}" 
            alt="Logo {{ config('sekolah.nama', 'SMP Muhammadiyah 7 Medan') }}" 
            class="w-full h-full object-contain drop-shadow-xs"
            width="48"
            height="48"
        />
    </div>

    {{-- Brand Text: SMP Muhammadiyah 7 Medan --}}
    @if($showText)
        <div class="flex flex-col text-left leading-none tracking-tight">
            <span class="text-sm sm:text-base font-black tracking-tight {{ $isWhite ? 'text-white' : 'text-darkgreen group-hover:text-forest' }} transition-colors drop-shadow-xs">
                SMP Muhammadiyah 7
            </span>
            <div class="flex items-center gap-1.5 mt-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full {{ $isWhite ? 'bg-mint' : 'bg-forest' }}"></span>
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider {{ $isWhite ? 'text-white/80' : 'text-forest' }}">
                    Medan <span class="sr-only">&bull; Kota Medan &bull; 10257325</span>
                </span>
            </div>
        </div>
    @endif
</a>
