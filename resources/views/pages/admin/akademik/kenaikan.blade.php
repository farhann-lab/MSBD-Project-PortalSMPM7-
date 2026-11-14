<x-layouts.admin 
    title="Kenaikan Kelas & Kelulusan" 
    activeMenu="akademik-kenaikan"
    :breadcrumbs="[
        ['label' => 'Akademik', 'url' => route('admin.akademik.kenaikan')],
        ['label' => 'Kenaikan Kelas', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Proses Kenaikan Kelas & Kelulusan Siswa (riwayat_kelas)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Migrasi data siswa antar tingkat rombel dan penetapan status kelulusan untuk angkatan akhir berdasarkan kriteria akademik.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="arrow-right-circle" class="w-4 h-4" />
                    <span>Eksekusi Kenaikan Terpilih</span>
                </button>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Rombel Asal</span>
                <div class="text-xl font-bold text-darkgreen mt-1">Kelas VII-1</div>
                <span class="text-xs text-muted-text mt-0.5 block">Wali Kelas: Dra. Nurhayati</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Total Siswa Terdaftar</span>
                <div class="text-xl font-bold text-darkgreen mt-1">32 Siswa</div>
                <span class="text-xs text-emerald-600 mt-0.5 block font-medium">100% Nilai Rapor Terinput</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Memenuhi Syarat Naik</span>
                <div class="text-xl font-bold text-emerald-600 mt-1">30 Siswa</div>
                <span class="text-xs text-emerald-700/80 mt-0.5 block">Kehadiran > 85%, Nilai Tuntas</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-700">Perlu Evaluasi Khusus</span>
                <div class="text-xl font-bold text-amber-600 mt-1">2 Siswa</div>
                <span class="text-xs text-amber-700/80 mt-0.5 block">Memerlukan Rapat Pleno Dewan Guru</span>
            </div>
        </div>

        <!-- Setup Rombel Asal & Tujuan Form -->
        <div class="p-6 rounded-3xl bg-soft/60 border border-border-soft shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-darkgreen uppercase tracking-wider flex items-center gap-2">
                <x-icon name="academic-cap" class="w-4 h-4 text-forest" />
                <span>Konfigurasi Migrasi Rombel Periode 2026/2027</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-darkgreen mb-1.5">1. Pilih Rombel Asal (Saat Ini)</label>
                    <select class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="VII-1" selected>Kelas VII-1 (T.A 2025/2026)</option>
                        <option value="VII-2">Kelas VII-2 (T.A 2025/2026)</option>
                        <option value="VIII-1">Kelas VIII-1 (T.A 2025/2026)</option>
                        <option value="VIII-2">Kelas VIII-2 (T.A 2025/2026)</option>
                        <option value="IX-1">Kelas IX-1 (Kelulusan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-darkgreen mb-1.5">2. Target Aksi Kenaikan</label>
                    <select class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="naik" selected>Naik ke Tingkat Berikutnya (Kelas VIII)</option>
                        <option value="tetap">Tinggal di Kelas yang Sama</option>
                        <option value="lulus">Lulus / Masuk Data Alumni (Khusus Kelas IX)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-darkgreen mb-1.5">3. Alokasi Rombel Tujuan</label>
                    <select class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="VIII-1" selected>Kelas VIII-1 (T.A 2026/2027)</option>
                        <option value="VIII-2">Kelas VIII-2 (T.A 2026/2027)</option>
                        <option value="VIII-3">Kelas VIII-3 (T.A 2026/2027)</option>
                    </select>
                </div>
            </div>
        </div>

        @php
            $sampleCandidates = [
                ['nisn' => '0098231001', 'nama' => 'Aditya Pratama Putra', 'kehadiran' => '98.5%', 'rata_rata' => '88.4', 'catatan' => 'Sangat Baik & Aktif IPM', 'status' => 'Naik Kelas', 'eligible' => true],
                ['nisn' => '0098231002', 'nama' => 'Aisyah Humaira', 'kehadiran' => '100%', 'rata_rata' => '92.1', 'catatan' => 'Juara 1 Rombel VII-1', 'status' => 'Naik Kelas', 'eligible' => true],
                ['nisn' => '0098231003', 'nama' => 'Bilal Ramadhan', 'kehadiran' => '96.0%', 'rata_rata' => '85.6', 'catatan' => 'Baik', 'status' => 'Naik Kelas', 'eligible' => true],
                ['nisn' => '0098231004', 'nama' => 'Dimas Arya Saputra', 'kehadiran' => '81.2%', 'rata_rata' => '73.2', 'catatan' => 'Absensi perlu pendampingan wali', 'status' => 'Tinjau Ulang', 'eligible' => false],
                ['nisn' => '0098231005', 'nama' => 'Farah Nabilah', 'kehadiran' => '95.8%', 'rata_rata' => '87.0', 'catatan' => 'Baik & Tuntas', 'status' => 'Naik Kelas', 'eligible' => true],
                ['nisn' => '0098231006', 'nama' => 'Gibran Al-Ghifari', 'kehadiran' => '97.2%', 'rata_rata' => '89.5', 'catatan' => 'Tahfidz Juz 30 Mumtaz', 'status' => 'Naik Kelas', 'eligible' => true],
                ['nisn' => '0098231007', 'nama' => 'Hafizh Pratama', 'kehadiran' => '83.0%', 'rata_rata' => '74.1', 'catatan' => 'Remedial Mapel Matematika', 'status' => 'Tinjau Ulang', 'eligible' => false],
            ];
        @endphp

        <!-- Students Selection Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="p-5 border-b border-border-soft flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="check-all" class="rounded-md border-border-soft text-forest focus:ring-mint w-4 h-4" checked>
                    <label for="check-all" class="text-xs font-bold text-darkgreen">Pilih Seluruh Siswa Layak (5 Siswa)</label>
                </div>
                <div class="text-xs text-muted-text">
                    Menampilkan daftar rekomendasi berdasarkan rekap semester genap
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6 w-12 text-center">Pilih</th>
                            <th class="py-3.5 px-4 sm:px-6">NISN & Nama Siswa</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Kehadiran</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Rata-rata Rapor</th>
                            <th class="py-3.5 px-4 sm:px-6">Catatan Rekomendasi Wali</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Rekomendasi</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach($sampleCandidates as $c)
                            <tr class="hover:bg-soft/40 transition-colors {{ !$c['eligible'] ? 'bg-amber-50/20' : '' }}">
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <input type="checkbox" class="rounded-md border-border-soft text-forest focus:ring-mint w-4 h-4" {{ $c['eligible'] ? 'checked' : '' }}>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-darkgreen">{{ $c['nama'] }}</div>
                                    <div class="text-[11px] font-mono text-muted-text">{{ $c['nisn'] }}</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-medium">
                                    <span class="{{ (float)$c['kehadiran'] < 85 ? 'text-amber-700 font-bold' : 'text-emerald-700' }}">
                                        {{ $c['kehadiran'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-darkgreen">
                                    {{ $c['rata_rata'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text text-xs">
                                    {{ $c['catatan'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $c['eligible'] ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $c['status'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <button type="button" class="text-xs font-semibold text-forest hover:underline">
                                        Rincian Nilai
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