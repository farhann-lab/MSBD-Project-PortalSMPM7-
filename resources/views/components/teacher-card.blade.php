@props([
    'name',
    'mapel' => null,
    'jabatan' => null,
])

@php
    // Get initials from teacher's name (e.g. "M. Reza Akbar, S.Pd." -> "MR")
    $words = preg_split('/[\s,\.]+/', trim($name), -1, PREG_SPLIT_NO_EMPTY);
    $initials = '';
    if (count($words) >= 2) {
        $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
    } elseif (count($words) === 1) {
        $initials = mb_substr($words[0], 0, 2);
    }
    $initials = strtoupper($initials ?: 'GP');
@endphp

<article {{ $attributes->merge(['class' => 'flex flex-col justify-between p-5 sm:p-6 rounded-2xl border border-border-soft bg-white transition-all duration-200 hover:shadow-md hover:border-sage/50']) }}>
    <div>
        {{-- Header: Professional Avatar Initials & Jabatan Badge --}}
        <div class="flex items-start justify-between gap-3 mb-4">
            <div class="w-12 h-12 rounded-xl bg-forest/10 border border-forest/15 flex items-center justify-center text-forest font-bold text-base shrink-0 select-none">
                {{ $initials }}
            </div>

            @if($jabatan)
                <x-badge variant="forest" size="sm">
                    {{ $jabatan }}
                </x-badge>
            @endif
        </div>

        {{-- Teacher Name (High-contrast Dark Green) --}}
        <h3 class="text-base sm:text-lg font-bold text-darkgreen leading-snug mb-2">
            {{ $name }}
        </h3>

        {{-- Mata Pelajaran (Subject Badge) --}}
        @if($mapel)
            <div class="mt-2 flex flex-wrap gap-1.5">
                <x-badge variant="sage" size="sm">
                    <x-icon name="book-open" class="w-3 h-3 text-forest mr-1 inline" />
                    {{ $mapel }}
                </x-badge>
            </div>
        @else
            <p class="text-xs text-muted-text mt-1 italic">
                Tenaga Pendidik
            </p>
        @endif
    </div>

    {{-- Subtle School Status Pill (DIR-8 Compliant: No NIP/NIK/Phone) --}}
    <div class="mt-5 pt-3.5 border-t border-border-soft/60 flex items-center justify-between text-xs text-muted-text">
        <span class="inline-flex items-center gap-1 text-olive font-medium">
            <x-icon name="check-circle" class="w-3.5 h-3.5 text-sage" />
            <span>Guru SMPM 7</span>
        </span>
        <span class="text-[11px] text-muted-text">Aktif</span>
    </div>
</article>
