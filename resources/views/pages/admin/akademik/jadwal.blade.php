<x-layouts.admin 
    title="Jadwal Pelajaran" 
    activeMenu="akademik-jadwal"
    :breadcrumbs="[
        ['label' => 'Akademik', 'url' => route('admin.akademik.jadwal')],
        ['label' => 'Jadwal Pelajaran', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Penyusunan & Distribusi Jadwal Pelajaran (jadwal)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Kelola plotting jam mengajar guru, alokasi ruang kelas, serta deteksi bentrok jadwal otomatis per semester aktif.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-white border border-border-soft hover:bg-soft text-darkgreen text-xs sm:text-sm font-semibold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="arrow-down-tray" class="w-4 h-4 text-forest" />
                    <span>Cetak PDF / Excel</span>
                </button>
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Plotting Jadwal Baru</span>
                </button>
            </div>
        </div>

        <!-- Academic Banner Notification -->
        <div class="p-5 rounded-3xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-900 text-xs flex items-start gap-3">
            <x-icon name="check-circle" class="w-5 h-5 text-emerald-700 mt-0.5 shrink-0" />
            <div class="space-y-1">
                <span class="font-bold text-sm block">Validasi Bentrok Guru & Ruangan Otomatis</span>
                <p class="text-emerald-800 leading-relaxed">
                    Sistem mendeteksi jadwal ganda secara real-time. Tidak diperkenankan seorang guru mengajar di dua rombel berbeda pada jam dan hari yang sama.
                </p>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Pilih Rombel / Kelas</label>
                    <select class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="VII-1" selected>Kelas VII-1 (Ibnu Khaldun)</option>
                        <option value="VII-2">Kelas VII-2 (Ibnu Sina)</option>
                        <option value="VII-3">Kelas VII-3 (Al-Farabi)</option>
                        <option value="VIII-1">Kelas VIII-1 (Al-Biruni)</option>
                        <option value="VIII-2">Kelas VIII-2 (Al-Khawarizmi)</option>
                        <option value="IX-1">Kelas IX-1 (Buya Hamka)</option>
                        <option value="IX-2">Kelas IX-2 (KH. Ahmad Dahlan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Filter Hari</label>
                    <select class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="all" selected>Semua Hari (Senin - Sabtu)</option>
                        <option value="senin">Senin</option>
                        <option value="selasa">Selasa</option>
                        <option value="rabu">Rabu</option>
                        <option value="kamis">Kamis</option>
                        <option value="jumat">Jumat</option>
                        <option value="sabtu">Sabtu</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 self-end md:self-auto text-xs text-muted-text">
                <span class="w-3 h-3 rounded-full bg-forest inline-block"></span> Pembelajaran Aktif
                <span class="w-3 h-3 rounded-full bg-amber-400 inline-block ml-2"></span> Istirahat / Sholat
            </div>
        </div>

        @php
            $scheduleSlots = [
                [
                    'hari' => 'Senin',
                    'items' => [
                        ['jam' => '07.15 - 08.00', 'ke' => '0', 'mapel' => 'Upacara Bendera & Tadarus Pagi', 'guru' => 'Seluruh Dewan Guru & Siswa', 'ruang' => 'Lapangan Utama', 'is_break' => false],
                        ['jam' => '08.00 - 09.20', 'ke' => '1-2', 'mapel' => 'Pendidikan Agama Islam & Kemuhammadiyahan', 'guru' => 'Ustadz Ahmad Fauzi, S.Pd.I', 'ruang' => 'R. VII-1', 'is_break' => false],
                        ['jam' => '09.20 - 10.40', 'ke' => '3-4', 'mapel' => 'Bahasa Indonesia', 'guru' => 'Dra. Nurhayati M.Pd', 'ruang' => 'R. VII-1', 'is_break' => false],
                        ['jam' => '10.40 - 11.10', 'ke' => '-', 'mapel' => 'Istirahat Pertama & Snack Sehat', 'guru' => '-', 'ruang' => '-', 'is_break' => true],
                        ['jam' => '11.10 - 12.30', 'ke' => '5-6', 'mapel' => 'Matematika', 'guru' => 'Bambang Irawan, S.Pd', 'ruang' => 'R. VII-1', 'is_break' => false],
                        ['jam' => '12.30 - 13.15', 'ke' => '-', 'mapel' => 'Sholat Dzuhur Berjamaah', 'guru' => 'Koordinator Ibadah', 'ruang' => 'Masjid Sekolah', 'is_break' => true],
                        ['jam' => '13.15 - 14.35', 'ke' => '7-8', 'mapel' => 'Ilmu Pengetahuan Alam (IPA)', 'guru' => 'Rina Marlina, S.Si', 'ruang' => 'Lab IPA', 'is_break' => false],
                    ]
                ],
                [
                    'hari' => 'Selasa',
                    'items' => [
                        ['jam' => '07.30 - 08.50', 'ke' => '1-2', 'mapel' => 'Bahasa Inggris', 'guru' => 'Sarah Wijaya, S.Pd', 'ruang' => 'R. VII-1', 'is_break' => false],
                        ['jam' => '08.50 - 10.10', 'ke' => '3-4', 'mapel' => 'Ilmu Pengetahuan Sosial (IPS)', 'guru' => 'Hasan Basri, S.Pd', 'ruang' => 'R. VII-1', 'is_break' => false],
                        ['jam' => '10.10 - 10.40', 'ke' => '-', 'mapel' => 'Istirahat Pagi', 'guru' => '-', 'ruang' => '-', 'is_break' => true],
                        ['jam' => '10.40 - 12.00', 'ke' => '5-6', 'mapel' => 'Pendidikan Pancasila & Kewarganegaraan', 'guru' => 'H. Suryadi, S.H, M.Si', 'ruang' => 'R. VII-1', 'is_break' => false],
                        ['jam' => '12.00 - 13.00', 'ke' => '-', 'mapel' => 'Sholat Dzuhur Berjamaah & Makan', 'guru' => 'Koordinator Ibadah', 'ruang' => 'Masjid Sekolah', 'is_break' => true],
                        ['jam' => '13.00 - 14.20', 'ke' => '7-8', 'mapel' => 'Informatika', 'guru' => 'Ferry Andika, S.Kom', 'ruang' => 'Lab Komputer', 'is_break' => false],
                    ]
                ],
                [
                    'hari' => 'Rabu',
                    'items' => [
                        ['jam' => '07.30 - 08.50', 'ke' => '1-2', 'mapel' => 'Bahasa Arab', 'guru' => 'Ustadzah Halimah, Lc', 'ruang' => 'R. VII-1', 'is_break' => false],
                        ['jam' => '08.50 - 10.10', 'ke' => '3-4', 'mapel' => 'Pendidikan Jasmani & Olahraga (PJOK)', 'guru' => 'Dedi Kurniawan, S.Pd.Or', 'ruang' => 'Lapangan Olahraga', 'is_break' => false],
                        ['jam' => '10.10 - 10.40', 'ke' => '-', 'mapel' => 'Istirahat Pagi', 'guru' => '-', 'ruang' => '-', 'is_break' => true],
                        ['jam' => '10.40 - 12.00', 'ke' => '5-6', 'mapel' => 'Seni Budaya & Prakarya', 'guru' => 'Dewi Lestari, S.Sn', 'ruang' => 'Studio Seni', 'is_break' => false],
                    ]
                ],
            ];
        @endphp

        <!-- Schedule Cards by Day -->
        <div class="space-y-6">
            @foreach($scheduleSlots as $day)
                <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                    <div class="bg-soft/75 px-6 py-3.5 border-b border-border-soft flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                            <h3 class="font-bold text-darkgreen text-sm sm:text-base">Hari {{ $day['hari'] }}</h3>
                        </div>
                        <span class="text-xs text-muted-text font-medium">{{ count($day['items']) }} Sesi Terjadwal</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                            <thead class="bg-white text-muted-text font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="py-3 px-4 sm:px-6 w-20">Jam Ke</th>
                                    <th class="py-3 px-4 sm:px-6 w-36">Waktu</th>
                                    <th class="py-3 px-4 sm:px-6">Mata Pelajaran</th>
                                    <th class="py-3 px-4 sm:px-6">Guru Pengampu</th>
                                    <th class="py-3 px-4 sm:px-6">Ruangan</th>
                                    <th class="py-3 px-4 sm:px-6 text-right w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-soft">
                                @foreach($day['items'] as $item)
                                    <tr class="{{ $item['is_break'] ? 'bg-amber-50/40 text-amber-900 font-medium' : 'hover:bg-soft/40 transition-colors' }}">
                                        <td class="py-3.5 px-4 sm:px-6 font-bold {{ $item['is_break'] ? 'text-amber-800' : 'text-forest' }}">
                                            {{ $item['ke'] }}
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 font-mono text-xs text-muted-text">
                                            {{ $item['jam'] }}
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 font-bold text-darkgreen">
                                            {{ $item['mapel'] }}
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 text-muted-text">
                                            {{ $item['guru'] }}
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6">
                                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $item['is_break'] ? 'bg-amber-100/70 text-amber-800' : 'bg-soft text-darkgreen border border-border-soft' }}">
                                                {{ $item['ruang'] }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 text-right">
                                            @if(!$item['is_break'])
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <button type="button" class="p-1.5 rounded-lg hover:bg-soft text-muted-text hover:text-darkgreen transition-colors" title="Edit Jadwal">
                                                        <x-icon name="pencil-square" class="w-4 h-4" />
                                                    </button>
                                                    <button type="button" class="p-1.5 rounded-lg hover:bg-rose-50 text-muted-text hover:text-rose-600 transition-colors" title="Hapus Slot">
                                                        <x-icon name="trash" class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-[11px] text-amber-700 italic">Jeda Agenda</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts.admin>