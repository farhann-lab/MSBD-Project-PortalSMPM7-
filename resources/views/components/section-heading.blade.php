@props([
    'kicker' => null,
    'tag' => null,
    'title',
    'subtitle' => null,
    'description' => null,
    'align' => 'left',
    'level' => 'h2',
    'kickerVariant' => 'sage',
])

@php
    $badgeText = $tag ?? $kicker;
    $descText = $description ?? $subtitle;

    $alignClasses = [
        'left' => 'text-left items-start',
        'center' => 'text-center items-center mx-auto',
        'right' => 'text-right items-end ml-auto',
    ][$align] ?? 'text-left items-start';

    $descAlignClasses = [
        'left' => 'mr-auto',
        'center' => 'mx-auto',
        'right' => 'ml-auto',
    ][$align] ?? '';
@endphp

<div {{ $attributes->merge(['class' => "flex flex-col {$alignClasses} max-w-3xl"]) }}>
    @if($badgeText)
        <div class="mb-3">
            <x-badge :variant="$kickerVariant" size="md">
                {{ $badgeText }}
            </x-badge>
        </div>
    @endif

    @if($level === 'h1')
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-darkgreen leading-tight">
            {{ $title }}
        </h1>
    @elseif($level === 'h3')
        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-darkgreen leading-snug">
            {{ $title }}
        </h3>
    @else
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-darkgreen leading-snug">
            {{ $title }}
        </h2>
    @endif

    @if($descText || $slot->isNotEmpty())
        <p class="mt-3 sm:mt-4 text-base sm:text-lg text-muted-text leading-relaxed {{ $descAlignClasses }}">
            {{ $descText ?? $slot }}
        </p>
    @endif
</div>
