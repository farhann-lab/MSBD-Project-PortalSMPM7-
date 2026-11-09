<x-layouts.guru 
    title="Jadwal Mengajar" 
    activeMenu="jadwal-mengajar"
    :breadcrumbs="[
        ['label' => 'Akademik & Pembelajaran', 'url' => ''],
        ['label' => 'Jadwal Mengajar', 'url' => route('guru.jadwal')],
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
                        Semester Ganjil 2026/2027
                    </span>
                    <span class="text-xs text-muted-text">&bull; Total 16 Jam Pelajaran (JP) / Minggu</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Jadwal Mengajar Guru
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Jadwal tatap muka mata pelajaran Ilmu Pengetahuan Alam (IPA) di kelas binaan.
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs font-bold flex items-center gap-2 transition-colors cursor-pointer">
                    <x-icon name="printer" class="w-4 h-4 text-forest" />
                    <span>Cetak Jadwal</span>
                </button>
            </div>
        </div>

        <!-- Weekly Schedule Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            
            <!-- Senin (Hari Ini) -->
            <div class="bg-white rounded-3xl border-2 border-forest/30 shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-forest text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <h2 class="font-bold text-sm tracking-wide">SENIN</h2>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/20 text-white uppercase tracking-wider">
                        Hari Ini
                    </span>
                </div>

                <div class="p-4 divide-y divide-border-soft/60 space-y-3">
                    <div class="pt-2 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">07.30 - 09.00 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold border border-amber-200">Jam Ke 1-2</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu &bull; Kelas VII-B</p>
                        <p class="text-xs text-muted-text">Ruang Belajar B-2 &bull; 32 Siswa</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">09.30 - 11.00 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam Ke 3-4</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu &bull; Kelas VIII-A</p>
                        <p class="text-xs text-muted-text">Laboratorium IPA &bull; 34 Siswa</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">11.15 - 12.45 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam Ke 5-6</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu &bull; Kelas VIII-B (Perwalian)</p>
                        <p class="text-xs text-muted-text">Ruang Belajar B-2 &bull; 32 Siswa</p>
                    </div>
                </div>
            </div>

            <!-- Selasa -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">SELASA</h2>
                    <span class="text-xs text-muted-text">2 Sesi Mengajar</span>
                </div>

                <div class="p-4 divide-y divide-border-soft/60 space-y-3">
                    <div class="pt-2 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">08.15 - 09.45 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam Ke 2-3</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu &bull; Kelas VII-A</p>
                        <p class="text-xs text-muted-text">Ruang Belajar A-1 &bull; 30 Siswa</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">10.15 - 11.45 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam Ke 4-5</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu &bull; Kelas VII-B</p>
                        <p class="text-xs text-muted-text">Laboratorium IPA &bull; 32 Siswa</p>
                    </div>
                </div>
            </div>

            <!-- Rabu -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">RABU</h2>
                    <span class="text-xs text-muted-text">2 Sesi Mengajar</span>
                </div>

                <div class="p-4 divide-y divide-border-soft/60 space-y-3">
                    <div class="pt-2 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">07.30 - 09.00 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam Ke 1-2</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu &bull; Kelas VIII-B (Perwalian)</p>
                        <p class="text-xs text-muted-text">Laboratorium IPA &bull; 32 Siswa</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">10.15 - 11.45 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam Ke 4-5</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu &bull; Kelas VIII-A</p>
                        <p class="text-xs text-muted-text">Ruang Belajar B-1 &bull; 34 Siswa</p>
                    </div>
                </div>
            </div>

            <!-- Kamis -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">KAMIS</h2>
                    <span class="text-xs text-muted-text">1 Sesi Mengajar</span>
                </div>

                <div class="p-4 divide-y divide-border-soft/60 space-y-3">
                    <div class="pt-2 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">08.15 - 09.45 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam Ke 2-3</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu &bull; Kelas VII-A</p>
                        <p class="text-xs text-muted-text">Laboratorium IPA &bull; 30 Siswa</p>
                    </div>
                </div>
            </div>

            <!-- Jumat -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">JUMAT</h2>
                    <span class="text-xs text-muted-text">Pembinaan Karakter &amp; Dhuha</span>
                </div>

                <div class="p-5 text-center text-muted-text text-xs">
                    <x-icon name="academic-cap" class="w-8 h-8 mx-auto text-forest/40 mb-2" />
                    <p class="font-bold text-darkgreen">Kegiatan Keislaman &amp; Ekstrakurikuler</p>
                    <p class="mt-1">Tidak ada jadwal tatap muka pelajaran IPA di hari Jumat.</p>
                </div>
            </div>

            <!-- Sabtu -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">SABTU</h2>
                    <span class="text-xs text-muted-text">Rapat / Evaluasi</span>
                </div>

                <div class="p-5 text-center text-muted-text text-xs">
                    <x-icon name="user-group" class="w-8 h-8 mx-auto text-forest/40 mb-2" />
                    <p class="font-bold text-darkgreen">Koordinasi Guru &amp; Ekstrakurikuler</p>
                    <p class="mt-1">Pukul 10.00 - 12.00 WIB: Pertemuan berkala tim MGMP IPA.</p>
                </div>
            </div>

        </div>

    </div>
</x-layouts.guru>
