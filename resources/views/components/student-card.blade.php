@props([
    'name',
    'kelas',
])

@php
    // Generate gentle avatar initials
    $words = preg_split('/[\s,\.]+/', trim($name), -1, PREG_SPLIT_NO_EMPTY);
    $initials = '';
    if (count($words) >= 2) {
        $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
    } elseif (count($words) === 1) {
        $initials = mb_substr($words[0], 0, 2);
    }
    $initials = strtoupper($initials ?: 'SW');
@endphp

<article {{ $attributes->merge(['class' => 'flex items-center justify-between p-4 sm:p-5 rounded-2xl border border-border-soft bg-white transition-all duration-150 hover:shadow-xs hover:border-sage/50 gap-3']) }}>
    <div class="flex items-center gap-3.5 min-w-0">
        {{-- Student Initials Badge (DIR-8 Compliant: No photos) --}}
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-mint/30 border border-mint/50 flex items-center justify-center text-forest font-bold text-xs sm:text-sm shrink-0 select-none">
            {{ $initials }}
        </div>

        {{-- Student Name (DIR-8 Compliant: No NIS/NISN/NIK) --}}
        <div class="min-w-0">
            <h4 class="text-sm sm:text-base font-bold text-darkgreen truncate">
                {{ $name }}
            </h4>
            <span class="inline-flex items-center gap-1 text-xs text-olive font-medium mt-0.5">
                <x-icon name="academic-cap" class="w-3.5 h-3.5 text-sage shrink-0" />
                <span>Siswa Aktif</span>
            </span>
        </div>
    </div>

    {{-- Class Badge --}}
    <div class="shrink-0">
        <x-badge variant="sage" size="md">
            {{ $kelas }}
        </x-badge>
    </div>
</article>
