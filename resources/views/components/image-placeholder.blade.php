@props([
    'aspect' => 'video',
    'icon' => 'photo',
    'label' => 'Dokumentasi Sekolah',
    'class' => '',
])

@php
    $aspectClasses = [
        'video' => 'aspect-video',
        '16/9' => 'aspect-video',
        '4/3' => 'aspect-4/3',
        'square' => 'aspect-square',
        'portrait' => 'aspect-[3/4]',
        'auto' => '',
    ][$aspect] ?? 'aspect-video';
@endphp

<div {{ $attributes->merge(['class' => "relative flex flex-col items-center justify-center bg-linear-to-b from-soft to-border-soft/60 border border-border-soft overflow-hidden {$aspectClasses} {$class}"]) }} aria-hidden="true">
    <div class="flex flex-col items-center justify-center p-4 text-center">
        <div class="w-12 h-12 rounded-xl bg-mint/30 flex items-center justify-center text-sage mb-2 border border-mint/50">
            <x-icon :name="$icon" class="w-6 h-6" />
        </div>
        <p class="text-xs font-semibold text-muted-text line-clamp-1 max-w-[85%]">
            {{ $label }}
        </p>
        <span class="text-[10px] text-olive tracking-wide uppercase mt-0.5 font-medium">
            SMP Muhammadiyah 7 Medan
        </span>
    </div>
</div>
