@props([
    'name' => 'q',
    'value' => '',
    'placeholder' => 'Cari berdasarkan nama...',
    'label' => 'Pencarian',
    'id' => null,
])

@php
    $inputId = $id ?? 'search_' . $name;
@endphp

<div {{ $attributes->merge(['class' => 'relative flex-1']) }}>
    <label for="{{ $inputId }}" class="sr-only">
        {{ $label }}
    </label>

    {{-- Search Icon Prefix --}}
    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sage" aria-hidden="true">
        <x-icon name="search" class="w-5 h-5" />
    </div>

    {{-- Input Field --}}
    <input 
        type="search" 
        name="{{ $name }}" 
        id="{{ $inputId }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}"
        class="block w-full min-h-11 pl-10.5 pr-4 py-2.5 text-sm rounded-xl border border-border-soft bg-white text-darkgreen placeholder:text-muted-text/80 transition-colors focus:border-forest focus:outline-2 focus:outline-offset-0 focus:outline-forest"
    />
</div>
