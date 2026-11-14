<x-layouts.guru 
    title="Kelas & Mata Pelajaran" 
    activeMenu="kelas-mapel"
    :breadcrumbs="[
        ['label' => 'Akademik & Pembelajaran', 'url' => ''],
        ['label' => 'Kelas & Mata Pelajaran', 'url' => route('guru.kelas-mapel')],
    ]"
    :isWaliKelas="true"
    kelasPerwalian="VIII-B"
>
    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold uppercase tracking-wider">
                        Tahun Ajaran 2026/2027
                    </span>
                    <span class="text-xs text-muted-text">&bull; Semester Ganjil</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Kelas &amp; Mata Pelajaran Diampu
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Daftar rombongan belajar (rombel) yang berada di bawah penugasan mengajar mata pelajaran Ilmu Pengetahuan Alam (IPA).
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-soft text-darkgreen text-xs font-bold border border-border-soft">
                    Total 4 Rombel &bull; 128 Siswa
                </span>
            </div>
        </div>

        <!-- Class Cards Grid (4 Rombel) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Rombel 1: VII-A -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs p-6 flex flex-col justify-between hover:border-forest/40 transition-colors">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                        <div class="flex items-center gap-3">
                            <span class="w-12 h-12 rounded-2xl bg-forest/10 text-forest flex items-center justify-center font-black text-base shrink-0">
                                7A
                            </span>
                            <div>
                                <h2 class="text-lg font-black text-darkgreen">Kelas VII-A</h2>
                                <p class="text-xs text-muted-text">Tingkat VII &bull; Kurikulum Merdeka</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                            Aktif
                        </span>
                    </div>

                    <div class="py-4 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Mata Pelajaran:</span>
                            <span class="font-bold text-darkgreen">Ilmu Pengetahuan Alam (IPA)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Beban Mengajar:</span>
                            <span class="font-bold text-darkgreen">4 Jam Pelajaran (JP) / Minggu</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Jumlah Siswa:</span>
                            <span class="font-bold text-darkgreen">30 Siswa (16 L / 14 P)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Wali Kelas:</span>
                            <span class="font-semibold text-darkgreen">Aisyah Rahmawati, S.Pd.</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-border-soft flex items-center justify-between gap-3">
                    <a href="{{ route('guru.absensi') }}?kelas=VII-A" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold transition-colors inline-flex items-center gap-2">
                        <x-icon name="clipboard-document-check" class="w-4 h-4" />
                        <span>Input Absensi</span>
                    </a>
                    <span class="text-xs text-muted-text font-medium">Jadwal: Selasa &amp; Kamis</span>
                </div>
            </div>

            <!-- Rombel 2: VII-B -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs p-6 flex flex-col justify-between hover:border-forest/40 transition-colors">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                        <div class="flex items-center gap-3">
                            <span class="w-12 h-12 rounded-2xl bg-forest/10 text-forest flex items-center justify-center font-black text-base shrink-0">
                                7B
                            </span>
                            <div>
                                <h2 class="text-lg font-black text-darkgreen">Kelas VII-B</h2>
                                <p class="text-xs text-muted-text">Tingkat VII &bull; Kurikulum Merdeka</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">
                            Absen Perlu Diisi
                        </span>
                    </div>

                    <div class="py-4 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Mata Pelajaran:</span>
                            <span class="font-bold text-darkgreen">Ilmu Pengetahuan Alam (IPA)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Beban Mengajar:</span>
                            <span class="font-bold text-darkgreen">4 Jam Pelajaran (JP) / Minggu</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Jumlah Siswa:</span>
                            <span class="font-bold text-darkgreen">32 Siswa (17 L / 15 P)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Wali Kelas:</span>
                            <span class="font-semibold text-darkgreen">Amirul Hadi, S.Pd.I.</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-border-soft flex items-center justify-between gap-3">
                    <a href="{{ route('guru.absensi') }}?kelas=VII-B" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition-colors inline-flex items-center gap-2">
                        <x-icon name="clipboard-document-check" class="w-4 h-4" />
                        <span>Isi Absensi Hari Ini</span>
                    </a>
                    <span class="text-xs text-muted-text font-medium">Jadwal: Senin &amp; Selasa</span>
                </div>
            </div>

            <!-- Rombel 3: VIII-A -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs p-6 flex flex-col justify-between hover:border-forest/40 transition-colors">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                        <div class="flex items-center gap-3">
                            <span class="w-12 h-12 rounded-2xl bg-forest/10 text-forest flex items-center justify-center font-black text-base shrink-0">
                                8A
                            </span>
                            <div>
                                <h2 class="text-lg font-black text-darkgreen">Kelas VIII-A</h2>
                                <p class="text-xs text-muted-text">Tingkat VIII &bull; Kurikulum Merdeka</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                            Aktif
                        </span>
                    </div>

                    <div class="py-4 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Mata Pelajaran:</span>
                            <span class="font-bold text-darkgreen">Ilmu Pengetahuan Alam (IPA)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Beban Mengajar:</span>
                            <span class="font-bold text-darkgreen">4 Jam Pelajaran (JP) / Minggu</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Jumlah Siswa:</span>
                            <span class="font-bold text-darkgreen">34 Siswa (18 L / 16 P)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Wali Kelas:</span>
                            <span class="font-semibold text-darkgreen">Faridah Hanum, S.Pd.</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-border-soft flex items-center justify-between gap-3">
                    <a href="{{ route('guru.absensi') }}?kelas=VIII-A" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold transition-colors inline-flex items-center gap-2">
                        <x-icon name="clipboard-document-check" class="w-4 h-4" />
                        <span>Input Absensi</span>
                    </a>
                    <span class="text-xs text-muted-text font-medium">Jadwal: Senin &amp; Rabu</span>
                </div>
            </div>

            <!-- Rombel 4: VIII-B (Perwalian Sendiri) -->
            <div class="bg-white rounded-3xl border-2 border-forest/40 shadow-xs p-6 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 px-3 py-1 bg-forest text-white text-[10px] font-bold rounded-bl-xl uppercase tracking-wider">
                    Kelas Perwalian Anda
                </div>

                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                        <div class="flex items-center gap-3">
                            <span class="w-12 h-12 rounded-2xl bg-forest text-white flex items-center justify-center font-black text-base shrink-0 shadow-xs">
                                8B
                            </span>
                            <div>
                                <h2 class="text-lg font-black text-darkgreen">Kelas VIII-B</h2>
                                <p class="text-xs text-muted-text">Tingkat VIII &bull; Kurikulum Merdeka</p>
                            </div>
                        </div>
                    </div>

                    <div class="py-4 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Mata Pelajaran:</span>
                            <span class="font-bold text-darkgreen">Ilmu Pengetahuan Alam (IPA)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Beban Mengajar:</span>
                            <span class="font-bold text-darkgreen">4 Jam Pelajaran (JP) / Minggu</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Jumlah Siswa:</span>
                            <span class="font-bold text-darkgreen">32 Siswa (18 L / 14 P)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-text">Wali Kelas:</span>
                            <span class="font-bold text-forest">Budi Santoso, S.Pd. (Anda)</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-border-soft flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('guru.absensi') }}?kelas=VIII-B" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold transition-colors inline-flex items-center gap-2">
                            <x-icon name="clipboard-document-check" class="w-4 h-4" />
                            <span>Input Absensi</span>
                        </a>
                        <a href="{{ route('guru.perwalian.siswa') }}" class="px-3 py-2 rounded-xl bg-soft hover:bg-border-soft/60 text-darkgreen text-xs font-semibold transition-colors">
                            Data Siswa
                        </a>
                    </div>
                    <span class="text-xs text-muted-text font-medium">Jadwal: Senin &amp; Rabu</span>
                </div>
            </div>

        </div>

    </div>
</x-layouts.guru>
