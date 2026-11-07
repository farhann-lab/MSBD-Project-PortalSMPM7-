@props([
    'title' => 'Dashboard Admin',
    'breadcrumbs' => [],
])

<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-border-soft transition-all">
    <div class="px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
        
        <!-- Left Section: Mobile Drawer Toggle & Page Titles -->
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <!-- Mobile Menu Toggle Button -->
            <button 
                type="button" 
                id="admin-sidebar-open" 
                class="lg:hidden p-2 rounded-xl text-darkgreen/70 hover:text-darkgreen hover:bg-soft focus:outline-hidden focus:ring-2 focus:ring-forest shrink-0"
                aria-label="Buka Menu Navigasi"
            >
                <x-icon name="menu" class="w-6 h-6" />
            </button>

            <!-- Titles & Breadcrumbs -->
            <div class="min-w-0">
                <nav class="hidden sm:flex items-center gap-1.5 text-xs text-muted-text mb-0.5" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.admin') }}" class="hover:text-forest transition-colors">Admin</a>
                    @foreach($breadcrumbs as $bc)
                        <x-icon name="chevron-right" class="w-3 h-3 text-muted-text/50 shrink-0" />
                        @if(!empty($bc['url']) && !$loop->last)
                            <a href="{{ $bc['url'] }}" class="hover:text-forest transition-colors">{{ $bc['label'] }}</a>
                        @else
                            <span class="text-darkgreen font-semibold truncate">{{ $bc['label'] }}</span>
                        @endif
                    @endforeach
                    @if(empty($breadcrumbs))
                        <x-icon name="chevron-right" class="w-3 h-3 text-muted-text/50 shrink-0" />
                        <span class="text-darkgreen font-semibold">Ikhtisar</span>
                    @endif
                </nav>

                <h1 class="text-lg sm:text-xl font-bold text-darkgreen tracking-tight truncate">
                    {{ $title }}
                </h1>
            </div>
        </div>

        <!-- Right Section: Status Context, Notifications & Profile Menu -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            
            <!-- Academic Period Badge (DESIGN.md: ditampilkan sebagai nama periode, mis. 2026/2027 Semester 1) -->
            <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-soft border border-border-soft text-xs text-darkgreen">
                <span class="w-2 h-2 rounded-full {{ ($globalPeriodeAktif['is_aktif'] ?? true) ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                <span class="font-medium">{{ $globalPeriodeAktif['label_topbar'] ?? '2026/2027 Semester 1' }}</span>
                <span class="text-muted-text">|</span>
                <span class="text-forest font-semibold">{{ ($globalPeriodeAktif['is_aktif'] ?? true) ? 'Aktif' : 'Arsip' }}</span>
            </div>

            <!-- Server / Security Status (Micro Badge) -->
            <div class="hidden xl:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-medium">
                <x-icon name="shield-check" class="w-4 h-4 text-emerald-600" />
                <span>SIAKAD Aman</span>
            </div>

            <!-- Notifications Shortcut (Interactive Dropdown Toggle) -->
            <div class="relative" id="admin-notifications-dropdown">
                <button 
                    type="button" 
                    id="admin-notif-btn"
                    class="p-2.5 rounded-xl text-darkgreen/75 hover:text-darkgreen hover:bg-soft focus:outline-hidden focus:ring-2 focus:ring-forest transition-colors relative"
                    aria-label="Pemberitahuan Sistem"
                    aria-expanded="false"
                >
                    <x-icon name="bell" class="w-5 h-5" />
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-amber-500 ring-2 ring-white"></span>
                </button>

                <!-- Notifications Flyout -->
                <div 
                    id="admin-notif-menu" 
                    class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-border-soft p-4 z-50 animate-in fade-in zoom-in-95 duration-150"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                        <span class="font-bold text-sm text-darkgreen">Pemberitahuan Sistem</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-soft text-forest font-semibold">2 Baru</span>
                    </div>
                    <div class="py-2 space-y-2">
                        <div class="p-2.5 rounded-xl bg-soft/60 hover:bg-soft transition-colors flex items-start gap-3 text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></span>
                            <div>
                                <p class="font-semibold text-darkgreen">Tahun Ajaran 2026/2027 Aktif</p>
                                <p class="text-muted-text text-[11px] mt-0.5">Konfigurasi semester ganjil telah tersinkronisasi.</p>
                            </div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-soft/60 hover:bg-soft transition-colors flex items-start gap-3 text-xs">
                            <span class="w-2 h-2 rounded-full bg-amber-500 mt-1.5 shrink-0"></span>
                            <div>
                                <p class="font-semibold text-darkgreen">Pembaruan Absensi Harian</p>
                                <p class="text-muted-text text-[11px] mt-0.5">10 Rombel siap dipantau hari ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" id="admin-profile-dropdown">
                <button 
                    type="button" 
                    id="admin-profile-btn"
                    class="flex items-center gap-2.5 p-1.5 pl-2 sm:pl-3 rounded-2xl border border-border-soft hover:bg-soft focus:outline-hidden focus:ring-2 focus:ring-forest transition-colors"
                    aria-expanded="false"
                    aria-label="Menu Profil Pengguna"
                >
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-bold text-darkgreen leading-tight">
                            {{ auth()->user()->username ?? 'Admin Utama' }}
                        </p>
                        <p class="text-[10px] text-muted-text font-medium leading-none">
                            Administrator
                        </p>
                    </div>

                    <div class="w-8 h-8 rounded-xl bg-forest text-white font-bold text-xs flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr(auth()->user()->username ?? 'AD', 0, 2)) }}
                    </div>

                    <x-icon name="chevron-down" class="w-4 h-4 text-muted-text hidden sm:block" />
                </button>

                <!-- Profile Flyout Menu -->
                <div 
                    id="admin-profile-menu" 
                    class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-border-soft p-2 z-50 animate-in fade-in zoom-in-95 duration-150"
                >
                    <div class="px-3 py-2 border-b border-border-soft mb-1 sm:hidden">
                        <p class="text-xs font-bold text-darkgreen">{{ auth()->user()->username ?? 'Admin Utama' }}</p>
                        <p class="text-[10px] text-muted-text">Administrator SIAKAD</p>
                    </div>

                    <a 
                        href="{{ route('password.change') }}" 
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-darkgreen hover:bg-soft hover:text-forest transition-colors"
                    >
                        <x-icon name="key" class="w-4 h-4 text-sage" />
                        <span>Ubah Kata Sandi</span>
                    </a>

                    <a 
                        href="{{ route('home') }}" 
                        target="_blank" 
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-darkgreen hover:bg-soft hover:text-forest transition-colors"
                    >
                        <x-icon name="globe" class="w-4 h-4 text-sage" />
                        <span>Buka Website Sekolah</span>
                    </a>

                    <div class="border-t border-border-soft my-1"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-red-600 hover:bg-red-50 transition-colors"
                        >
                            <x-icon name="logout" class="w-4 h-4 text-red-500" />
                            <span>Keluar dari SIAKAD</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</header>
