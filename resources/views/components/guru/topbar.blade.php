@props([
    'title' => 'Dashboard Guru',
    'breadcrumbs' => [],
    'isWaliKelas' => true,
    'kelasPerwalian' => 'VIII-B',
])

<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-border-soft transition-all">
    <div class="px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
        
        <!-- Left Section: Mobile Drawer Toggle & Page Titles -->
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <!-- Mobile Menu Toggle Button -->
            <button 
                type="button" 
                id="guru-sidebar-open" 
                class="lg:hidden p-2 rounded-xl text-darkgreen/70 hover:text-darkgreen hover:bg-soft focus:outline-hidden focus:ring-2 focus:ring-forest shrink-0"
                aria-label="Buka Menu Navigasi Guru"
            >
                <x-icon name="menu" class="w-6 h-6" />
            </button>

            <!-- Titles & Breadcrumbs -->
            <div class="min-w-0">
                <nav class="hidden sm:flex items-center gap-1.5 text-xs text-muted-text mb-0.5" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.guru') }}" class="hover:text-forest transition-colors">Portal Guru</a>
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

        <!-- Right Section: Periode, Status Wali, Notifikasi & Profil Guru -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            
            <!-- Academic Period Badge (DESIGN.md: 2026/2027 Semester 1) -->
            <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-soft border border-border-soft text-xs text-darkgreen">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="font-medium">2026/2027 Semester 1</span>
                <span class="text-muted-text">|</span>
                <span class="text-forest font-semibold">Aktif</span>
            </div>

            <!-- Role Badge / Wali Kelas status -->
            @if($isWaliKelas)
                <div class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold">
                    <x-icon name="user-group" class="w-3.5 h-3.5 text-emerald-600" />
                    <span>Wali Kelas {{ $kelasPerwalian }}</span>
                </div>
            @endif

            <!-- Notifications Shortcut -->
            <div class="relative" id="guru-notifications-dropdown">
                <button 
                    type="button" 
                    id="guru-notif-btn"
                    class="p-2.5 rounded-xl text-darkgreen/75 hover:text-darkgreen hover:bg-soft focus:outline-hidden focus:ring-2 focus:ring-forest transition-colors relative"
                    aria-label="Pemberitahuan Akademik"
                    aria-expanded="false"
                >
                    <x-icon name="bell" class="w-5 h-5" />
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-amber-500 ring-2 ring-white"></span>
                </button>

                <!-- Notifications Flyout -->
                <div 
                    id="guru-notif-menu" 
                    class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-border-soft p-4 z-50 animate-in fade-in zoom-in-95 duration-150"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-darkgreen">Pemberitahuan Guru</h2>
                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">2 Baru</span>
                        </div>
                    </div>

                    <div class="py-3 space-y-3">
                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-soft/60 hover:bg-soft transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                <x-icon name="clipboard-document-check" class="w-4 h-4" />
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-darkgreen">Absensi Kelas VIII-A Belum Lengkap</p>
                                <p class="text-[11px] text-muted-text mt-0.5">Pertemuan Jam Ke-2 Matematika menunggu submit absensi.</p>
                                <span class="text-[10px] text-muted-text font-medium mt-1 inline-block">10 menit yang lalu</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-soft/60 hover:bg-soft transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <x-icon name="calendar" class="w-4 h-4" />
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-darkgreen">Rapat Dewan Guru & Kurikulum</p>
                                <p class="text-[11px] text-muted-text mt-0.5">Sabtu, Pukul 10.00 WIB di Ruang Guru Utama.</p>
                                <span class="text-[10px] text-muted-text font-medium mt-1 inline-block">Kemarin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Menu Dropdown -->
            <div class="relative" id="guru-profile-dropdown">
                <button 
                    type="button" 
                    id="guru-profile-btn"
                    class="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-2 rounded-xl hover:bg-soft focus:outline-hidden focus:ring-2 focus:ring-forest transition-colors cursor-pointer"
                    aria-label="Menu Profil Guru"
                    aria-expanded="false"
                >
                    <div class="w-9 h-9 rounded-xl bg-forest text-white font-bold flex items-center justify-center text-xs shadow-xs">
                        {{ strtoupper(substr(auth()->user()->username ?? 'Guru', 0, 2)) }}
                    </div>
                    <div class="hidden sm:flex flex-col text-left">
                        <span class="text-xs font-bold text-darkgreen leading-tight truncate max-w-[120px]">
                            {{ auth()->user()->username ?? 'Budi Santoso, S.Pd.' }}
                        </span>
                        <span class="text-[10px] text-muted-text font-medium">Guru Pengampu</span>
                    </div>
                    <x-icon name="chevron-down" class="w-4 h-4 text-muted-text hidden sm:block" />
                </button>

                <!-- Profile Menu Flyout -->
                <div 
                    id="guru-profile-menu" 
                    class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-border-soft p-2 z-50 animate-in fade-in zoom-in-95 duration-150"
                >
                    <div class="px-3 py-2.5 border-b border-border-soft mb-1">
                        <p class="text-xs font-bold text-darkgreen">{{ auth()->user()->username ?? 'Budi Santoso, S.Pd.' }}</p>
                        <p class="text-[11px] text-muted-text mt-0.5">NIP: 198503152010011012</p>
                        @if($isWaliKelas)
                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Wali Kelas {{ $kelasPerwalian }}</span>
                        @endif
                    </div>

                    <a href="{{ route('password.change') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-darkgreen hover:bg-soft transition-colors">
                        <x-icon name="key" class="w-4 h-4 text-muted-text" />
                        <span>Ubah Password</span>
                    </a>

                    <a href="http://localhost:8080/rkm" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium text-darkgreen hover:bg-soft transition-colors">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="arrow-top-right-on-square" class="w-4 h-4 text-muted-text" />
                            <span>Buka Aplikasi RKM</span>
                        </div>
                        <span class="text-[10px] text-emerald-600 font-bold">Eksternal</span>
                    </a>

                    <div class="border-t border-border-soft my-1"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors cursor-pointer text-left">
                            <x-icon name="arrow-right-on-rectangle" class="w-4 h-4 text-red-500" />
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
        // Toggle Notifications Dropdown
        const notifBtn = document.getElementById('guru-notif-btn');
        const notifMenu = document.getElementById('guru-notif-menu');
        if (notifBtn && notifMenu) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = notifMenu.classList.contains('hidden');
                notifMenu.classList.toggle('hidden');
                notifBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                const profileMenu = document.getElementById('guru-profile-menu');
                if (profileMenu) profileMenu.classList.add('hidden');
            });
        }

        // Toggle Profile Dropdown
        const profileBtn = document.getElementById('guru-profile-btn');
        const profileMenu = document.getElementById('guru-profile-menu');
        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = profileMenu.classList.contains('hidden');
                profileMenu.classList.toggle('hidden');
                profileBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                if (notifMenu) notifMenu.classList.add('hidden');
            });
        }

        // Close dropdowns on outside click
        document.addEventListener('click', () => {
            if (notifMenu) notifMenu.classList.add('hidden');
            if (profileMenu) profileMenu.classList.add('hidden');
        });
    });
</script>
