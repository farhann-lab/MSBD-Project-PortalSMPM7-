<x-layouts.admin 
    title="Data Guru & Tendik" 
    activeMenu="master-guru"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.guru')],
        ['label' => 'Guru & Tendik', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen Guru & Tenaga Kependidikan</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Daftar pendidik aktif dan staf SMP Muhammadiyah 7 Medan yang terintegrasi dengan penugasan dan akun SIAKAD.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="user-plus" class="w-4 h-4" />
                    <span>Tambah Guru Baru</span>
                </button>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                    <x-icon name="search" class="w-4 h-4 text-sage" />
                </span>
                <input 
                    type="text" 
                    placeholder="Cari nama guru atau mata pelajaran..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2">
                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Status Keaktifan</option>
                    <option value="A" selected>Aktif Mengajar (A)</option>
                    <option value="P">Pindah / Cuti (P)</option>
                    <option value="M">Mutasi / Berhenti (M)</option>
                </select>
                <button type="button" class="px-3 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs flex items-center gap-1.5 transition-colors">
                    <x-icon name="filter" class="w-3.5 h-3.5 text-sage" />
                    <span>Filter</span>
                </button>
            </div>
        </div>

        <!-- Master Data Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">No</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama & Gelar</th>
                            <th class="py-3.5 px-4 sm:px-6">Mata Pelajaran Utama</th>
                            <th class="py-3.5 px-4 sm:px-6">Jabatan Tambahan</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Akun SIAKAD</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @foreach($teachers as $index => $teacher)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-forest/10 text-forest font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($teacher['nama_lengkap'], 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-darkgreen block">{{ $teacher['nama_lengkap'] }}</span>
                                            <span class="text-[11px] text-muted-text">NIP. 19820315 200801 1 00{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-semibold">
                                        <x-icon name="book-open" class="w-3.5 h-3.5 text-emerald-600" />
                                        {{ $teacher['mapel'] ?? 'Belum ditentukan' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text text-xs">
                                    {{ $teacher['jabatan'] ?? 'Guru Pengampu' }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        Aktif (A)
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-semibold">
                                        <x-icon name="check-circle" class="w-3.5 h-3.5 text-emerald-600" />
                                        Terhubung
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-soft transition-colors" title="Edit Data Guru">
                                            <x-icon name="external-link" class="w-4 h-4" />
                                        </button>
                                        <button type="button" class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-soft transition-colors" title="Reset Kata Sandi">
                                            <x-icon name="key" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination & Count -->
            <div class="p-4 sm:px-6 border-t border-border-soft bg-soft/30 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-muted-text">
                <div>
                    Menampilkan <strong>1 - {{ count($teachers) }}</strong> dari <strong>{{ count($teachers) }}</strong> data guru
                </div>
                <div class="flex items-center gap-1">
                    <span class="px-3 py-1.5 rounded-lg bg-forest text-white font-bold">1</span>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
