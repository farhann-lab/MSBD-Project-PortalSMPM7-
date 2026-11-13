@props([
    'title' => 'Dashboard Guru',
    'breadcrumbs' => [],
    'isWaliKelas' => true,
    'kelasPerwalian' => 'VIII-B',
])

<header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-border-soft/80 shadow-xs transition-all">
    <div class="px-4 sm:px-6 lg:px-8 h-18 sm:h-20 flex items-center justify-between gap-4">
        
        <!-- Left Section: Mobile Drawer Toggle & Modern Breadcrumbs / Titles -->
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <!-- Mobile Menu Toggle Button -->
            <button 
                type="button" 
                id="guru-sidebar-open" 
                class="lg:hidden w-10 h-10 rounded-xl bg-soft border border-border-soft flex items-center justify-center text-darkgreen hover:bg-forest/10 hover:text-forest focus:outline-hidden focus:ring-2 focus:ring-forest transition-colors shrink-0"
                aria-label="Buka Menu Navigasi Guru"
            >
                <x-icon name="menu" class="w-5 h-5" />
            </button>

            <!-- Titles & Breadcrumbs -->
            <div class="min-w-0 flex flex-col justify-center">
                <nav class="hidden sm:flex items-center gap-1.5 text-[11px] font-medium text-muted-text mb-1" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.guru') }}" class="inline-flex items-center gap-1 text-muted-text hover:text-forest transition-colors">
                        <x-icon name="academic-cap" class="w-3.5 h-3.5 text-sage" />
                        <span>Portal Guru</span>
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

        <!-- Right Section: Periode, Status Wali, Notifikasi & Profil Guru -->
        <div class="flex items-center gap-3 sm:gap-4 shrink-0">
            
            <!-- Academic & Role Metadata Block (Formal & Institutional) -->
            <div class="hidden md:flex items-center gap-3.5 pl-4 border-l border-border-soft">
                <div class="flex flex-col text-right leading-tight">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted-text">Tahun Ajaran</span>
                    <span class="text-xs font-black text-darkgreen mt-0.5">2026/2027 Semester 1</span>
                </div>

                @if($isWaliKelas)
                    <div class="h-6 w-px bg-border-soft/70"></div>

                    <div class="flex flex-col text-left leading-tight">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-forest">Penugasan</span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-forest"></span>
                            <span class="text-xs font-bold text-darkgreen">Wali Kelas {{ $kelasPerwalian }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="h-6 w-px bg-border-soft hidden md:block"></div>

            <!-- Notifications Shortcut -->
            <div class="relative" id="guru-notifications-dropdown">
                <button 
                    type="button" 
                    id="guru-notif-btn"
                    class="w-10 h-10 rounded-xl bg-soft/70 hover:bg-soft border border-border-soft flex items-center justify-center text-darkgreen/80 hover:text-darkgreen focus:outline-hidden focus:ring-2 focus:ring-forest transition-colors relative cursor-pointer"
                    aria-label="Pemberitahuan Guru"
                    aria-expanded="false"
                >
                    <x-icon name="bell" class="w-4 h-4" />
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-amber-500 ring-2 ring-white"></span>
                </button>

                <!-- Notifications Flyout -->
                <div 
                    id="guru-notif-menu" 
                    class="hidden absolute right-0 mt-2.5 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-border-soft p-4 z-50 animate-in fade-in zoom-in-95 duration-150"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-forest"></span>
                            <h2 class="text-sm font-bold text-darkgreen">Pemberitahuan Guru</h2>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-forest/10 text-forest text-[11px] font-bold">2 Baru</span>
                    </div>

                    <div class="py-2.5 space-y-2">
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-soft/60 hover:bg-soft transition-colors border border-border-soft/40">
                            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                <x-icon name="clipboard-document-check" class="w-4 h-4" />
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-darkgreen">Absensi Kelas VIII-A Belum Lengkap</p>
                                <p class="text-[11px] text-muted-text mt-0.5 leading-relaxed">Pertemuan Jam Ke-2 Matematika menunggu submit absensi.</p>
                                <span class="text-[10px] text-muted-text/80 font-medium mt-1 inline-block">10 menit yang lalu</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl bg-soft/60 hover:bg-soft transition-colors border border-border-soft/40">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <x-icon name="calendar" class="w-4 h-4" />
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-darkgreen">Rapat Dewan Guru & Kurikulum</p>
                                <p class="text-[11px] text-muted-text mt-0.5 leading-relaxed">Sabtu, Pukul 10.00 WIB di Ruang Guru Utama.</p>
                                <span class="text-[10px] text-muted-text/80 font-medium mt-1 inline-block">Kemarin</span>
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
                    class="flex items-center gap-2.5 p-1 pl-2 sm:pl-3 pr-2.5 rounded-2xl bg-white hover:bg-soft border border-border-soft focus:outline-hidden focus:ring-2 focus:ring-forest transition-all cursor-pointer shadow-2xs group"
                    aria-label="Menu Profil Guru"
                    aria-expanded="false"
                >
                    <div class="text-left hidden sm:block">
                        <span class="text-xs font-black text-darkgreen leading-tight block truncate max-w-[130px] group-hover:text-forest transition-colors">
                            {{ auth()->user()->username ?? 'Budi Santoso, S.Pd.' }}
                        </span>
                        <span class="text-[10px] text-forest font-bold uppercase tracking-wider leading-none mt-0.5 block">
                            Guru Pengampu
                        </span>
                    </div>

                    <div class="w-8 h-8 rounded-xl bg-forest text-white font-extrabold text-xs flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr(auth()->user()->username ?? 'Guru', 0, 2)) }}
                    </div>

                    <x-icon name="chevron-down" class="w-3.5 h-3.5 text-muted-text hidden sm:block transition-transform duration-200" />
                </button>

                <!-- Profile Menu Flyout -->
                <div 
                    id="guru-profile-menu" 
                    class="hidden absolute right-0 mt-2.5 w-64 bg-white rounded-2xl shadow-xl border border-border-soft p-2 z-50 animate-in fade-in zoom-in-95 duration-150"
                >
                    <div class="px-3 py-2.5 border-b border-border-soft mb-1">
                        <p class="text-xs font-bold text-darkgreen">{{ auth()->user()->username ?? 'Budi Santoso, S.Pd.' }}</p>
                        <p class="text-[11px] text-muted-text mt-0.5">NIP: 198503152010011012</p>
                        @if($isWaliKelas)
                            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold border border-emerald-200">Wali Kelas {{ $kelasPerwalian }}</span>
                        @endif
                    </div>

                    <a href="{{ route('password.change') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-darkgreen hover:bg-soft hover:text-forest transition-colors">
                        <x-icon name="key" class="w-4 h-4 text-forest" />
                        <span>Ubah Password</span>
                    </a>

                    <a href="http://localhost:8080/rkm" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-darkgreen hover:bg-soft hover:text-forest transition-colors">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="arrow-top-right-on-square" class="w-4 h-4 text-forest" />
                            <span>Buka Aplikasi RKM</span>
                        </div>
                        <span class="text-[10px] text-forest font-bold uppercase">Eksternal</span>
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
