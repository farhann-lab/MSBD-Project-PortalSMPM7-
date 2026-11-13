<x-layouts.siswa 
    title="Dashboard Siswa" 
    activeMenu="dashboard"
    kelasSiswa="VIII-B"
>
    <!-- Welcome Banner Siswa (DESIGN.md 10.4: Identitas, kelas, dan tahun ajaran aktif) -->
    <div class="mb-8 rounded-3xl bg-linear-to-r from-darkgreen to-forest p-6 sm:p-8 text-white relative overflow-hidden shadow-sm">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative z-10 max-w-2xl">   
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">
                Assalamu'alaikum, Ahmad Rizky Pratama!
            </h1>
            <p class="text-white/80 text-xs sm:text-sm leading-relaxed">
                Selamat datang di portal akademik Anda. Anda terdaftar aktif di <strong class="text-white">Kelas VIII-B</strong> di bawah bimbingan Wali Kelas <strong class="text-emerald-300">Bpk. Budi Santoso, S.Pd.</strong>
            </p>
        </div>
    </div>

    <!-- Quick Stats Cards (DESIGN.md 10.4: Ringkasan absensi pribadi, jadwal, materi) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        
        <!-- Stat 1: Kehadiran Pribadi -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Kehadiran Saya</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">100%</p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">48 Pertemuan Hadir Penuh</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <x-icon name="clipboard-document-check" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 2: Jadwal Pelajaran Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Jadwal Hari Ini</p>
                <p class="text-2xl font-black text-darkgreen mt-1">3 <span class="text-xs font-semibold text-muted-text">Mapel</span></p>
                <p class="text-[11px] text-forest font-semibold mt-1">07.30 - 12.45 WIB</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-forest/10 text-forest flex items-center justify-center shrink-0">
                <x-icon name="calendar" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 3: Materi Tersedia -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Materi Pelajaran</p>
                <p class="text-2xl font-black text-darkgreen mt-1">6 <span class="text-xs font-semibold text-muted-text">Bahan Ajar</span></p>
                <p class="text-[11px] text-forest font-semibold mt-1">Siap Dipelajari &amp; Diunduh</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-soft text-forest flex items-center justify-center shrink-0">
                <x-icon name="book-open" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 4: Status Akademik -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Status Pelajar</p>
                <p class="text-2xl font-black text-forest mt-1">Aktif</p>
                <p class="text-[11px] text-muted-text mt-1">NIS: 2401 &bull; NISN: 0098712301</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-forest/10 text-forest flex items-center justify-center shrink-0">
                <x-icon name="academic-cap" class="w-6 h-6" />
            </div>
        </div>

    </div>

    <!-- Main Workspace Two-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Left 2 Cols: Jadwal Hari Ini & Materi Pelajaran Terbaru -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card: Jadwal Pelajaran Hari Ini (Senin) -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                            <x-icon name="clock" class="w-4 h-4" />
                        </span>
                        <h2 class="text-base font-bold text-darkgreen">Jadwal Pelajaran Hari Ini (Senin)</h2>
                    </div>
                    <a href="{{ route('siswa.jadwal') }}" class="text-xs font-semibold text-forest hover:underline">
                        Lihat Jadwal Mingguan &rarr;
                    </a>
                </div>

                <div class="divide-y divide-border-soft/60 mt-2">
                    <!-- Sesi 1 -->
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen font-bold text-xs">
                                07.30 - 09.00
                            </span>
                            <div>
                                <p class="text-sm font-bold text-darkgreen">Matematika</p>
                                <p class="text-xs text-muted-text">Nadia Fitri, S.Pd. &bull; Ruang B-2</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200 self-start sm:self-auto">
                            Telah Selesai
                        </span>
                    </div>

                    <!-- Sesi 2 -->
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen font-bold text-xs">
                                09.30 - 11.00
                            </span>
                            <div>
                                <p class="text-sm font-bold text-darkgreen">Bahasa Indonesia</p>
                                <p class="text-xs text-muted-text">Faridah Hanum, S.Pd. &bull; Ruang B-2</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-forest text-white text-[11px] font-bold self-start sm:self-auto">
                            Sedang Berlangsung
                        </span>
                    </div>

                    <!-- Sesi 3 -->
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen font-bold text-xs">
                                11.15 - 12.45
                            </span>
                            <div>
                                <p class="text-sm font-bold text-darkgreen">IPA Terpadu</p>
                                <p class="text-xs text-muted-text">Budi Santoso, S.Pd. &bull; Ruang B-2</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-soft text-muted-text text-[11px] font-semibold self-start sm:self-auto">
                            Sesi Siang
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card: Materi Pembelajaran Terbaru -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                            <x-icon name="book-open" class="w-4 h-4" />
                        </span>
                        <h2 class="text-base font-bold text-darkgreen">Materi Pembelajaran Baru</h2>
                    </div>
                    <a href="{{ route('siswa.materi') }}" class="text-xs font-semibold text-forest hover:underline">
                        Semua Materi &rarr;
                    </a>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center shrink-0">
                                <x-icon name="document-text" class="w-5 h-5" />
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-darkgreen truncate">Sistem Gerak pada Manusia &amp; Tumbuhan</p>
                                <p class="text-[11px] text-muted-text">IPA Terpadu &bull; PDF (2.4 MB) &bull; Bpk. Budi Santoso</p>
                            </div>
                        </div>
                        <a href="{{ route('siswa.materi') }}" class="px-3 py-1.5 rounded-lg bg-forest hover:bg-forest/90 text-white text-xs font-semibold shrink-0 transition-colors">
                            Unduh
                        </a>
                    </div>

                    <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                                <x-icon name="arrow-top-right-on-square" class="w-5 h-5" />
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-darkgreen truncate">Video Praktikum: Uji Fotosintesis Percobaan Sachs</p>
                                <p class="text-[11px] text-muted-text">IPA Terpadu &bull; Video YouTube &bull; Bpk. Budi Santoso</p>
                            </div>
                        </div>
                        <a href="{{ route('siswa.materi') }}" class="px-3 py-1.5 rounded-lg bg-soft hover:bg-border-soft/60 text-darkgreen text-xs font-semibold shrink-0 transition-colors">
                            Tonton
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Ringkasan Absensi Pribadi & Info Kelas -->
        <div class="space-y-6">
            
            <!-- Card: Ringkasan Absensi Saya (DESIGN.md 10.4) -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h2 class="text-sm font-bold text-darkgreen">Absensi Semester Ini</h2>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Disiplin</span>
                </div>

                <div class="py-4 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-muted-text">Hadir (H):</span>
                        <span class="font-bold text-emerald-700">48 Kali</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-muted-text">Sakit (S):</span>
                        <span class="font-bold text-blue-700">0 Kali</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-muted-text">Izin (I):</span>
                        <span class="font-bold text-amber-700">0 Kali</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-muted-text">Tanpa Keterangan (A):</span>
                        <span class="font-bold text-darkgreen">0 Kali</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-border-soft">
                    <a href="{{ route('siswa.absensi') }}" class="w-full block text-center py-2 rounded-xl bg-forest/10 hover:bg-forest/20 text-forest text-xs font-bold transition-colors">
                        Lihat Riwayat Kehadiran Lengkap
                    </a>
                </div>
            </div>

            <!-- Card: Identitas Rombel & Kontak Sekolah -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <h3 class="text-sm font-bold text-darkgreen mb-3">Informasi Kelas &amp; Perwalian</h3>
                
                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-muted-text">Rombel:</span>
                        <span class="font-bold text-darkgreen">Kelas VIII-B</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-text">Wali Kelas:</span>
                        <span class="font-bold text-forest">Budi Santoso, S.Pd.</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-text">Ketua Kelas:</span>
                        <span class="font-semibold text-darkgreen">Ahmad Rizky P. (Anda)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-text">Kurikulum:</span>
                        <span class="font-semibold text-darkgreen">Kurikulum Merdeka</span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-border-soft text-[11px] text-muted-text leading-relaxed">
                    Butuh bantuan kendala akademik? Silakan hubungi wali kelas atau ruang BK sekolah.
                </div>
            </div>

        </div>

    </div>
</x-layouts.siswa>
