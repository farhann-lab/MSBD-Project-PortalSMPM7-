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
                    @if($schoolTag)
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 text-white text-xs font-semibold tracking-wide shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-mint animate-pulse"></span>
                            {{ $schoolTag }}
                        </span>
                    @endif

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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-mint"><path fill-rule="evenodd" d="M4 2a2 2 0 0 0-2 2v11a3 3 0 1 0 6 0V4a2 2 0 0 0-2-2H4Zm1 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm5-1.757-4-4V4h2v10.243l2 2Zm0 0 .88.88A3 3 0 0 0 16 15V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v9.243l2 2ZM15 15a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs font-semibold">Gedung 3 Lantai</span>
            </div>
            <div class="w-px h-4 bg-white/20"></div>
            <div class="flex items-center gap-2.5 text-white/90">
                <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-sage"><path d="M10.75 16.82A7.462 7.462 0 0 1 15 15.5c.71 0 1.396.098 2.046.282A.75.75 0 0 0 18 15.06v-11a.75.75 0 0 0-.546-.721A9.006 9.006 0 0 0 15 3a8.963 8.963 0 0 0-4.25 1.065V16.82ZM9.25 4.065A8.963 8.963 0 0 0 5 3c-.85 0-1.673.118-2.454.339A.75.75 0 0 0 2 4.06v11a.75.75 0 0 0 .954.721A7.506 7.506 0 0 1 5 15.5c1.579 0 3.042.487 4.25 1.32V4.065Z"/></svg>
                </div>
                <span class="text-xs font-semibold">Kurikulum Merdeka</span>
            </div>
            <div class="w-px h-4 bg-white/20"></div>
            <div class="flex items-center gap-2.5 text-white/90">
                <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-mint"><path fill-rule="evenodd" d="M10 1c-1.828 0-3.623.149-5.371.435a.75.75 0 0 0-.629.74v.387c-.827.157-1.642.345-2.445.564a.75.75 0 0 0-.552.698 5 5 0 0 0 4.503 5.152 6 6 0 0 0 2.946 1.822A6.451 6.451 0 0 1 7.768 13H7.5A1.5 1.5 0 0 0 6 14.5V17h-.75C4.56 17 4 17.56 4 18.25v.75h12v-.75c0-.69-.56-1.25-1.25-1.25H14v-2.5a1.5 1.5 0 0 0-1.5-1.5h-.268a6.453 6.453 0 0 1-1.647-2.802 6 6 0 0 0 2.946-1.822 5 5 0 0 0 4.503-5.152.75.75 0 0 0-.552-.698A31.804 31.804 0 0 0 15.75 1.56v-.387a.75.75 0 0 0-.629-.74A33.227 33.227 0 0 0 10 1Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs font-semibold">Prestasi Teruji</span>
            </div>
            <div class="w-px h-4 bg-white/20"></div>
            <div class="flex items-center gap-2.5 text-white/90">
                <div class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-sage"><path d="M7 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM14.5 9a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 17a9.953 9.953 0 0 1-5.385-1.572ZM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 0 0-1.588-3.755 4.502 4.502 0 0 1 5.874 2.636.818.818 0 0 1-.36.98A7.465 7.465 0 0 1 14.5 16Z"/></svg>
                </div>
                <span class="text-xs font-semibold">{{ config('sekolah.statistik.siswa', '500+') }} Siswa Aktif</span>
            </div>
        </div>
    </div>
</section>

