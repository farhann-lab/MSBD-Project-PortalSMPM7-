<x-layouts.guru 
    title="Input Absensi Siswa" 
    activeMenu="absensi-siswa"
    :breadcrumbs="[
        ['label' => 'Akademik & Pembelajaran', 'url' => ''],
        ['label' => 'Input Absensi Siswa', 'url' => route('guru.absensi')],
    ]"
    :isWaliKelas="true"
    kelasPerwalian="VIII-B"
>
    @php
        $selectedClass = request('kelas', 'VII-B');
        
        // Dummy daftar siswa untuk absensi per kelas
        $sampleStudents = [
            ['nis' => '2401', 'nama' => 'Ahmad Rizky Pratama', 'gender' => 'L', 'status' => 'H'],
            ['nis' => '2402', 'nama' => 'Aisyah Putri Azzahra', 'gender' => 'P', 'status' => 'H'],
            ['nis' => '2403', 'nama' => 'Aldi Syahputra', 'gender' => 'L', 'status' => 'H'],
            ['nis' => '2404', 'nama' => 'Annisa Rahmadani', 'gender' => 'P', 'status' => 'S'],
            ['nis' => '2405', 'nama' => 'Bagas Wicaksono', 'gender' => 'L', 'status' => 'H'],
            ['nis' => '2406', 'nama' => 'Dinda Permata Sari', 'gender' => 'P', 'status' => 'H'],
            ['nis' => '2407', 'nama' => 'Fadhil Muhammad', 'gender' => 'L', 'status' => 'I'],
            ['nis' => '2408', 'nama' => 'Hanifah Zahra', 'gender' => 'P', 'status' => 'H'],
            ['nis' => '2409', 'nama' => 'Irfan Maulana', 'gender' => 'L', 'status' => 'H'],
            ['nis' => '2410', 'nama' => 'Nabila Syakira', 'gender' => 'P', 'status' => 'H'],
        ];
    @endphp

    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold uppercase tracking-wider">
                        Presensi Harian
                    </span>
                    <span class="text-xs text-muted-text">&bull; Pertemuan Tatap Muka</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Input Absensi Peserta Didik
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Isi kehadiran siswa per sesi pelajaran sesuai kelas dan mata pelajaran yang Anda ampu.
                </p>
            </div>

            <!-- Ringkasan Cepat Status Presensi -->
            <div class="flex items-center gap-2 text-xs">
                <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 font-bold border border-emerald-200">
                    Hadir: 8
                </span>
                <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-800 font-bold border border-blue-200">
                    Sakit: 1
                </span>
                <span class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-800 font-bold border border-amber-200">
                    Izin: 1
                </span>
                <span class="px-3 py-1.5 rounded-xl bg-red-50 text-red-800 font-bold border border-red-200">
                    Alpa: 0
                </span>
            </div>
        </div>

        <!-- Filter & Control Form -->
        <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs">
            <form action="{{ route('guru.absensi') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Pilih Kelas Rombel -->
                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1.5">
                        Kelas Rombel
                    </label>
                    <select name="kelas" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 rounded-xl bg-soft border border-border-soft text-xs sm:text-sm font-semibold text-darkgreen focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest">
                        <option value="VII-A" {{ $selectedClass === 'VII-A' ? 'selected' : '' }}>Kelas VII-A (30 Siswa)</option>
                        <option value="VII-B" {{ $selectedClass === 'VII-B' ? 'selected' : '' }}>Kelas VII-B (32 Siswa)</option>
                        <option value="VIII-A" {{ $selectedClass === 'VIII-A' ? 'selected' : '' }}>Kelas VIII-A (34 Siswa)</option>
                        <option value="VIII-B" {{ $selectedClass === 'VIII-B' ? 'selected' : '' }}>Kelas VIII-B (32 Siswa - Perwalian)</option>
                    </select>
                </div>

                <!-- Tanggal Pertemuan -->
                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1.5">
                        Tanggal Pertemuan
                    </label>
                    <input 
                        type="date" 
                        name="tanggal" 
                        value="{{ date('Y-m-d') }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-soft border border-border-soft text-xs sm:text-sm font-semibold text-darkgreen focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                    >
                </div>

                <!-- Jam / Sesi Pelajaran -->
                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1.5">
                        Jam / Pertemuan Ke-
                    </label>
                    <select name="pertemuan" class="w-full px-3.5 py-2.5 rounded-xl bg-soft border border-border-soft text-xs sm:text-sm font-semibold text-darkgreen focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest">
                        <option value="1">Jam Ke 1-2 (07.30 - 09.00)</option>
                        <option value="2">Jam Ke 3-4 (09.30 - 11.00)</option>
                        <option value="3">Jam Ke 5-6 (11.15 - 12.45)</option>
                    </select>
                </div>

                <!-- Materi / Pokok Bahasan -->
                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1.5">
                        Materi / Pokok Bahasan
                    </label>
                    <input 
                        type="text" 
                        placeholder="Mis. Bab 3: Klasifikasi Makhluk Hidup" 
                        value="Bab 3: Klasifikasi Makhluk Hidup"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-soft border border-border-soft text-xs sm:text-sm text-darkgreen focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                    >
                </div>

            </form>
        </div>

        <!-- Presensi Form & Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            
            <div class="p-5 border-b border-border-soft flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                    <h2 class="text-sm font-bold text-darkgreen">
                        Presensi Kelas {{ $selectedClass }} &bull; Mata Pelajaran IPA
                    </h2>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold transition-colors cursor-pointer border border-emerald-200">
                        Tandai Semua Hadir (H)
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6 w-14">No</th>
                            <th class="py-3.5 px-4 sm:px-6 w-28">NIS</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama Peserta Didik</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-20">L/P</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center w-64">Status Presensi</th>
                            <th class="py-3.5 px-4 sm:px-6">Catatan Pertemuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft/60">
                        @foreach($sampleStudents as $index => $student)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3 px-4 sm:px-6 font-medium text-muted-text">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3 px-4 sm:px-6 font-mono text-darkgreen font-semibold">
                                    {{ $student['nis'] }}
                                </td>
                                <td class="py-3 px-4 sm:px-6 font-bold text-darkgreen">
                                    {{ $student['nama'] }}
                                </td>
                                <td class="py-3 px-4 sm:px-6 text-center">
                                    <span class="px-2 py-0.5 rounded-sm {{ $student['gender'] === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }} text-[11px] font-bold">
                                        {{ $student['gender'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6 text-center">
                                    <div class="inline-flex items-center gap-1.5 p-1 rounded-xl bg-soft border border-border-soft">
                                        <!-- Hadir -->
                                        <label class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer transition-colors {{ $student['status'] === 'H' ? 'bg-emerald-600 text-white shadow-xs' : 'text-darkgreen hover:bg-white' }}">
                                            <input type="radio" name="status_{{ $student['nis'] }}" value="H" class="sr-only" {{ $student['status'] === 'H' ? 'checked' : '' }}>
                                            H
                                        </label>
                                        <!-- Sakit -->
                                        <label class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer transition-colors {{ $student['status'] === 'S' ? 'bg-blue-600 text-white shadow-xs' : 'text-darkgreen hover:bg-white' }}">
                                            <input type="radio" name="status_{{ $student['nis'] }}" value="S" class="sr-only" {{ $student['status'] === 'S' ? 'checked' : '' }}>
                                            S
                                        </label>
                                        <!-- Izin -->
                                        <label class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer transition-colors {{ $student['status'] === 'I' ? 'bg-amber-500 text-white shadow-xs' : 'text-darkgreen hover:bg-white' }}">
                                            <input type="radio" name="status_{{ $student['nis'] }}" value="I" class="sr-only" {{ $student['status'] === 'I' ? 'checked' : '' }}>
                                            I
                                        </label>
                                        <!-- Alpa -->
                                        <label class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer transition-colors {{ $student['status'] === 'A' ? 'bg-red-600 text-white shadow-xs' : 'text-darkgreen hover:bg-white' }}">
                                            <input type="radio" name="status_{{ $student['nis'] }}" value="A" class="sr-only" {{ $student['status'] === 'A' ? 'checked' : '' }}>
                                            A
                                        </label>
                                    </div>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <input 
                                        type="text" 
                                        placeholder="Keterangan..." 
                                        value="{{ $student['status'] === 'S' ? 'Surat dokter terlampir' : ($student['status'] === 'I' ? 'Izin acara keluarga' : '') }}"
                                        class="w-full px-3 py-1.5 rounded-lg bg-soft/50 border border-border-soft text-xs text-darkgreen focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-forest"
                                    >
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-border-soft flex items-center justify-between">
                <span class="text-xs text-muted-text">
                    Menampilkan 10 dari 32 peserta didik terdaftar di rombel ini.
                </span>
                <button type="button" class="px-6 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs transition-colors cursor-pointer flex items-center gap-2">
                    <x-icon name="check-circle" class="w-4 h-4 text-emerald-300" />
                    <span>Simpan Presensi Pertemuan</span>
                </button>
            </div>

        </div>

    </div>
</x-layouts.guru>
