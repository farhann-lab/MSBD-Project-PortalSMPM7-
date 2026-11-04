<x-layouts.admin 
    title="Data Mata Pelajaran" 
    activeMenu="master-mapel"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.mapel')],
        ['label' => 'Mata Pelajaran', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Mata Pelajaran Kurikulum Merdeka & Kemuhammadiyahan</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Daftar mata pelajaran aktif (m_mapel) yang terhubung ke jadwal pelajaran dan penugasan guru pengampu.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="book-open" class="w-4 h-4" />
                    <span>Tambah Mata Pelajaran</span>
                </button>
            </div>
        </div>

        @php
            $subjects = [
                ['kode' => 'PAI-01', 'nama' => 'Pendidikan Agama Islam & Budi Pekerti', 'kategori' => 'Agama & Karakter', 'guru' => 2, 'beban' => '3 Jam/Minggu'],
                ['kode' => 'KMD-01', 'nama' => 'Kemuhammadiyahan (AIK)', 'kategori' => 'Muatan Khas', 'guru' => 2, 'beban' => '2 Jam/Minggu'],
                ['kode' => 'ARB-01', 'nama' => 'Bahasa Arab', 'kategori' => 'Bahasa Asing', 'guru' => 1, 'beban' => '2 Jam/Minggu'],
                ['kode' => 'PPKN-01', 'nama' => 'Pendidikan Pancasila & Kewarganegaraan', 'kategori' => 'Umum', 'guru' => 1, 'beban' => '3 Jam/Minggu'],
                ['kode' => 'BIN-01', 'nama' => 'Bahasa Indonesia', 'kategori' => 'Bahasa', 'guru' => 2, 'beban' => '4 Jam/Minggu'],
                ['kode' => 'MAT-01', 'nama' => 'Matematika', 'kategori' => 'Eksakta', 'guru' => 2, 'beban' => '4 Jam/Minggu'],
                ['kode' => 'IPA-01', 'nama' => 'Ilmu Pengetahuan Alam (IPA)', 'kategori' => 'Eksakta', 'guru' => 2, 'beban' => '4 Jam/Minggu'],
                ['kode' => 'IPS-01', 'nama' => 'Ilmu Pengetahuan Sosial (IPS)', 'kategori' => 'Sosial', 'guru' => 1, 'beban' => '3 Jam/Minggu'],
                ['kode' => 'BIG-01', 'nama' => 'Bahasa Inggris', 'kategori' => 'Bahasa Asing', 'guru' => 1, 'beban' => '3 Jam/Minggu'],
                ['kode' => 'PJOK-01', 'nama' => 'Pendidikan Jasmani, Olahraga & Kesehatan', 'kategori' => 'Kesehatan & Raga', 'guru' => 2, 'beban' => '3 Jam/Minggu'],
                ['kode' => 'INF-01', 'nama' => 'Informatika / TIK', 'kategori' => 'Teknologi', 'guru' => 1, 'beban' => '2 Jam/Minggu'],
                ['kode' => 'SBK-01', 'nama' => 'Seni Budaya & Prakarya', 'kategori' => 'Seni', 'guru' => 1, 'beban' => '2 Jam/Minggu'],
                ['kode' => 'BMR-01', 'nama' => 'Muatan Lokal / Budaya Melayu', 'kategori' => 'Muatan Lokal', 'guru' => 1, 'beban' => '2 Jam/Minggu'],
                ['kode' => 'BK-01', 'nama' => 'Bimbingan Konseling', 'kategori' => 'Layanan Khusus', 'guru' => 1, 'beban' => '1 Jam/Minggu'],
            ];
        @endphp

        <!-- Master Data Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Kode</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama Mata Pelajaran</th>
                            <th class="py-3.5 px-4 sm:px-6">Kategori</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Beban Mengajar</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Jumlah Guru</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @foreach($subjects as $s)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-mono font-bold text-forest text-xs">{{ $s['kode'] }}</td>
                                <td class="py-3.5 px-4 sm:px-6 font-bold text-darkgreen">{{ $s['nama'] }}</td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen text-xs border border-border-soft">
                                        {{ $s['kategori'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-xs text-muted-text">{{ $s['beban'] }}</td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        {{ $s['guru'] }} Pengampu
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-soft transition-colors" title="Edit Mapel">
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
                <span>Total: <strong>14 Mata Pelajaran</strong> Aktif Terdaftar</span>
                <span class="text-forest font-semibold">Tahun Ajaran 2026/2027</span>
            </div>
        </div>

    </div>
</x-layouts.admin>
