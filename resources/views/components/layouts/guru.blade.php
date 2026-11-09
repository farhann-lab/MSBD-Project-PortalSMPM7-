@props([
    'title' => 'Dashboard Guru',
    'breadcrumbs' => [],
    'activeMenu' => 'dashboard',
    'isWaliKelas' => true,
    'kelasPerwalian' => 'VIII-B',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-soft">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — Portal Guru SIAKAD SMP Muhammadiyah 7 Medan</title>
    <meta name="theme-color" content="#344B39">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-full font-sans antialiased text-darkgreen bg-soft selection:bg-mint selection:text-darkgreen">

    <!-- Accessibility: Skip to Main Content -->
    <a 
        href="#main-guru-content" 
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-forest focus:text-white focus:rounded-xl focus:shadow-xl focus:ring-2 focus:ring-mint"
    >
        Lewati ke Konten Utama
    </a>

    <div class="min-h-full flex flex-col lg:flex-row">
        
        <!-- Sidebar Shell Component Guru -->
        <x-guru.sidebar 
            :activeMenu="$activeMenu" 
            :isWaliKelas="$isWaliKelas" 
            :kelasPerwalian="$kelasPerwalian" 
        />

        <!-- Main Wrapper (Offset for Desktop Fixed Sidebar) -->
        <div class="flex-1 lg:pl-72 flex flex-col min-h-screen">
            
            <!-- Topbar Header Component Guru -->
            <x-guru.topbar 
                :title="$title" 
                :breadcrumbs="$breadcrumbs" 
                :isWaliKelas="$isWaliKelas" 
                :kelasPerwalian="$kelasPerwalian" 
            />

            <!-- Main Content Area with Soft Background (#F5F8F5 / #F7F9F6) -->
            <main id="main-guru-content" class="flex-1 p-4 sm:p-6 lg:p-8" tabindex="-1">
                
                {{-- Flash Success Message --}}
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm shadow-xs animate-in fade-in" role="alert">
                        <div class="flex items-center gap-3">
                            <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 shrink-0" />
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="p-1 text-emerald-600 hover:text-emerald-900 rounded-lg">
                            <x-icon name="x-mark" class="w-4 h-4" />
                        </button>
                    </div>
                @endif

                {{-- Flash Error Message --}}
                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between text-sm shadow-xs animate-in fade-in" role="alert">
                        <div class="flex items-center gap-3">
                            <x-icon name="exclamation-circle" class="w-5 h-5 text-red-600 shrink-0" />
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="p-1 text-red-600 hover:text-red-900 rounded-lg">
                            <x-icon name="x-mark" class="w-4 h-4" />
                        </button>
                    </div>
                @endif

                <!-- Dynamic Content Slot -->
                {{ $slot }}
            </main>

            <!-- Dashboard Footer -->
            <footer class="mt-auto px-6 py-4 border-t border-border-soft bg-white/60 text-xs text-muted-text flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                    <span class="font-semibold text-darkgreen">SIAKAD Guru</span> — SMP Muhammadiyah 7 Medan &copy; {{ date('Y') }}
                </div>
                <div class="flex items-center gap-4 text-[11px]">
                    <a href="http://localhost:8080/rkm" target="_blank" rel="noopener noreferrer" class="hover:text-forest transition-colors flex items-center gap-1">
                        <span>Aplikasi RKM</span>
                        <x-icon name="external-link" class="w-3 h-3" />
                    </a>
                    <span class="text-border-soft">|</span>
                    <span>Tahun Ajaran 2026/2027 Semester Ganjil</span>
                </div>
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
