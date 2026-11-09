<x-layouts.guru 
    title="Pemantauan Absensi Kelas" 
    activeMenu="wali-kelas-absensi"
    :breadcrumbs="[
        ['label' => 'Perwalian (Kelas VIII-B)', 'url' => ''],
        ['label' => 'Pemantauan Absensi Kelas', 'url' => route('guru.perwalian.absensi')],
    ]"
    :isWaliKelas="true"
    kelasPerwalian="VIII-B"
>
    @php
        // Rekapitulasi presensi siswa perwalian VIII-B (Total 48 Pertemuan berjalan)
        $rekapAbsensi = [
            ['nis' => '2401', 'nama' => 'Ahmad Rizky Pratama', 'gender' => 'L', 'h' => 48, 's' => 0, 'i' => 0, 'a' => 0, 'persen' => '100%'],
            ['nis' => '2402', 'nama' => 'Aisyah Putri Azzahra', 'gender' => 'P', 'h' => 47, 's' => 1, 'i' => 0, 'a' => 0, 'persen' => '97.9%'],
            ['nis' => '2403', 'nama' => 'Aldi Syahputra', 'gender' => 'L', 'h' => 46, 's' => 1, 'i' => 1, 'a' => 0, 'persen' => '95.8%'],
            ['nis' => '2404', 'nama' => 'Annisa Rahmadani', 'gender' => 'P', 'h' => 45, 's' => 3, 'i' => 0, 'a' => 0, 'persen' => '93.7%'],
            ['nis' => '2405', 'nama' => 'Bagas Wicaksono', 'gender' => 'L', 'h' => 42, 's' => 2, 'i' => 1, 'a' => 3, 'persen' => '87.5%'], // Perlu Perhatian Alpa
            ['nis' => '2406', 'nama' => 'Dinda Permata Sari', 'gender' => 'P', 'h' => 48, 's' => 0, 'i' => 0, 'a' => 0, 'persen' => '100%'],
            ['nis' => '2407', 'nama' => 'Fadhil Muhammad', 'gender' => 'L', 'h' => 46, 's' => 0, 'i' => 2, 'a' => 0, 'persen' => '95.8%'],
            ['nis' => '2408', 'nama' => 'Hanifah Zahra', 'gender' => 'P', 'h' => 48, 's' => 0, 'i' => 0, 'a' => 0, 'persen' => '100%'],
            ['nis' => '2409', 'nama' => 'Irfan Maulana', 'gender' => 'L', 'h' => 47, 's' => 1, 'i' => 0, 'a' => 0, 'persen' => '97.9%'],
            ['nis' => '2410', 'nama' => 'Nabila Syakira', 'gender' => 'P', 'h' => 48, 's' => 0, 'i' => 0, 'a' => 0, 'persen' => '100%'],
        ];
    @endphp

    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest text-white text-xs font-bold uppercase tracking-wider">
                        Monitoring Wali Kelas
                    </span>
                    <span class="text-xs text-muted-text">&bull; Kelas VIII-B (32 Siswa)</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Pemantauan Absensi Kelas Perwalian
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Rekapitulasi kehadiran peserta didik gabungan dari seluruh mata pelajaran pada Semester Ganjil 2026/2027.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs font-bold flex items-center gap-2 transition-colors cursor-pointer">
                    <x-icon name="printer" class="w-4 h-4 text-forest" />
                    <span>Cetak Rekap Presensi</span>
                </button>
            </div>
        </div>

        <!-- Warning Alert Jika ada Siswa dengan Alpa >= 3 -->
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3 shadow-xs">
            <x-icon name="exclamation-circle" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
            <div class="text-xs sm:text-sm leading-relaxed">
                <strong class="font-bold text-amber-950">Perhatian Wali Kelas:</strong> 
                Terdapat 1 siswa (<span class="font-bold">Bagas Wicaksono</span>) dengan akumulasi 3 kali Tanpa Keterangan (Alpa). Disarankan melakukan konfirmasi orang tua atau berkoordinasi dengan Guru Bimbingan Konseling (BK).
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Rata-rata Hadir (H)</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">96.8%</p>
                <p class="text-[11px] text-muted-text mt-1">46.5 Sesi per Siswa</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Total Sakit (S)</p>
                <p class="text-2xl font-black text-blue-700 mt-1">8 <span class="text-xs font-semibold text-muted-text">Kasus</span></p>
                <p class="text-[11px] text-blue-600 font-semibold mt-1">Surat Keterangan Lengkap</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Total Izin (I)</p>
                <p class="text-2xl font-black text-amber-700 mt-1">5 <span class="text-xs font-semibold text-muted-text">Kasus</span></p>
                <p class="text-[11px] text-amber-600 font-semibold mt-1">Izin Kegiatan &amp; Keluarga</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Total Alpa (A)</p>
                <p class="text-2xl font-black text-red-600 mt-1">3 <span class="text-xs font-semibold text-muted-text">Sesi</span></p>
                <p class="text-[11px] text-red-700 font-semibold mt-1">Perlu Pembinaan BK</p>
            </div>

        </div>

        <!-- Rekapitulasi Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            
            <div class="p-5 border-b border-border-soft flex items-center justify-between">
                <h2 class="text-sm font-bold text-darkgreen">
                    Rekapitulasi Presensi Kumulatif Kelas VIII-B
                </h2>
                <span class="text-xs text-muted-text font-medium">
                    Total 48 Jam Tatap Muka Terlaksana
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6 w-14">No</th>
                            <th class="py-3.5 px-4 sm:px-6 w-28">NIS</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama Peserta Didik</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-20">L/P</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-20 text-emerald-800">Hadir</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-20 text-blue-800">Sakit</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-20 text-amber-800">Izin</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-20 text-red-800">Alpa</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-28">% Kehadiran</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-36">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft/60">
                        @foreach($rekapAbsensi as $index => $row)
                            <tr class="hover:bg-soft/40 transition-colors {{ $row['a'] >= 3 ? 'bg-red-50/40' : '' }}">
                                <td class="py-3.5 px-4 sm:px-6 font-medium text-muted-text">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-darkgreen font-bold">
                                    {{ $row['nis'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-bold text-darkgreen">
                                    {{ $row['nama'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2 py-0.5 rounded-sm {{ $row['gender'] === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }} text-[11px] font-bold">
                                        {{ $row['gender'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-emerald-700">
                                    {{ $row['h'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-blue-700">
                                    {{ $row['s'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-amber-700">
                                    {{ $row['i'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-black {{ $row['a'] > 0 ? 'text-red-600' : 'text-muted-text' }}">
                                    {{ $row['a'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-black text-darkgreen">
                                    {{ $row['persen'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($row['a'] >= 3)
                                        <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold border border-red-300">
                                            Perhatian BK
                                        </span>
                                    @elseif($row['persen'] === '100%')
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200">
                                            Sempurna
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-soft text-darkgreen text-xs font-semibold">
                                            Normal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-border-soft flex items-center justify-between text-xs text-muted-text">
                <span>Tampilan ringkasan perwalian kelas</span>
                <span>Diperbarui otomatis saat guru mata pelajaran menyimpan presensi</span>
            </div>

        </div>

    </div>
</x-layouts.guru>
