<x-layouts.admin 
    title="Manajemen Tahun Ajaran & Semester" 
    activeMenu="master-tahun-ajaran"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.tahun-ajaran')],
        ['label' => 'Tahun Ajaran', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Pengelolaan Periode Tahun Ajaran & Semester (tahun)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Atur tahun ajaran aktif, riwayat periode sebelumnya, dan penetapan semester berjalan.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="calendar" class="w-4 h-4" />
                    <span>Tambah Periode Baru</span>
                </button>
            </div>
        </div>

        <!-- Academic Rule Info (DESIGN.md 11.3 & ARCHITECTURE.md) -->
        <div class="p-5 rounded-3xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-900 text-xs flex items-start gap-3">
            <x-icon name="check-circle" class="w-5 h-5 text-emerald-700 mt-0.5 shrink-0" />
            <div class="space-y-1">
                <span class="font-bold text-sm block">Aturan Sistem: Tepat Satu Periode Aktif</span>
                <p class="text-emerald-800 leading-relaxed">
                    Sistem SIAKAD menjamin hanya ada <strong>satu baris tahun ajaran berstatus Aktif (Y)</strong> pada satu waktu. Saat mengaktifkan periode baru, periode yang sedang berjalan otomatis dinonaktifkan secara aman via stored procedure.
                </p>
            </div>
        </div>

        @php
            $periods = [
                ['tasm' => '20261', 'nama' => '2026/2027 Semester 1', 'semester' => 'Ganjil', 'status' => 'Aktif (Y)', 'is_active' => true, 'rentang' => 'Juli 2026 - Desember 2026'],
                ['tasm' => '20252', 'nama' => '2025/2026 Semester 2', 'semester' => 'Genap', 'status' => 'Selesai (N)', 'is_active' => false, 'rentang' => 'Januari 2026 - Juni 2026'],
                ['tasm' => '20251', 'nama' => '2025/2026 Semester 1', 'semester' => 'Ganjil', 'status' => 'Selesai (N)', 'is_active' => false, 'rentang' => 'Juli 2025 - Desember 2025'],
                ['tasm' => '20242', 'nama' => '2024/2025 Semester 2', 'semester' => 'Genap', 'status' => 'Selesai (N)', 'is_active' => false, 'rentang' => 'Januari 2025 - Juni 2025'],
                ['tasm' => '20241', 'nama' => '2024/2025 Semester 1', 'semester' => 'Ganjil', 'status' => 'Selesai (N)', 'is_active' => false, 'rentang' => 'Juli 2024 - Desember 2024'],
            ];
        @endphp

        <!-- Master Data Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Kode Periode</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama Tahun Ajaran & Semester</th>
                            <th class="py-3.5 px-4 sm:px-6">Jenis Semester</th>
                            <th class="py-3.5 px-4 sm:px-6">Rentang Waktu</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @foreach($periods as $p)
                            <tr class="hover:bg-soft/40 transition-colors {{ $p['is_active'] ? 'bg-emerald-50/30' : '' }}">
                                <td class="py-3.5 px-4 sm:px-6 font-mono font-bold text-forest text-xs">{{ $p['tasm'] }}</td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="font-bold text-darkgreen block">{{ $p['nama'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $p['semester'] === 'Ganjil' ? 'bg-blue-50 text-blue-800' : 'bg-purple-50 text-purple-800' }}">
                                        {{ $p['semester'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-xs text-muted-text">
                                    {{ $p['rentang'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($p['is_active'])
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-xs">
                                            Sedang Berjalan
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700">
                                            Arsip Riwayat
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if(!$p['is_active'])
                                            <button type="button" class="px-3 py-1 rounded-lg bg-soft hover:bg-forest hover:text-white text-xs font-semibold text-darkgreen transition-colors">
                                                Aktifkan
                                            </button>
                                        @else
                                            <span class="text-xs text-emerald-700 font-semibold px-2">Periode Utama</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 sm:px-6 border-t border-border-soft bg-soft/30 text-xs text-muted-text flex items-center justify-between">
                <span>Total: <strong>{{ count($periods) }} Riwayat Periode</strong> Tercatat</span>
                <span class="text-muted-text">Sinkronisasi Database RKM</span>
            </div>
        </div>

    </div>
</x-layouts.admin>
