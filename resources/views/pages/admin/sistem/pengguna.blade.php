<x-layouts.admin 
    title="Manajemen Pengguna" 
    activeMenu="sistem-pengguna"
    :breadcrumbs="[
        ['label' => 'Akun & Sistem', 'url' => route('admin.sistem.pengguna')],
        ['label' => 'Manajemen Pengguna', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Pengelolaan Akun Pengguna SIAKAD (users)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Kontrol hak akses multi-role (Admin, Guru, Siswa), status aktivasi akun, dan pembuatan akun secara manual atau otomatis.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-white border border-border-soft hover:bg-soft text-darkgreen text-xs sm:text-sm font-semibold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="user-group" class="w-4 h-4 text-forest" />
                    <span>Generate Akun Siswa Baru</span>
                </button>
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Tambah Pengguna</span>
                </button>
            </div>
        </div>

        <!-- Role Overview Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Administrator</span>
                <div class="text-2xl font-bold text-darkgreen mt-1">3 Akun</div>
                <span class="text-xs text-muted-text mt-0.5 block">Hak akses penuh sistem</span>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Akun Dewan Guru</span>
                <div class="text-2xl font-bold text-darkgreen mt-1">17 Akun</div>
                <span class="text-xs text-emerald-600 mt-0.5 block font-medium">17 Aktif Terverifikasi</span>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Akun Siswa</span>
                <div class="text-2xl font-bold text-darkgreen mt-1">356 Akun</div>
                <span class="text-xs text-muted-text mt-0.5 block">Login menggunakan NISN</span>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-700">Password Default</span>
                <div class="text-2xl font-bold text-amber-600 mt-1">28 Akun</div>
                <span class="text-xs text-amber-700/80 mt-0.5 block">Belum ganti password pertama</span>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="w-72">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Cari Pengguna</label>
                    <div class="relative">
                        <x-icon name="magnifying-glass" class="w-4 h-4 text-muted-text absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                        <input type="text" placeholder="Cari nama, NIP, atau NISN..." class="w-full pl-10 pr-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Peran (Role)</label>
                    <select class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="all" selected>Semua Peran</option>
                        <option value="admin">Administrator</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Status Akun</label>
                    <select class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="all" selected>Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="text-xs text-muted-text">
                Menampilkan 8 dari 376 pengguna terdaftar
            </div>
        </div>

        @php
            $sampleUsers = [
                ['name' => 'Administrator SIAKAD', 'username' => 'admin', 'role' => 'admin', 'role_badge' => 'bg-purple-100 text-purple-800', 'status' => 'Aktif', 'changed_pwd' => true, 'last_login' => '5 Nov 2026 21:10'],
                ['name' => 'Dra. Hj. Siti Rahmah M.Pd', 'username' => '197508142000032001', 'role' => 'guru', 'role_badge' => 'bg-emerald-100 text-emerald-800', 'status' => 'Aktif', 'changed_pwd' => true, 'last_login' => '5 Nov 2026 14:22'],
                ['name' => 'Ustadz Ahmad Fauzi, S.Pd.I', 'username' => '198203112006041005', 'role' => 'guru', 'role_badge' => 'bg-emerald-100 text-emerald-800', 'status' => 'Aktif', 'changed_pwd' => true, 'last_login' => '5 Nov 2026 12:45'],
                ['name' => 'Sarah Wijaya, S.Pd', 'username' => '199011052015032004', 'role' => 'guru', 'role_badge' => 'bg-emerald-100 text-emerald-800', 'status' => 'Aktif', 'changed_pwd' => true, 'last_login' => '4 Nov 2026 16:30'],
                ['name' => 'Aditya Pratama Putra', 'username' => '0098231001', 'role' => 'siswa', 'role_badge' => 'bg-blue-100 text-blue-800', 'status' => 'Aktif', 'changed_pwd' => true, 'last_login' => '5 Nov 2026 19:15'],
                ['name' => 'Aisyah Humaira', 'username' => '0098231002', 'role' => 'siswa', 'role_badge' => 'bg-blue-100 text-blue-800', 'status' => 'Aktif', 'changed_pwd' => true, 'last_login' => '5 Nov 2026 18:02'],
                ['name' => 'Bilal Ramadhan', 'username' => '0098231003', 'role' => 'siswa', 'role_badge' => 'bg-blue-100 text-blue-800', 'status' => 'Aktif', 'changed_pwd' => false, 'last_login' => '28 Okt 2026 10:14'],
                ['name' => 'Dimas Arya Saputra', 'username' => '0098231004', 'role' => 'siswa', 'role_badge' => 'bg-blue-100 text-blue-800', 'status' => 'Aktif', 'changed_pwd' => false, 'last_login' => 'Belum Pernah'],
            ];
        @endphp

        <!-- Users Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Nama & Info Profil</th>
                            <th class="py-3.5 px-4 sm:px-6 font-mono">Username</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Peran (Role)</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status Password</th>
                            <th class="py-3.5 px-4 sm:px-6">Aktivitas Terakhir</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach($sampleUsers as $u)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-forest/15 text-forest font-bold flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($u['name'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-darkgreen">{{ $u['name'] }}</div>
                                            <span class="text-[11px] text-muted-text capitalize">{{ $u['role'] }} SMPM7</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-darkgreen font-semibold">
                                    {{ $u['username'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $u['role_badge'] }}">
                                        {{ $u['role'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($u['changed_pwd'])
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                            Telah Diubah
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-100 text-amber-800">
                                            Default
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text text-xs">
                                    {{ $u['last_login'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.sistem.reset-password') }}" class="p-1.5 rounded-lg hover:bg-soft text-muted-text hover:text-darkgreen transition-colors" title="Reset Password">
                                            <x-icon name="key" class="w-4 h-4" />
                                        </a>
                                        <button type="button" class="p-1.5 rounded-lg hover:bg-soft text-muted-text hover:text-darkgreen transition-colors" title="Edit Pengguna">
                                            <x-icon name="pencil-square" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.admin>