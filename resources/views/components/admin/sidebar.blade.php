@props([
    'activeMenu' => 'dashboard',
])

@php
    // Kelompok menu navigasi Admin sesuai PRD & DESIGN.md Section 10.1
    $menuGroups = [
        [
            'heading' => 'Menu Utama',
            'items' => [
                [
                    'id' => 'dashboard',
                    'label' => 'Dashboard',
                    'icon' => 'chart-bar',
                    'route' => route('dashboard.admin'),
                    'badge' => null,
                ],
            ],
        ],
        [
            'heading' => 'Website Sekolah',
            'items' => [
                [
                    'id' => 'web-berita',
                    'label' => 'Kelola Berita',
                    'icon' => 'newspaper',
                    'route' => route('admin.cms.berita.index'),
                    'badge' => null,
                ],
                [
                    'id' => 'web-pengumuman',
                    'label' => 'Pengumuman',
                    'icon' => 'announcement',
                    'route' => route('admin.cms.pengumuman.index'),
                    'badge' => null,
                ],
                [
                    'id' => 'web-fasilitas',
                    'label' => 'Sarana & Prasarana',
                    'icon' => 'building-office',
                    'route' => route('admin.cms.fasilitas.index'),
                    'badge' => null,
                ],
                [
                    'id' => 'web-prestasi',
                    'label' => 'Prestasi Siswa',
                    'icon' => 'trophy',
                    'route' => route('admin.cms.prestasi.index'),
                    'badge' => null,
                ],
                [
                    'id' => 'web-galeri',
                    'label' => 'Galeri Foto',
                    'icon' => 'photo',
                    'route' => route('admin.cms.galeri.index'),
                    'badge' => null,
                ],
            ],
        ],
        [
            'heading' => 'Data Master',
            'items' => [
                [
                    'id' => 'master-guru',
                    'label' => 'Guru & Tendik',
                    'icon' => 'users',
                    'route' => route('admin.master.guru'),
                    'badge' => '17 Guru',
                ],
                [
                    'id' => 'master-siswa',
                    'label' => 'Data Siswa & Impor',
                    'icon' => 'user-group',
                    'route' => route('admin.master.siswa'),
                    'badge' => '356 Siswa',
                ],
                [
                    'id' => 'master-kelas',
                    'label' => 'Manajemen Kelas',
                    'icon' => 'building-office',
                    'route' => route('admin.master.kelas'),
                    'badge' => '10 Rombel',
                ],
                [
                    'id' => 'master-mapel',
                    'label' => 'Mata Pelajaran',
                    'icon' => 'book-open',
                    'route' => route('admin.master.mapel'),
                    'badge' => '14 Mapel',
                ],
                [
                    'id' => 'master-penugasan',
                    'label' => 'Penugasan Guru',
                    'icon' => 'clipboard-check',
                    'route' => route('admin.master.penugasan'),
                    'badge' => null,
                ],
                [
                    'id' => 'master-tahun-ajaran',
                    'label' => 'Tahun Ajaran',
                    'icon' => 'calendar',
                    'route' => route('admin.master.tahun-ajaran'),
                    'badge' => 'Aktif',
                ],
                [
                    'id' => 'master-ekskul',
                    'label' => 'Ekstrakurikuler',
                    'icon' => 'trophy',
                    'route' => route('admin.master.ekskul'),
                    'badge' => null,
                ],
            ],
        ],
        [
            'heading' => 'Akademik',
            'items' => [
                [
                    'id' => 'akademik-jadwal',
                    'label' => 'Jadwal Pelajaran',
                    'icon' => 'clock',
                    'route' => route('admin.akademik.jadwal'),
                    'badge' => null,
                ],
                [
                    'id' => 'akademik-kenaikan',
                    'label' => 'Kenaikan Kelas',
                    'icon' => 'academic-cap',
                    'route' => route('admin.akademik.kenaikan'),
                    'badge' => null,
                ],
                [
                    'id' => 'akademik-absensi',
                    'label' => 'Pemantauan Absensi',
                    'icon' => 'clipboard-document-check',
                    'route' => route('admin.akademik.absensi'),
                    'badge' => null,
                ],
            ],
        ],
        [
            'heading' => 'Akun & Sistem',
            'items' => array_values(array_filter([
                auth()->check() && auth()->user()->isAdminUtama() ? [
                    'id' => 'sistem-manajemen-admin',
                    'label' => 'Manajemen Admin',
                    'icon' => 'shield-check',
                    'route' => route('admin.manajemen-admin.index'),
                    'badge' => 'Utama',
                ] : null,
                [
                    'id' => 'sistem-pengguna',
                    'label' => 'Manajemen Pengguna',
                    'icon' => 'user-plus',
                    'route' => route('admin.sistem.pengguna'),
                    'badge' => null,
                ],
                [
                    'id' => 'sistem-reset-password',
                    'label' => 'Reset Password',
                    'icon' => 'key',
                    'route' => route('admin.sistem.reset-password'),
                    'badge' => null,
                ],
                [
                    'id' => 'sistem-pengaturan',
                    'label' => 'Pengaturan Sekolah',
                    'icon' => 'settings',
                    'route' => route('admin.sistem.pengaturan'),
                    'badge' => null,
                ],
            ])),
        ],
    ];
@endphp

<!-- Mobile Backdrop -->
<div 
    id="admin-sidebar-backdrop" 
    class="fixed inset-0 bg-darkgreen/60 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity duration-300"
    tabindex="-1"
    aria-hidden="true"
></div>

