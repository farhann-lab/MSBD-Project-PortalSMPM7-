<x-layouts.siswa 
    title="Absensi Saya" 
    activeMenu="absensi-siswa"
    :breadcrumbs="[
        ['label' => 'Absensi Saya', 'url' => route('siswa.absensi')],
    ]"
    kelasSiswa="VIII-B"
>
    @php
        // Riwayat absensi siswa pribadi (Ahmad Rizky Pratama - NIS 2401)
        // Siswa hanya melihat riwayat miliknya (DESIGN.md Section 10.4 & 11)
        $riwayatPresensi = [
            ['tanggal' => '07 Nov 2026', 'hari' => 'Kamis', 'mapel' => 'Pendidikan Pancasila & Kewarganegaraan', 'jam' => '07.30 - 09.00', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '07 Nov 2026', 'hari' => 'Kamis', 'mapel' => 'Seni Budaya & Prakarya', 'jam' => '09.30 - 11.00', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '06 Nov 2026', 'hari' => 'Rabu', 'mapel' => 'IPA Terpadu (Praktikum)', 'jam' => '07.30 - 09.00', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '06 Nov 2026', 'hari' => 'Rabu', 'mapel' => 'Informatika / TIK', 'jam' => '09.30 - 11.00', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '06 Nov 2026', 'hari' => 'Rabu', 'mapel' => 'Ilmu Pengetahuan Sosial', 'jam' => '11.15 - 12.45', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '05 Nov 2026', 'hari' => 'Selasa', 'mapel' => 'Pendidikan Agama Islam', 'jam' => '07.30 - 09.00', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '05 Nov 2026', 'hari' => 'Selasa', 'mapel' => 'Bahasa Inggris', 'jam' => '09.30 - 11.00', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '05 Nov 2026', 'hari' => 'Selasa', 'mapel' => 'Pendidikan Jasmani', 'jam' => '11.15 - 12.45', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '04 Nov 2026', 'hari' => 'Senin', 'mapel' => 'Matematika', 'jam' => '07.30 - 09.00', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
            ['tanggal' => '04 Nov 2026', 'hari' => 'Senin', 'mapel' => 'Bahasa Indonesia', 'jam' => '09.30 - 11.00', 'status' => 'H', 'keterangan' => 'Hadir tepat waktu'],
        ];
    @endphp

    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest text-white text-xs font-bold uppercase tracking-wider">
                        Rekapitulasi Kehadiran Pribadi
                    </span>
                    <span class="text-xs text-muted-text">&bull; Semester Ganjil 2026/2027</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Catatan Kehadiran &amp; Presensi Saya
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Pantau catatan presensi harian dari seluruh guru mata pelajaran yang mengajar di kelas VIII-B.
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs font-bold flex items-center gap-2 transition-colors cursor-pointer">
                    <x-icon name="printer" class="w-4 h-4 text-forest" />
                    <span>Cetak Lembar Presensi</span>
                </button>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Persentase Kehadiran</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">100%</p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">Predikat Disiplin Sempurna</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Total Hadir (H)</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">48 <span class="text-xs font-semibold text-muted-text">Pertemuan</span></p>
                <p class="text-[11px] text-muted-text mt-1">Hadir penuh di semua sesi</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Sakit (S) / Izin (I)</p>
                <p class="text-2xl font-black text-darkgreen mt-1">0 <span class="text-xs font-semibold text-muted-text">Kali</span></p>
                <p class="text-[11px] text-muted-text mt-1">Tidak ada izin/sakit</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Alpa (A)</p>
                <p class="text-2xl font-black text-darkgreen mt-1">0 <span class="text-xs font-semibold text-muted-text">Kali</span></p>
                <p class="text-[11px] text-muted-text mt-1">Kepatuhan kehadiran optimal</p>
            </div>

        </div>

        <!-- Table Riwayat Presensi -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="p-5 border-b border-border-soft flex items-center justify-between">
                <h2 class="text-sm font-bold text-darkgreen">
                    Riwayat Presensi Tatap Muka Terakhir
                </h2>
                <span class="text-xs text-muted-text font-medium">
                    10 Pertemuan Terakhir
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6 w-14">No</th>
                            <th class="py-3.5 px-4 sm:px-6 w-36">Tanggal</th>
                            <th class="py-3.5 px-4 sm:px-6">Mata Pelajaran</th>
                            <th class="py-3.5 px-4 sm:px-6 w-32">Waktu Sesi</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-28">Status</th>
                            <th class="py-3.5 px-4 sm:px-6">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft/60">
                        @foreach($riwayatPresensi as $index => $row)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-medium text-muted-text">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-medium text-darkgreen">
                                    {{ $row['tanggal'] }}
                                    <span class="block text-[11px] text-muted-text">{{ $row['hari'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-bold text-darkgreen">
                                    {{ $row['mapel'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-xs text-muted-text">
                                    {{ $row['jam'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200">
                                        Hadir
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-xs text-muted-text">
                                    {{ $row['keterangan'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-border-soft flex items-center justify-between text-xs text-muted-text">
                <span>Data tersinkronisasi langsung saat guru mata pelajaran mengisi absensi</span>
                <span>Siswa hanya dapat melihat riwayat kehadiran pribadi (DESIGN.md 11)</span>
            </div>
        </div>

    </div>
</x-layouts.siswa>
