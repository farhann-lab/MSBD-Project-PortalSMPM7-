@props([
    'title' => null,
    'description' => null,
    'fullWidth' => false,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Title & Meta --}}
    <title>
        @if(isset($title) && !empty($title))
            {{ $title }} — {{ config('sekolah.nama', 'SMP Muhammadiyah 7 Medan') }}
        @elseif(View::hasSection('title'))
            @yield('title') — {{ config('sekolah.nama', 'SMP Muhammadiyah 7 Medan') }}
        @else
            {{ config('sekolah.nama', 'SMP Muhammadiyah 7 Medan') }} — Unggul, Cerdas, Berakhlak
        @endif
    </title>

    <meta name="description" content="{{ $description ?? View::yieldContent('description', config('sekolah.deskripsi', 'Portal Informasi Resmi SIAKAD SMP Muhammadiyah 7 Medan. Pusat informasi akademik, profil sekolah, direktori guru dan siswa, prestasi, dan kegiatan.')) }}">
    <meta name="theme-color" content="#344B39">

    {{-- Open Graph / Social Media Meta --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? View::yieldContent('title', config('sekolah.nama', 'SMP Muhammadiyah 7 Medan')) }}">
    <meta property="og:description" content="{{ $description ?? View::yieldContent('description', config('sekolah.deskripsi')) }}">
    <meta property="og:site_name" content="{{ config('sekolah.nama', 'SMP Muhammadiyah 7 Medan') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Google Fonts: Plus Jakarta Sans (Modern UI) & Playfair Display (Elegant Serif Headline) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,600;1,700&display=swap" rel="stylesheet">

    {{-- Vite Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-full flex flex-col font-sans antialiased text-darkgreen bg-soft selection:bg-mint selection:text-darkgreen">

    {{-- Accessibility: Skip-to-content Link --}}
    <a href="#main-content" 
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2.5 focus:bg-forest focus:text-white focus:rounded-xl focus:shadow-xl focus:outline-none focus:ring-2 focus:ring-sage font-medium text-sm transition-all duration-150">
        Lewati ke konten utama
    </a>

    {{-- Global Responsive Navbar --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-border-soft transition-shadow duration-200">
        <x-navbar />
    </header>

    {{-- Main Content Area --}}
    <main id="main-content" role="main" class="flex-1 flex flex-col focus:outline-none" tabindex="-1">
        @if(isset($slot) && $slot->isNotEmpty())
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

    {{-- Global 4-Column Footer --}}
    <x-footer />

    @stack('scripts')
</body>
</html>
