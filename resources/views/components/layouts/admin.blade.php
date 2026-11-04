@props([
    'title' => 'Dashboard Admin',
    'breadcrumbs' => [],
    'activeMenu' => 'dashboard',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-soft">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — Panel Admin SIAKAD SMP Muhammadiyah 7 Medan</title>
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
        href="#main-admin-content" 
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-forest focus:text-white focus:rounded-xl focus:shadow-xl focus:ring-2 focus:ring-mint"
    >
        Lewati ke Konten Utama
    </a>

    <div class="min-h-full flex flex-col lg:flex-row">
        
        <!-- Sidebar Shell Component -->
        <x-admin.sidebar :activeMenu="$activeMenu" />

        <!-- Main Wrapper (Offset for Desktop Fixed Sidebar) -->
        <div class="flex-1 lg:pl-72 flex flex-col min-h-screen">
            
            <!-- Topbar Header Component -->
            <x-admin.topbar :title="$title" :breadcrumbs="$breadcrumbs" />

            <!-- Main Content Area with Soft Background (#F7F9F6) -->
            <main id="main-admin-content" class="flex-1 p-4 sm:p-6 lg:p-8" tabindex="-1">
                
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

                {{ $slot }}
            </main>

            <!-- Dashboard Mini Footer -->
            <footer class="px-4 sm:px-6 lg:px-8 py-4 border-t border-border-soft bg-white/50 text-xs text-muted-text flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                    &copy; {{ date('Y') }} <strong>SIAKAD SMP Muhammadiyah 7 Medan</strong>. Hak Cipta Dilindungi.
                </div>
                <div class="flex items-center gap-4">
                    <span>Versi 1.0 (MVP)</span>
                    <span class="text-border-soft">|</span>
                    <a href="{{ route('home') }}" target="_blank" class="hover:text-forest transition-colors">Portal Sekolah</a>
                </div>
            </footer>

        </div>
    </div>

    <!-- Admin Interactive Logic (Mobile Drawer, Dropdowns, ARIA states) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            const openBtn = document.getElementById('admin-sidebar-open');
            const closeBtn = document.getElementById('admin-sidebar-close');

            function openSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Escape key closes drawer and dropdowns
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                    closeAllDropdowns();
                }
            });

            // Generic Dropdown Toggle Helper
            function setupDropdown(triggerId, menuId) {
                const trigger = document.getElementById(triggerId);
                const menu = document.getElementById(menuId);
                if (!trigger || !menu) return;

                trigger.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = !menu.classList.contains('hidden');
                    closeAllDropdowns();
                    if (!isOpen) {
                        menu.classList.remove('hidden');
                        trigger.setAttribute('aria-expanded', 'true');
                    }
                });
            }

            setupDropdown('admin-notif-btn', 'admin-notif-menu');
            setupDropdown('admin-profile-btn', 'admin-profile-menu');

            function closeAllDropdowns() {
                const notifMenu = document.getElementById('admin-notif-menu');
                const notifBtn = document.getElementById('admin-notif-btn');
                const profileMenu = document.getElementById('admin-profile-menu');
                const profileBtn = document.getElementById('admin-profile-btn');

                if (notifMenu) notifMenu.classList.add('hidden');
                if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
                if (profileMenu) profileMenu.classList.add('hidden');
                if (profileBtn) profileBtn.setAttribute('aria-expanded', 'false');
            }

            document.addEventListener('click', function (e) {
                if (!e.target.closest('#admin-notifications-dropdown') && !e.target.closest('#admin-profile-dropdown')) {
                    closeAllDropdowns();
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
