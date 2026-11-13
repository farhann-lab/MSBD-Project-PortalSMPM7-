@props([
    'title' => 'Dashboard Admin',
    'breadcrumbs' => [],
])

<header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-border-soft/80 shadow-xs transition-all">
    <div class="px-4 sm:px-6 lg:px-8 h-18 sm:h-20 flex items-center justify-between gap-4">
        
        <!-- Left Section: Mobile Drawer Toggle & Modern Breadcrumbs / Titles -->
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <!-- Mobile Menu Toggle Button -->
            <button 
                type="button" 
                id="admin-sidebar-open" 
                class="lg:hidden w-10 h-10 rounded-xl bg-soft border border-border-soft flex items-center justify-center text-darkgreen hover:bg-forest/10 hover:text-forest focus:outline-hidden focus:ring-2 focus:ring-forest transition-colors shrink-0"
                aria-label="Buka Menu Navigasi"
            >
                <x-icon name="menu" class="w-5 h-5" />
            </button>

            <!-- Titles & Breadcrumbs -->
            <div class="min-w-0 flex flex-col justify-center">
                <nav class="hidden sm:flex items-center gap-1.5 text-[11px] font-medium text-muted-text mb-1" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center gap-1 text-muted-text hover:text-forest transition-colors">
                        <x-icon name="building-office" class="w-3.5 h-3.5 text-sage" />
                        <span>Admin</span>
                    </a>
                    @foreach($breadcrumbs as $bc)
                        <x-icon name="chevron-right" class="w-3 h-3 text-muted-text/40 shrink-0" />
                        @if(!empty($bc['url']) && !$loop->last)
                            <a href="{{ $bc['url'] }}" class="hover:text-forest transition-colors truncate max-w-[150px]">{{ $bc['label'] }}</a>
                        @else
                            <span class="text-forest font-semibold truncate max-w-[180px]">{{ $bc['label'] }}</span>
                        @endif
                    @endforeach
                    @if(empty($breadcrumbs))
                        <x-icon name="chevron-right" class="w-3 h-3 text-muted-text/40 shrink-0" />
                        <span class="text-forest font-semibold">Ikhtisar</span>
                    @endif
                </nav>

                <h1 class="text-lg sm:text-xl font-black text-darkgreen tracking-tight leading-none truncate">
                    {{ $title }}
                </h1>
            </div>
        </div>

        <!-- Right Section: Badges, Notifikasi & Profil Admin -->
        <div class="flex items-center gap-3 sm:gap-4 shrink-0">
            
            <!-- Academic & Server Metadata Block (Formal & Institutional) -->
            <div class="hidden md:flex items-center gap-3.5 pl-4 border-l border-border-soft">
                <div class="flex flex-col text-right leading-tight">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted-text">Tahun Ajaran</span>
                    <span class="text-xs font-black text-darkgreen mt-0.5">2026/2027 Semester 1</span>
                </div>
                
                <div class="h-6 w-px bg-border-soft/70"></div>

                <div class="flex flex-col text-left leading-tight">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-forest">Status Sistem</span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        <span class="text-xs font-semibold text-emerald-800">SIAKAD Aman</span>
                    </div>
                </div>
            </div>

            <div class="h-6 w-px bg-border-soft hidden md:block"></div>

            <!-- Notifications Dropdown -->
            <div class="relative" id="admin-notifications-dropdown">
                <button 
                    type="button" 
                    id="admin-notif-btn"
                    class="w-10 h-10 rounded-xl bg-soft/70 hover:bg-soft border border-border-soft flex items-center justify-center text-darkgreen/80 hover:text-darkgreen focus:outline-hidden focus:ring-2 focus:ring-forest transition-colors relative cursor-pointer"
                    aria-label="Pemberitahuan Sistem"
                    aria-expanded="false"
                >
                    <x-icon name="bell" class="w-4 h-4" />
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-amber-500 ring-2 ring-white"></span>
                </button>

                <!-- Notifications Flyout -->
                <div 
                    id="admin-notif-menu" 
                    class="hidden absolute right-0 mt-2.5 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-border-soft p-4 z-50 animate-in fade-in zoom-in-95 duration-150"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-forest"></span>
                            <span class="font-bold text-sm text-darkgreen">Pemberitahuan Sistem</span>
                        </div>
                        <span class="text-[11px] px-2 py-0.5 rounded-full bg-forest/10 text-forest font-bold">2 Baru</span>
                    </div>
                    <div class="py-2.5 space-y-2">
                        <div class="p-3 rounded-xl bg-soft/60 hover:bg-soft transition-colors flex items-start gap-3 text-xs border border-border-soft/40">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></span>
                            <div>
                                <p class="font-bold text-darkgreen">Tahun Ajaran 2026/2027 Aktif</p>
                                <p class="text-muted-text text-[11px] mt-0.5 leading-relaxed">Konfigurasi semester ganjil telah tersinkronisasi otomatis.</p>
                                <span class="text-[10px] text-muted-text/80 mt-1 block">1 jam lalu</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-xl bg-soft/60 hover:bg-soft transition-colors flex items-start gap-3 text-xs border border-border-soft/40">
                            <span class="w-2 h-2 rounded-full bg-amber-500 mt-1.5 shrink-0"></span>
                            <div>
                                <p class="font-bold text-darkgreen">Pembaruan Absensi Harian</p>
                                <p class="text-muted-text text-[11px] mt-0.5 leading-relaxed">10 Rombel siap dipantau kehadirannya hari ini.</p>
                                <span class="text-[10px] text-muted-text/80 mt-1 block">2 jam lalu</span>
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
                    class="flex items-center gap-2.5 p-1 pl-2 sm:pl-3 pr-2.5 rounded-2xl bg-white hover:bg-soft border border-border-soft focus:outline-hidden focus:ring-2 focus:ring-forest transition-all cursor-pointer shadow-2xs group"
                    aria-expanded="false"
                    aria-label="Menu Profil Pengguna"
                >
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-black text-darkgreen leading-tight group-hover:text-forest transition-colors">
                            {{ auth()->user()->username ?? 'Admin Utama' }}
                        </p>
                        <p class="text-[10px] text-forest font-bold uppercase tracking-wider leading-none mt-0.5">
                            Administrator
                        </p>
                    </div>

                    <div class="w-8 h-8 rounded-xl bg-forest text-white font-extrabold text-xs flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr(auth()->user()->username ?? 'AD', 0, 2)) }}
                    </div>

                    <x-icon name="chevron-down" class="w-3.5 h-3.5 text-muted-text hidden sm:block transition-transform duration-200" />
                </button>

                <!-- Profile Flyout Menu -->
                <div 
                    id="admin-profile-menu" 
                    class="hidden absolute right-0 mt-2.5 w-60 bg-white rounded-2xl shadow-xl border border-border-soft p-2 z-50 animate-in fade-in zoom-in-95 duration-150"
                >
                    <div class="px-3 py-2.5 border-b border-border-soft mb-1 sm:hidden">
                        <p class="text-xs font-bold text-darkgreen">{{ auth()->user()->username ?? 'Admin Utama' }}</p>
                        <p class="text-[10px] text-forest font-bold uppercase">Administrator SIAKAD</p>
                    </div>

                    <a 
                        href="{{ route('password.change') }}" 
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-darkgreen hover:bg-soft hover:text-forest transition-colors"
                    >
                        <x-icon name="key" class="w-4 h-4 text-forest" />
                        <span>Ubah Kata Sandi</span>
                    </a>

                    <a 
                        href="{{ route('home') }}" 
                        target="_blank" 
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-darkgreen hover:bg-soft hover:text-forest transition-colors"
                    >
                        <x-icon name="globe" class="w-4 h-4 text-forest" />
                        <span>Buka Website Sekolah</span>
                    </a>

                    <div class="border-t border-border-soft my-1"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors cursor-pointer text-left"
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Notif Toggle
        const notifBtn = document.getElementById('admin-notif-btn');
        const notifMenu = document.getElementById('admin-notif-menu');
        if (notifBtn && notifMenu) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = notifMenu.classList.contains('hidden');
                notifMenu.classList.toggle('hidden');
                notifBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                const profileMenu = document.getElementById('admin-profile-menu');
                if (profileMenu) profileMenu.classList.add('hidden');
            });
        }

        // Profile Toggle
        const profileBtn = document.getElementById('admin-profile-btn');
        const profileMenu = document.getElementById('admin-profile-menu');
        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = profileMenu.classList.contains('hidden');
                profileMenu.classList.toggle('hidden');
                profileBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                if (notifMenu) notifMenu.classList.add('hidden');
            });
        }

        // Close on outside click
        document.addEventListener('click', () => {
            if (notifMenu) notifMenu.classList.add('hidden');
            if (profileMenu) profileMenu.classList.add('hidden');
        });
    });
</script>
