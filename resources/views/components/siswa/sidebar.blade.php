@props([
    'activeMenu' => 'dashboard',
    'kelasSiswa' => 'VIII-B',
])

@php
    // Menu navigasi Siswa sesuai PRD & DESIGN.md Section 10.4
    // Navigasi sederhana: Dashboard, Jadwal Pelajaran, Materi Pembelajaran, Absensi Saya, Profil
    $menuItems = [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'chart-bar',
            'route' => route('dashboard.siswa'),
            'badge' => null,
        ],
        [
            'id' => 'jadwal-siswa',
            'label' => 'Jadwal Pelajaran',
            'icon' => 'calendar',
            'route' => route('siswa.jadwal'),
            'badge' => 'Hari Ini',
        ],
        [
            'id' => 'materi-siswa',
            'label' => 'Materi Pembelajaran',
            'icon' => 'book-open',
            'route' => route('siswa.materi'),
            'badge' => 'Baru',
        ],
        [
            'id' => 'absensi-siswa',
            'label' => 'Absensi Saya',
            'icon' => 'clipboard-document-check',
            'route' => route('siswa.absensi'),
            'badge' => '100%',
        ],
        [
            'id' => 'profil-siswa',
            'label' => 'Profil & Akun',
            'icon' => 'user',
            'route' => route('siswa.profil'),
            'badge' => null,
        ],
    ];
@endphp

<!-- Responsive Sidebar Siswa Container -->
<aside 
    id="siswa-sidebar"
    class="fixed inset-y-0 left-0 z-40 w-72 bg-darkgreen text-white flex flex-col justify-between transition-transform duration-300 ease-in-out transform -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none select-none border-r border-emerald-950/40"
    aria-label="Sidebar Navigasi Siswa"
>
    <!-- Top Branding & Identity Area -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-white/10 shrink-0">
        <a href="{{ route('dashboard.siswa') }}" class="flex items-center gap-3 group focus:outline-hidden">
            <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center p-1.5 transition-transform group-hover:scale-105">
                <img 
                    src="{{ asset('images/school/logo.png') }}" 
                    alt="Logo SMP Muhammadiyah 7 Medan" 
                    class="w-full h-full object-contain"
                />
            </div>
            <div class="flex flex-col text-left">
                <span class="text-sm font-black text-white tracking-tight leading-tight">SIAKAD SISWA</span>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300/80">SMP Muhammadiyah 7</span>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button 
            type="button" 
            id="siswa-sidebar-close" 
            class="lg:hidden p-2 rounded-xl text-white/70 hover:text-white hover:bg-white/10 focus:outline-hidden focus:ring-2 focus:ring-white/20"
            aria-label="Tutup Menu Navigasi"
        >
            <x-icon name="x-mark" class="w-5 h-5" />
        </button>
    </div>

    <!-- Student Micro-Card Profile -->
    <div class="px-5 py-4 border-b border-white/10 bg-black/15 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-forest border border-emerald-400/30 flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-xs">
                {{ strtoupper(substr(auth()->user()->username ?? '3901', 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-white truncate">
                    Ahmad Rizky Pratama
                </p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="text-[10px] font-mono text-emerald-300 font-semibold">NIS: {{ auth()->user()->username ?? '3901' }}</span>
                    <span class="text-[9px] px-1.5 py-0.2 rounded-sm bg-forest text-emerald-200 border border-emerald-400/30 font-bold">Kelas {{ $kelasSiswa }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu List -->
    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5 sidebar-scrollbar overscroll-contain">
        <h3 class="px-3 text-[11px] font-bold uppercase tracking-wider text-emerald-300/60 mb-2">
            Menu Siswa
        </h3>

        <ul class="space-y-1">
            @foreach($menuItems as $item)
                @php
                    $isActive = ($activeMenu === $item['id']);
                @endphp
                <li>
                    <a 
                        href="{{ $item['route'] }}" 
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all group {{ $isActive ? 'bg-forest text-white font-semibold shadow-xs border border-white/15' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
                        @if($isActive) aria-current="page" @endif
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="{{ $isActive ? 'text-emerald-300' : 'text-white/70 group-hover:text-white group-hover:scale-105 transition-transform' }}">
                                <x-icon :name="$item['icon']" class="w-4 h-4 shrink-0" />
                            </span>
                            <span class="truncate">{{ $item['label'] }}</span>
                        </div>

                        @if(!empty($item['badge']))
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $isActive ? 'bg-white/20 text-white' : ($item['badge'] === '100%' ? 'bg-emerald-950/60 text-emerald-300 border border-emerald-800/40' : 'bg-forest/50 text-emerald-200 border border-emerald-500/30') }}">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Bottom Actions / Logout Area -->
    <div class="p-4 border-t border-white/10 bg-black/10 shrink-0 space-y-2">
        <a 
            href="{{ url('/') }}" 
            class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-medium text-white/75 hover:text-white hover:bg-white/10 transition-colors"
        >
            <div class="flex items-center gap-2.5">
                <x-icon name="arrow-left" class="w-4 h-4 text-emerald-300" />
                <span>Lihat Web Sekolah</span>
            </div>
            <x-icon name="external-link" class="w-3.5 h-3.5 text-white/40" />
        </a>

        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-semibold bg-red-500/20 hover:bg-red-500/30 text-red-200 border border-red-500/30 transition-colors cursor-pointer"
            >
                <x-icon name="arrow-right-on-rectangle" class="w-4 h-4 text-red-300" />
                <span>Keluar dari SIAKAD</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay Backdrop -->
<div 
    id="siswa-sidebar-backdrop" 
    class="fixed inset-0 bg-darkgreen/60 backdrop-blur-xs z-30 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" 
    aria-hidden="true"
></div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('siswa-sidebar');
        const openBtn = document.getElementById('siswa-sidebar-open');
        const closeBtn = document.getElementById('siswa-sidebar-close');
        const backdrop = document.getElementById('siswa-sidebar-backdrop');

        function openSidebar() {
            if (!sidebar || !backdrop) return;
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            if (!sidebar || !backdrop) return;
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        }

        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (backdrop) backdrop.addEventListener('click', closeSidebar);
    });
</script>
