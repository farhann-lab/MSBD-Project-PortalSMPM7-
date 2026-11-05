<x-layouts.admin 
    title="Pemantauan Absensi Siswa" 
    activeMenu="akademik-absensi"
    :breadcrumbs="[
        ['label' => 'Akademik', 'url' => route('admin.akademik.absensi')],
        ['label' => 'Pemantauan Absensi', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Monitoring & Rekapitulasi Presensi Siswa (presensi)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Pantau input kehadiran harian dari guru mata pelajaran dan wali kelas secara terpusat untuk seluruh rombel.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-white border border-border-soft hover:bg-soft text-darkgreen text-xs sm:text-sm font-semibold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="arrow-down-tray" class="w-4 h-4 text-forest" />
                    <span>Ekspor Rekap Bulanan (Excel)</span>
                </button>
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="check-circle" class="w-4 h-4" />
                    <span>Tutup Absensi Harian</span>
                </button>
            </div>
        </div>

        <!-- Today Attendance Metrics -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs col-span-2 lg:col-span-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Presensi Hari Ini</span>
                <div class="text-2xl font-black text-darkgreen mt-1">96.8%</div>
                <span class="text-[11px] text-muted-text mt-0.5 block">Kamis, 5 Nov 2026</span>
            </div>

            <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200/60 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Hadir (H)</span>
                <div class="text-2xl font-black text-emerald-700 mt-1">345</div>
                <span class="text-[11px] text-emerald-800/80 mt-0.5 block">Dari 356 Total Siswa</span>
            </div>

            <div class="p-5 rounded-2xl bg-blue-50/60 border border-blue-200/60 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-800">Sakit (S)</span>
                <div class="text-2xl font-black text-blue-700 mt-1">5</div>
                <span class="text-[11px] text-blue-800/80 mt-0.5 block">Surat dokter terverifikasi</span>
            </div>

            <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200/60 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Izin (I)</span>
                <div class="text-2xl font-black text-amber-700 mt-1">4</div>
                <span class="text-[11px] text-amber-800/80 mt-0.5 block">Pemberitahuan wali murid</span>
            </div>

            <div class="p-5 rounded-2xl bg-rose-50/60 border border-rose-200/60 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-800">Alpa / Tanpa Ket (A)</span>
                <div class="text-2xl font-black text-rose-700 mt-1">2</div>
                <span class="text-[11px] text-rose-800/80 mt-0.5 block">Notifikasi dikirim ke ortu</span>
            </div>
        </div>

        <!-- Filter Presensi -->
        <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Pilih Tanggal</label>
                    <input type="date" value="2026-11-05" class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Tingkat Kelas</label>
                    <select class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="all" selected>Seluruh Tingkat (VII, VIII, IX)</option>
                        <option value="7">Kelas VII</option>
                        <option value="8">Kelas VIII</option>
                        <option value="9">Kelas IX</option>
                    </select>
                </div>
            </div>

            <div class="text-xs text-muted-text">
                Status sinkronisasi: <span class="font-bold text-emerald-700">10 dari 10 Rombel Telah Diinput</span>
            </div>
        </div>

        @php
            $attendanceData = [
                ['rombel' => 'Kelas VII-1', 'wali' => 'Dra. Nurhayati M.Pd', 'total' => 36, 'h' => 35, 's' => 1, 'i' => 0, 'a' => 0, 'status' => 'Lengkap', 'updated_at' => '07.45 WIB'],
                ['rombel' => 'Kelas VII-2', 'wali' => 'Ahmad Fauzi, S.Pd.I', 'total' => 36, 'h' => 34, 's' => 1, 'i' => 1, 'a' => 0, 'status' => 'Lengkap', 'updated_at' => '07.50 WIB'],
                ['rombel' => 'Kelas VII-3', 'wali' => 'Sarah Wijaya, S.Pd', 'total' => 35, 'h' => 34, 's' => 0, 'i' => 1, 'a' => 0, 'status' => 'Lengkap', 'updated_at' => '07.40 WIB'],
                ['rombel' => 'Kelas VIII-1', 'wali' => 'Bambang Irawan, S.Pd', 'total' => 36, 'h' => 35, 's' => 0, 'i' => 0, 'a' => 1, 'status' => 'Lengkap', 'updated_at' => '08.05 WIB'],
                ['rombel' => 'Kelas VIII-2', 'wali' => 'Rina Marlina, S.Si', 'total' => 35, 'h' => 34, 's' => 1, 'i' => 0, 'a' => 0, 'status' => 'Lengkap', 'updated_at' => '07.55 WIB'],
                ['rombel' => 'Kelas VIII-3', 'wali' => 'Hasan Basri, S.Pd', 'total' => 36, 'h' => 35, 's' => 0, 'i' => 1, 'a' => 0, 'status' => 'Lengkap', 'updated_at' => '07.45 WIB'],
                ['rombel' => 'Kelas IX-1', 'wali' => 'Dedi Kurniawan, S.Pd.Or', 'total' => 36, 'h' => 35, 's' => 1, 'i' => 0, 'a' => 0, 'status' => 'Lengkap', 'updated_at' => '07.35 WIB'],
                ['rombel' => 'Kelas IX-2', 'wali' => 'Dewi Lestari, S.Sn', 'total' => 35, 'h' => 34, 's' => 0, 'i' => 0, 'a' => 1, 'status' => 'Lengkap', 'updated_at' => '08.00 WIB'],
                ['rombel' => 'Kelas IX-3', 'wali' => 'Ferry Andika, S.Kom', 'total' => 36, 'h' => 35, 's' => 1, 'i' => 0, 'a' => 0, 'status' => 'Lengkap', 'updated_at' => '07.42 WIB'],
                ['rombel' => 'Kelas IX-4', 'wali' => 'Halimah, Lc', 'total' => 35, 'h' => 34, 's' => 0, 'i' => 1, 'a' => 0, 'status' => 'Lengkap', 'updated_at' => '07.38 WIB'],
            ];
        @endphp

        <!-- Attendance Summary Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Rombel & Wali Kelas</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Total Siswa</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center text-emerald-700">Hadir</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center text-blue-700">Sakit</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center text-amber-700">Izin</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center text-rose-700">Alpa</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">% Kehadiran</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status Input</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach($attendanceData as $row)
                            @php
                                $percent = round(($row['h'] / $row['total']) * 100, 1);
                            @endphp
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-darkgreen">{{ $row['rombel'] }}</div>
                                    <div class="text-[11px] text-muted-text">{{ $row['wali'] }}</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-darkgreen">
                                    {{ $row['total'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-emerald-700 bg-emerald-50/20">
                                    {{ $row['h'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-medium text-blue-700">
                                    {{ $row['s'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-medium text-amber-700">
                                    {{ $row['i'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold {{ $row['a'] > 0 ? 'text-rose-700 bg-rose-50/20' : 'text-muted-text' }}">
                                    {{ $row['a'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <div class="flex items-center justify-center gap-1.5 font-bold {{ $percent >= 95 ? 'text-emerald-700' : 'text-amber-700' }}">
                                        <span>{{ $percent }}%</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                        {{ $row['status'] }} ({{ $row['updated_at'] }})
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <button type="button" class="text-xs font-semibold text-forest hover:underline">
                                        Lihat Rincian
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.admin>