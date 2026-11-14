<x-layouts.guru 
    title="Dashboard Guru" 
    activeMenu="dashboard"
    :isWaliKelas="true"
    kelasPerwalian="VIII-B"
>
    <!-- Welcome Banner Section (DESIGN.md Section 10.2: Sapaan dan identitas guru) -->
    <div class="mb-8 rounded-3xl bg-linear-to-r from-darkgreen to-forest p-6 sm:p-8 text-white relative overflow-hidden shadow-sm">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-200 text-xs font-semibold uppercase tracking-wider mb-3 border border-white/15">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Tahun Ajaran 2026/2027 Semester 1</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">
                Selamat Datang, {{ auth()->user()->username ?? 'Budi Santoso, S.Pd.' }}! 👋
            </h1>
            <p class="text-white/80 text-xs sm:text-sm leading-relaxed">
                Portal Guru SIAKAD SMP Muhammadiyah 7 Medan. Anda mengampu <strong class="text-white">4 Rombel</strong> mata pelajaran <strong class="text-white">IPA</strong> serta bertugas sebagai <strong class="text-emerald-300">Wali Kelas VIII-B</strong>.
            </p>
        </div>
    </div>

    <!-- Quick Stats Cards (DESIGN.md 10.2: Kelas & Mapel diampu, Jadwal hari ini, Absensi, Materi) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        
        <!-- Stat 1: Rombel Diampu -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Kelas Diampu</p>
                <p class="text-2xl font-black text-darkgreen mt-1">4 <span class="text-xs font-semibold text-muted-text">Rombel</span></p>
                <p class="text-[11px] text-forest font-semibold mt-1">VII-A, VII-B, VIII-A, VIII-B</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-forest/10 text-forest flex items-center justify-center shrink-0">
                <x-icon name="building-office" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 2: Jadwal Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Jadwal Hari Ini</p>
                <p class="text-2xl font-black text-darkgreen mt-1">3 <span class="text-xs font-semibold text-muted-text">Pertemuan</span></p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">Berikutnya: 09.30 WIB (VIII-A)</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <x-icon name="calendar" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 3: Absensi Perlu Diisi -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Absensi Siswa</p>
                <p class="text-2xl font-black text-amber-600 mt-1">1 <span class="text-xs font-semibold text-muted-text">Perlu Diisi</span></p>
                <p class="text-[11px] text-amber-700 font-semibold mt-1">Kelas VII-B (Jam Ke-1)</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <x-icon name="clipboard-document-check" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 4: Materi Pembelajaran -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Materi Dikelola</p>
                <p class="text-2xl font-black text-darkgreen mt-1">8 <span class="text-xs font-semibold text-muted-text">Dokumen</span></p>
                <p class="text-[11px] text-forest font-semibold mt-1">Materi &amp; Tugas Mandiri</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-soft text-forest flex items-center justify-center shrink-0">
                <x-icon name="book-open" class="w-6 h-6" />
            </div>
        </div>

    </div>

    <!-- Main Workspace Two-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Left 2 Cols: Jadwal Mengajar Hari Ini & Form Absensi Cepat -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card: Jadwal Mengajar Hari Ini -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                            <x-icon name="clock" class="w-4 h-4" />
                        </span>
                        <h2 class="text-base font-bold text-darkgreen">Jadwal Mengajar Hari Ini (Senin)</h2>
                    </div>
                    <span class="text-xs font-semibold text-forest">3 Sesi Terjadwal</span>
                </div>

                <div class="divide-y divide-border-soft/60 mt-2">
                    <!-- Sesi 1 -->
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen font-bold text-xs">
                                07.30 - 09.00
                            </span>
                            <div>
                                <p class="text-sm font-bold text-darkgreen">IPA Terpadu — Kelas VII-B</p>
                                <p class="text-xs text-muted-text">Ruang Belajar Gedung B Lt. 2 &bull; 32 Siswa</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200/60 text-[11px] font-bold">
                                Belum Absen
                            </span>
                            <button type="button" class="px-3 py-1.5 rounded-lg bg-forest text-white text-xs font-semibold hover:bg-forest/90 cursor-pointer">
                                Isi Absensi
                            </button>
                        </div>
                    </div>

                    <!-- Sesi 2 -->
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen font-bold text-xs">
                                09.30 - 11.00
                            </span>
                            <div>
                                <p class="text-sm font-bold text-darkgreen">IPA Terpadu — Kelas VIII-A</p>
                                <p class="text-xs text-muted-text">Laboratorium IPA &bull; 34 Siswa</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full bg-soft text-muted-text text-[11px] font-semibold">
                                Sesi Berikutnya
                            </span>
                        </div>
                    </div>

                    <!-- Sesi 3 -->
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen font-bold text-xs">
                                11.15 - 12.45
                            </span>
                            <div>
                                <p class="text-sm font-bold text-darkgreen">IPA Terpadu — Kelas VIII-B (Perwalian)</p>
                                <p class="text-xs text-muted-text">Ruang Belajar Gedung B Lt. 2 &bull; 32 Siswa</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full bg-soft text-muted-text text-[11px] font-semibold">
                                Siang
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Materi Pembelajaran Aktif -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                            <x-icon name="book-open" class="w-4 h-4" />
                        </span>
                        <h2 class="text-base font-bold text-darkgreen">Materi Pembelajaran Terakhir</h2>
                    </div>
                    <button type="button" class="text-xs font-semibold text-forest hover:underline">
                        + Unggah Materi
                    </button>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="p-3 rounded-xl bg-soft/50 border border-border-soft flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                                <x-icon name="clipboard-check" class="w-4 h-4" />
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-darkgreen truncate">Sistem Gerak pada Manusia &amp; Tumbuhan</p>
                                <p class="text-[11px] text-muted-text">Kelas VIII &bull; PDF (2.4 MB) &bull; Diunggah 3 Nov 2026</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-forest shrink-0">84 Diunduh</span>
                    </div>

                    <div class="p-3 rounded-xl bg-soft/50 border border-border-soft flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-9 h-9 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center shrink-0">
                                <x-icon name="arrow-top-right-on-square" class="w-4 h-4" />
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-darkgreen truncate">Video Praktikum: Fotosintesis &amp; Percobaan Sachs</p>
                                <p class="text-[11px] text-muted-text">Kelas VIII &bull; Tautan YouTube &bull; Diunggah 1 Nov 2026</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-forest shrink-0">62 Dilihat</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Panel Khusus Wali Kelas (DESIGN.md 10.3) & Tautan Cepat RKM -->
        <div class="space-y-6">
            
            <!-- Card Khusus: Perwalian Kelas VIII-B (DESIGN.md 10.3) -->
            <div class="bg-white rounded-2xl border border-emerald-300/80 p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-forest/5 rounded-bl-full pointer-events-none"></div>
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                        <h2 class="text-sm font-bold text-darkgreen">Perwalian Kelas VIII-B</h2>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-forest/10 text-forest text-[11px] font-bold">Wali Aktif</span>
                </div>

                <div class="py-4 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-muted-text">Total Peserta Didik:</span>
                        <span class="font-bold text-darkgreen">32 Siswa (18 L / 14 P)</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-muted-text">Kehadiran Hari Ini:</span>
                        <span class="font-bold text-emerald-700">96.8% (31 Hadir, 1 Sakit)</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-muted-text">Ketua Kelas:</span>
                        <span class="font-semibold text-darkgreen">Ahmad Rizky Pratama</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-border-soft flex flex-col gap-2">
                    <button type="button" class="w-full py-2 rounded-xl bg-forest/10 hover:bg-forest/20 text-forest text-xs font-bold transition-colors">
                        Lihat Daftar Siswa VIII-B
                    </button>
                    <button type="button" class="w-full py-2 rounded-xl bg-soft hover:bg-border-soft/60 text-darkgreen text-xs font-semibold transition-colors">
                        Rekap Absensi Mingguan
                    </button>
                </div>
            </div>

            <!-- Card: Tautan Cepat RKM (DESIGN.md 10.2: Tautan aplikasi RKM di sidebar / panel) -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                        <x-icon name="arrow-top-right-on-square" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-darkgreen">Aplikasi Nilai RKM</h3>
                        <p class="text-[11px] text-muted-text">Integrasi Pengolahan Raport</p>
                    </div>
                </div>
                <p class="text-xs text-muted-text leading-relaxed mb-4">
                    Sistem Rencana Kerja &amp; Manajemen Nilai Raport Kurikulum Merdeka terhubung untuk penginputan formatif &amp; sumatif.
                </p>
                <a 
                    href="http://localhost:8080/rkm" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold transition-all shadow-xs"
                >
                    <span>Masuk ke Aplikasi RKM</span>
                    <x-icon name="arrow-top-right-on-square" class="w-4 h-4" />
                </a>
            </div>

        </div>

    </div>
</x-layouts.guru>
