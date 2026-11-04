<x-layouts.admin 
    title="Penugasan Guru (Mata Pelajaran & Kelas)" 
    activeMenu="master-penugasan"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.penugasan')],
        ['label' => 'Penugasan Guru', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Penugasan Guru ke Mata Pelajaran & Kelas (t_guru_mapel)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Tetapkan guru pengampu untuk setiap rombel kelas pada periode semester aktif (2026/2027 Ganjil).
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs sm:text-sm font-semibold flex items-center gap-2 transition-colors">
                    <x-icon name="arrow-path" class="w-4 h-4 text-forest" />
                    <span>Salin dari Semester Lalu</span>
                </button>
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="clipboard-check" class="w-4 h-4" />
                    <span>Tambah Penugasan</span>
                </button>
            </div>
        </div>

        @php
            $assignments = [
                ['guru' => 'Ahmad Fauzi, S.Pd.', 'mapel' => 'Matematika', 'kelas' => '7A, 7B, 7C', 'jam' => '12 Jam/Minggu'],
                ['guru' => 'Eka Setiawan, S.Pd.', 'mapel' => 'Matematika', 'kelas' => '8A, 8B, 8C, 9A', 'jam' => '16 Jam/Minggu'],
                ['guru' => 'Aisyah Rahmawati, S.Pd.', 'mapel' => 'Bahasa Indonesia', 'kelas' => '7A, 7B, 8A', 'jam' => '12 Jam/Minggu'],
                ['guru' => 'Faridah Hanum, S.Pd.', 'mapel' => 'Bahasa Indonesia', 'kelas' => '8B, 9A, 9B, 9C', 'jam' => '16 Jam/Minggu'],
                ['guru' => 'Amirul Hadi, S.Pd.I.', 'mapel' => 'Pendidikan Agama Islam', 'kelas' => '7A, 7B, 7C, 8A', 'jam' => '12 Jam/Minggu'],
                ['guru' => 'Nurul Hidayah, S.Pd.', 'mapel' => 'Pendidikan Agama Islam', 'kelas' => '8B, 8C, 9A, 9B, 9C, 9D', 'jam' => '18 Jam/Minggu'],
                ['guru' => 'Budi Santoso, S.Pd.', 'mapel' => 'IPA', 'kelas' => '7A, 7B, 8A, 8B', 'jam' => '16 Jam/Minggu'],
                ['guru' => 'Hendra Gunawan, S.Pd.', 'mapel' => 'IPA', 'kelas' => '8C, 9A, 9B, 9C, 9D', 'jam' => '20 Jam/Minggu'],
                ['guru' => 'Diah Puspita Sari, S.Pd.', 'mapel' => 'Bahasa Inggris', 'kelas' => '7A, 7B, 8A, 8B, 9A, 9B', 'jam' => '18 Jam/Minggu'],
                ['guru' => 'Andika Permana, S.Pd.', 'mapel' => 'Pendidikan Jasmani', 'kelas' => '7A, 7B, 7C, 8A, 8B', 'jam' => '15 Jam/Minggu'],
                ['guru' => 'Irfan Maulana, S.Kom.', 'mapel' => 'Informatika / TIK', 'kelas' => '7A, 7B, 8A, 8B, 9A, 9B', 'jam' => '12 Jam/Minggu'],
            ];
        @endphp

        <!-- Master Data Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">No</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama Guru Pengampu</th>
                            <th class="py-3.5 px-4 sm:px-6">Mata Pelajaran</th>
                            <th class="py-3.5 px-4 sm:px-6">Kelas yang Diampu</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Beban Mengajar</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @foreach($assignments as $idx => $a)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="font-bold text-darkgreen block">{{ $a['guru'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-semibold">
                                        <x-icon name="book-open" class="w-3.5 h-3.5 text-emerald-600" />
                                        {{ $a['mapel'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(explode(', ', $a['kelas']) as $k)
                                            <span class="px-2 py-0.5 rounded-md bg-soft text-forest text-xs font-bold border border-border-soft">
                                                {{ $k }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-xs text-muted-text font-semibold">
                                    {{ $a['jam'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-soft transition-colors" title="Ubah Penugasan">
                                            <x-icon name="external-link" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 sm:px-6 border-t border-border-soft bg-soft/30 text-xs text-muted-text flex items-center justify-between">
                <span>Total: <strong>{{ count($assignments) }} Penugasan Aktif</strong> Terdaftar</span>
                <span class="text-emerald-700 font-semibold">Semua Kelas Terjadwal</span>
            </div>
        </div>

    </div>
</x-layouts.admin>
