@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'badgeVariant' => 'forest',
    'badgeIcon' => null,
    'extraBadges' => [],
    'image' => asset('images/school/gedung.jpg'),
    'imageAlt' => 'Gedung SMP Muhammadiyah 7 Medan',
    'imagePosition' => 'center_90%',
    'schoolTag' => 'SMP Muhammadiyah 7 Medan',
])

@php
    $posStyle = str_replace('_', ' ', $imagePosition);
@endphp

<section
    class="relative w-full overflow-hidden bg-darkgreen text-white border-b border-border-soft/60"
    style="height: clamp(420px, 56vw, 580px);"
    aria-label="{{ strip_tags($title) }}"
>
    {{-- Background Image --}}
    <img
        src="{{ $image }}"
        alt="{{ $imageAlt }}"
        class="w-full h-full object-cover"
        style="object-position: {{ $posStyle }};"
        loading="eager"
        fetchpriority="high"
    />

    {{-- Dark & Soft Gradient Overlays (Identical to Home Hero Section) --}}
    <div class="absolute inset-0 bg-gradient-to-r from-darkgreen/90 via-darkgreen/65 to-darkgreen/30 pointer-events-none"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-darkgreen/90 via-darkgreen/30 to-transparent pointer-events-none"></div>

    {{-- Text & Content Overlay (Harmonious & Modern Layout) --}}
    <div class="absolute inset-0 flex items-center">
        <div class="w-full max-w-7xl mx-auto px-6 sm:px-10 lg:px-14 pb-14 sm:pb-16">
            <div class="max-w-2xl lg:max-w-3xl hero-text-content">
                {{-- Badges row --}}
                <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 mb-3 sm:mb-4">

                    @if($badge)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-mint/20 backdrop-blur-md border border-mint/35 text-mint text-xs font-semibold tracking-wide shadow-xs">
                            @if($badgeIcon)
                                <x-icon :name="$badgeIcon" class="w-3.5 h-3.5" />
                            @endif
                            {{ $badge }}
                        </span>
                    @endif

                    @foreach($extraBadges as $extra)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/10 backdrop-blur-md text-white/90 border border-white/20">
                            {{ $extra }}
                        </span>
                    @endforeach
                </div>

                {{-- Page Title in Refined Modern-Editorial Serif --}}
                <h1 class="font-serif text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-[1.2] tracking-tight mb-3 sm:mb-4 drop-shadow-md">
                    {!! $title !!}
                </h1>

                {{-- Subtitle / Description --}}
                @if($subtitle)
                    <p class="text-sm sm:text-base lg:text-lg text-white/85 leading-relaxed font-normal max-w-2xl drop-shadow-xs">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Trust Signals Strip at Bottom (Identical to Home Hero) --}}
    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-darkgreen/95 via-forest/90 to-darkgreen/95 backdrop-blur-md border-t border-white/15 hidden sm:block z-10 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 lg:px-14 py-3 flex items-center justify-between gap-6">
            <div class="flex items-center gap-2.5 text-white/90">
                <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                    <x-icon name="building-office" class="w-3.5 h-3.5 text-mint" />
                </div>
                <span class="text-xs font-semibold">Gedung 3 Lantai</span>
            </div>
            <div class="w-px h-4 bg-white/20"></div>
            <div class="flex items-center gap-2.5 text-white/90">
                <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                    <x-icon name="book-open" class="w-3.5 h-3.5 text-sage" />
                </div>
                <span class="text-xs font-semibold">Kurikulum Merdeka</span>
            </div>
            <div class="w-px h-4 bg-white/20"></div>
            <div class="flex items-center gap-2.5 text-white/90">
                <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                    <x-icon name="trophy" class="w-3.5 h-3.5 text-mint" />
                </div>
                <span class="text-xs font-semibold">Prestasi Teruji</span>
            </div>
            <div class="w-px h-4 bg-white/20"></div>
            <div class="flex items-center gap-2.5 text-white/90">
                <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                    <x-icon name="users" class="w-3.5 h-3.5 text-sage" />
                </div>
                <span class="text-xs font-semibold">{{ config('sekolah.statistik.siswa', '500+') }} Siswa Aktif</span>
            </div>
        </div>
    </div>
</section>

