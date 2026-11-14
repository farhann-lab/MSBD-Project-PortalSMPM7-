<x-layouts.guru 
    title="Daftar Siswa Perwalian" 
    activeMenu="wali-kelas-siswa"
    :breadcrumbs="[
        ['label' => 'Perwalian (Kelas VIII-B)', 'url' => ''],
        ['label' => 'Daftar Siswa Perwalian', 'url' => route('guru.perwalian.siswa')],
    ]"
    :isWaliKelas="true"
    kelasPerwalian="VIII-B"
>
    @php
        // Dummy data siswa kelas VIII-B perwalian sesuai batasan DIR-8 (tanpa kebocoran privasi sensitif)
        $studentsPerwalian = [
            ['nis' => '2401', 'nisn' => '0098712301', 'nama' => 'Ahmad Rizky Pratama', 'gender' => 'L', 'status' => 'Aktif', 'role_kelas' => 'Ketua Kelas'],
            ['nis' => '2402', 'nisn' => '0098712302', 'nama' => 'Aisyah Putri Azzahra', 'gender' => 'P', 'status' => 'Aktif', 'role_kelas' => 'Sekretaris'],
            ['nis' => '2403', 'nisn' => '0098712303', 'nama' => 'Aldi Syahputra', 'gender' => 'L', 'status' => 'Aktif', 'role_kelas' => '-'],
            ['nis' => '2404', 'nisn' => '0098712304', 'nama' => 'Annisa Rahmadani', 'gender' => 'P', 'status' => 'Aktif', 'role_kelas' => 'Bendahara'],
            ['nis' => '2405', 'nisn' => '0098712305', 'nama' => 'Bagas Wicaksono', 'gender' => 'L', 'status' => 'Aktif', 'role_kelas' => '-'],
            ['nis' => '2406', 'nisn' => '0098712306', 'nama' => 'Dinda Permata Sari', 'gender' => 'P', 'status' => 'Aktif', 'role_kelas' => '-'],
            ['nis' => '2407', 'nisn' => '0098712307', 'nama' => 'Fadhil Muhammad', 'gender' => 'L', 'status' => 'Aktif', 'role_kelas' => '-'],
            ['nis' => '2408', 'nisn' => '0098712308', 'nama' => 'Hanifah Zahra', 'gender' => 'P', 'status' => 'Aktif', 'role_kelas' => '-'],
            ['nis' => '2409', 'nisn' => '0098712309', 'nama' => 'Irfan Maulana', 'gender' => 'L', 'status' => 'Aktif', 'role_kelas' => '-'],
            ['nis' => '2410', 'nisn' => '0098712310', 'nama' => 'Nabila Syakira', 'gender' => 'P', 'status' => 'Aktif', 'role_kelas' => '-'],
            ['nis' => '2411', 'nisn' => '0098712311', 'nama' => 'Rafi Pratama', 'gender' => 'L', 'status' => 'Aktif', 'role_kelas' => '-'],
            ['nis' => '2412', 'nisn' => '0098712312', 'nama' => 'Siti Khadijah', 'gender' => 'P', 'status' => 'Aktif', 'role_kelas' => '-'],
        ];
    @endphp

    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest text-white text-xs font-bold uppercase tracking-wider">
                        Perwalian Kelas VIII-B
                    </span>
                    <span class="text-xs text-muted-text">&bull; Tahun Ajaran 2026/2027</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Daftar Siswa Perwalian
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Informasi rombel perwalian di bawah bimbingan Anda sebagai Wali Kelas.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs font-bold flex items-center gap-2 transition-colors cursor-pointer">
                    <x-icon name="printer" class="w-4 h-4 text-forest" />
                    <span>Cetak Leger / Absen</span>
                </button>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Total Siswa</p>
                <p class="text-2xl font-black text-darkgreen mt-1">32 <span class="text-xs font-semibold text-muted-text">Peserta Didik</span></p>
                <p class="text-[11px] text-forest font-semibold mt-1">100% Terdaftar Aktif</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Laki-Laki</p>
                <p class="text-2xl font-black text-blue-700 mt-1">18 <span class="text-xs font-semibold text-muted-text">Siswa</span></p>
                <p class="text-[11px] text-muted-text mt-1">56.2% dari rombel</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Perempuan</p>
                <p class="text-2xl font-black text-pink-700 mt-1">14 <span class="text-xs font-semibold text-muted-text">Siswi</span></p>
                <p class="text-[11px] text-muted-text mt-1">43.8% dari rombel</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Kehadiran Rata-rata</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">96.8%</p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">Predikat Sangat Disiplin</p>
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
                    placeholder="Cari nama atau NIS siswa perwalian..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2">
                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Jenis Kelamin</option>
                    <option value="L">Laki-Laki (18)</option>
                    <option value="P">Perempuan (14)</option>
                </select>
                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Status</option>
                    <option value="Aktif" selected>Aktif</option>
                    <option value="Cuti">Cuti</option>
                </select>
            </div>
        </div>

        <!-- Table Data Siswa Perwalian -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6 w-14">No</th>
                            <th class="py-3.5 px-4 sm:px-6 w-28">NIS</th>
                            <th class="py-3.5 px-4 sm:px-6 w-32">NISN</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama Lengkap Peserta Didik</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-20">L/P</th>
                            <th class="py-3.5 px-4 sm:px-6">Jabatan di Kelas</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-28">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft/60">
                        @foreach($studentsPerwalian as $index => $item)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-medium text-muted-text">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-darkgreen font-bold">
                                    {{ $item['nis'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-muted-text text-xs">
                                    {{ $item['nisn'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="font-bold text-darkgreen">{{ $item['nama'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2 py-0.5 rounded-sm {{ $item['gender'] === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }} text-[11px] font-bold">
                                        {{ $item['gender'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    @if($item['role_kelas'] !== '-')
                                        <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold">
                                            {{ $item['role_kelas'] }}
                                        </span>
                                    @else
                                        <span class="text-muted-text text-xs">Anggota Rombel</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <a href="{{ route('guru.perwalian.catatan') }}" class="px-3 py-1.5 rounded-lg bg-soft hover:bg-forest hover:text-white text-darkgreen text-xs font-semibold transition-colors inline-block">
                                        Catatan / BK
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-border-soft flex items-center justify-between text-xs text-muted-text">
                <span>Menampilkan 12 dari 32 siswa perwalian Kelas VIII-B</span>
                <span>Semester Ganjil 2026/2027</span>
            </div>
        </div>

    </div>
</x-layouts.guru>