<!-- Sidebar Shell (Dark Green Theme per DESIGN.md: #0F1D0F) -->
<aside 
    id="admin-sidebar" 
    class="fixed top-0 bottom-0 left-0 z-50 w-72 bg-[#0F1D0F] text-white flex flex-col transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 border-r border-[#1C3322] shadow-2xl lg:shadow-none"
    aria-label="Sidebar Navigasi Admin"
>
    <!-- Brand / Header Section -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-[#1C3322]/80 bg-[#0A140A]/50">
        <a href="{{ route('dashboard.admin') }}" class="flex items-center gap-3.5 group focus:outline-hidden focus:ring-2 focus:ring-mint rounded-xl p-1 -m-1">
            <div class="w-10 h-10 rounded-xl bg-white/10 p-1 flex items-center justify-center border border-white/15 shadow-inner group-hover:bg-white/20 transition-colors">
                <img src="{{ asset('images/school/logo.png') }}" alt="Logo SMPM7" class="w-8 h-8 object-contain">
            </div>
            <div class="flex flex-col">
                <span class="text-xs uppercase tracking-widest font-bold text-mint">SIAKAD Panel</span>
                <span class="text-sm font-bold text-white tracking-tight group-hover:text-mint transition-colors">SMP Muhammadiyah 7</span>
            </div>
        </a>

        <!-- Mobile Drawer Close Button -->
        <button 
            type="button" 
            id="admin-sidebar-close" 
            class="lg:hidden p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 focus:outline-hidden focus:ring-2 focus:ring-mint"
            aria-label="Tutup Menu Navigasi"
        >
            <x-icon name="x-mark" class="w-5 h-5" />
        </button>
    </div>

    {{-- <!-- Active Academic Year Badge in Sidebar -->
    <div class="px-6 py-3.5 bg-[#142617]/70 border-b border-[#1C3322]/60 flex items-center justify-between text-xs">
        <div class="flex items-center gap-2 text-white/80">
            <span class="w-2 h-2 rounded-full {{ ($globalPeriodeAktif['is_aktif'] ?? true) ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' }}"></span>
            <span class="font-medium">T.A. {{ $globalPeriodeAktif['label_topbar'] ?? '2026/2027 Semester 1' }}</span>
        </div>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ ($globalPeriodeAktif['semester_tipe'] ?? 'Ganjil') === 'Ganjil' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : 'bg-blue-500/20 text-blue-300 border-blue-500/30' }} border">
            {{ $globalPeriodeAktif['semester_tipe'] ?? 'Ganjil' }}
        </span>
    </div> --}}

    <!-- Navigation Menu Items (Scrollable) -->
    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-6 sidebar-scrollbar overscroll-contain" aria-label="Menu Admin">
        @foreach($menuGroups as $group)
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-white/45">
                    {{ $group['heading'] }}
                </div>
                <ul class="space-y-1" role="list">
                    @foreach($group['items'] as $item)
                        @php
                            $isActive = ($activeMenu === $item['id']);
                        @endphp
                        <li>
                            <a 
                                href="{{ $item['route'] }}" 
                                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all group {{ $isActive ? 'bg-[#344B39] text-white shadow-xs font-semibold ring-1 ring-white/10' : 'text-white/75 hover:bg-[#1C3322]/60 hover:text-white' }}"
                                @if($isActive) aria-current="page" @endif
                            >
                                <span class="flex items-center gap-3 min-w-0">
                                    <x-icon 
                                        :name="$item['icon']" 
                                        class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110 {{ $isActive ? 'text-white' : 'text-sage group-hover:text-mint' }}" 
                                    />
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </span>

                                @if(!empty($item['badge']))
                                    <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-[#1C3322] text-mint border border-white/5' }}">
                                        {{ $item['badge'] }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    <!-- Sidebar Footer / Admin Identity & Web Portal Link -->
    <div class="p-4 border-t border-[#1C3322] bg-[#0A140A]/60 space-y-3">
        <!-- Quick Website Link -->
        <a 
            href="{{ route('home') }}" 
            target="_blank" 
            class="flex items-center justify-between px-3 py-2 rounded-xl text-xs text-white/70 hover:text-white hover:bg-white/5 transition-colors group"
        >
            <span class="flex items-center gap-2">
                <x-icon name="globe" class="w-4 h-4 text-sage group-hover:text-mint" />
                <span>Lihat Web Sekolah</span>
            </span>
            <x-icon name="external-link" class="w-3.5 h-3.5 text-white/40 group-hover:text-white/80" />
        </a>

        <!-- Admin Profile Info -->
        <div class="flex items-center justify-between pt-2 border-t border-[#1C3322]/60">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-9 h-9 rounded-full bg-[#344B39] border border-white/20 flex items-center justify-center font-bold text-white text-xs shrink-0 shadow-xs">
                    {{ strtoupper(substr(auth()->user()->username ?? 'AD', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">
                        {{ auth()->user()->username ?? 'Admin Utama' }}
                    </p>
                    <span class="inline-flex items-center gap-1 text-[10px] text-emerald-400 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Admin Aktif
                    </span>
                </div>
            </div>

            <!-- Logout Form Shortcut -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button 
                    type="submit" 
                    title="Keluar dari SIAKAD"
                    class="p-2 text-white/60 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors focus:outline-hidden focus:ring-2 focus:ring-red-400"
                    aria-label="Logout"
                >
                    <x-icon name="logout" class="w-4 h-4" />
                </button>
            </form>
        </div>
    </div>
</aside>
