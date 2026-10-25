@props([
    'title' => 'Data tidak ditemukan',
    'message' => 'Maaf, tidak ada data yang cocok dengan kriteria pencarian atau filter yang Anda pilih.',
    'description' => null,
    'icon' => 'search',
    'resetUrl' => null,
    'resetLabel' => 'Reset Filter / Pencarian',
])

@php
    $desc = $description ?? $message;
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center p-8 sm:p-12 rounded-2xl border border-dashed border-border-soft bg-white/60 my-6']) }}>
    {{-- Decorative Icon Badge --}}
    <div class="w-16 h-16 rounded-2xl bg-mint/30 border border-mint/60 flex items-center justify-center text-forest mb-4">
        <x-icon :name="$icon" class="w-8 h-8 text-forest" />
    </div>

    {{-- Title --}}
    <h3 class="text-lg sm:text-xl font-bold text-darkgreen mb-2">
        {{ $title }}
    </h3>

    {{-- Description --}}
    <p class="text-sm text-muted-text max-w-md leading-relaxed mb-6">
        {{ $desc }}
    </p>

    {{-- Optional Action / Reset Button --}}
    @if($resetUrl)
        <div>
            <x-button :href="$resetUrl" variant="secondary" size="md" icon="arrow-path" iconPosition="left">
                {{ $resetLabel }}
            </x-button>
        </div>
    @elseif($slot->isNotEmpty())
        <div class="flex flex-wrap items-center justify-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
