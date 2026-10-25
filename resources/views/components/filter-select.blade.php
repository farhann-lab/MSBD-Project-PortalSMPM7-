@props([
    'name',
    'label' => 'Filter',
    'options' => [],
    'selected' => '',
    'placeholder' => 'Semua',
    'id' => null,
    'icon' => 'funnel',
])

@php
    $selectId = $id ?? 'filter_' . $name;
@endphp

<div {{ $attributes->merge(['class' => 'relative min-w-[180px] sm:min-w-[200px]']) }}>
    <label for="{{ $selectId }}" class="sr-only">
        {{ $label }}
    </label>

    {{-- Prefix Icon --}}
    @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sage" aria-hidden="true">
            <x-icon :name="$icon" class="w-4 h-4" />
        </div>
    @endif

    {{-- Select Element --}}
    <select 
        name="{{ $name }}" 
        id="{{ $selectId }}"
        class="block w-full min-h-11 {{ $icon ? 'pl-9' : 'pl-3.5' }} pr-10 py-2.5 text-sm rounded-xl border border-border-soft bg-white text-darkgreen font-medium transition-colors focus:border-forest focus:outline-2 focus:outline-offset-0 focus:outline-forest cursor-pointer appearance-none"
    >
        @if($placeholder)
            <option value="" {{ empty($selected) ? 'selected' : '' }}>
                {{ $placeholder }}
            </option>
        @endif

        @foreach($options as $val => $lbl)
            @php
                $optVal = is_numeric($val) && !is_array($lbl) ? $lbl : $val;
                $optLabel = is_array($lbl) ? ($lbl['label'] ?? $lbl['value']) : $lbl;
                $isSelected = (string)$optVal === (string)$selected;
            @endphp
            <option value="{{ $optVal }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $optLabel }}
            </option>
        @endforeach
    </select>

    {{-- Custom Chevron Down --}}
    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-muted-text" aria-hidden="true">
        <x-icon name="chevron-down" class="w-4 h-4 text-sage" />
    </div>
</div>
