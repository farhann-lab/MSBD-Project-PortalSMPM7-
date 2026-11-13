<x-layouts.siswa 
    title="Jadwal Pelajaran" 
    activeMenu="jadwal-siswa"
    :breadcrumbs="[
        ['label' => 'Jadwal Pelajaran', 'url' => route('siswa.jadwal')],
    ]"
    kelasSiswa="VIII-B"
>
    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest text-white text-xs font-bold uppercase tracking-wider">
                        Kelas VIII-B
                    </span>
                    <span class="text-xs text-muted-text">&bull; Semester Ganjil 2026/2027</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Jadwal Pelajaran Mingguan
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Jadwal kegiatan belajar mengajar tatap muka mingguan kelas VIII-B.
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs font-bold flex items-center gap-2 transition-colors cursor-pointer">
                    <x-icon name="printer" class="w-4 h-4 text-forest" />
                    <span>Cetak Jadwal</span>
                </button>
            </div>
        </div>

        <!-- Weekly Schedule Grid Siswa -->
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
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam 1-2</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">Matematika</p>
                        <p class="text-xs text-muted-text">Nadia Fitri, S.Pd. &bull; Ruang B-2</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">09.30 - 11.00 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam 3-4</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">Bahasa Indonesia</p>
                        <p class="text-xs text-muted-text">Faridah Hanum, S.Pd. &bull; Ruang B-2</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-darkgreen">11.15 - 12.45 WIB</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-soft text-darkgreen font-bold">Jam 5-6</span>
                        </div>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu</p>
                        <p class="text-xs text-muted-text">Budi Santoso, S.Pd. &bull; Ruang B-2</p>
                    </div>
                </div>
            </div>

            <!-- Selasa -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">SELASA</h2>
                    <span class="text-xs text-muted-text">3 Mata Pelajaran</span>
                </div>

                <div class="p-4 divide-y divide-border-soft/60 space-y-3">
                    <div class="pt-2 flex flex-col gap-1.5">
                        <span class="text-xs font-black text-darkgreen">07.30 - 09.00 WIB</span>
                        <p class="text-sm font-bold text-darkgreen">Pendidikan Agama Islam (PAI)</p>
                        <p class="text-xs text-muted-text">Amirul Hadi, S.Pd.I. &bull; Ruang B-2</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <span class="text-xs font-black text-darkgreen">09.30 - 11.00 WIB</span>
                        <p class="text-sm font-bold text-darkgreen">Bahasa Inggris</p>
                        <p class="text-xs text-muted-text">Muhammad Iqbal, S.Pd. &bull; Ruang B-2</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <span class="text-xs font-black text-darkgreen">11.15 - 12.45 WIB</span>
                        <p class="text-sm font-bold text-darkgreen">Pendidikan Jasmani &amp; Olahraga</p>
                        <p class="text-xs text-muted-text">Andika Permana, S.Pd. &bull; Lapangan Sekolah</p>
                    </div>
                </div>
            </div>

            <!-- Rabu -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">RABU</h2>
                    <span class="text-xs text-muted-text">3 Mata Pelajaran</span>
                </div>

                <div class="p-4 divide-y divide-border-soft/60 space-y-3">
                    <div class="pt-2 flex flex-col gap-1.5">
                        <span class="text-xs font-black text-darkgreen">07.30 - 09.00 WIB</span>
                        <p class="text-sm font-bold text-darkgreen">IPA Terpadu (Praktikum)</p>
                        <p class="text-xs text-muted-text">Budi Santoso, S.Pd. &bull; Laboratorium IPA</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <span class="text-xs font-black text-darkgreen">09.30 - 11.00 WIB</span>
                        <p class="text-sm font-bold text-darkgreen">Informatika / TIK</p>
                        <p class="text-xs text-muted-text">Irfan Maulana, S.Kom. &bull; Lab Komputer</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <span class="text-xs font-black text-darkgreen">11.15 - 12.45 WIB</span>
                        <p class="text-sm font-bold text-darkgreen">Ilmu Pengetahuan Sosial (IPS)</p>
                        <p class="text-xs text-muted-text">Annisa Nur Fadilah, S.Pd. &bull; Ruang B-2</p>
                    </div>
                </div>
            </div>

            <!-- Kamis -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">KAMIS</h2>
                    <span class="text-xs text-muted-text">2 Mata Pelajaran</span>
                </div>

                <div class="p-4 divide-y divide-border-soft/60 space-y-3">
                    <div class="pt-2 flex flex-col gap-1.5">
                        <span class="text-xs font-black text-darkgreen">07.30 - 09.00 WIB</span>
                        <p class="text-sm font-bold text-darkgreen">Pendidikan Pancasila &amp; Kewarganegaraan</p>
                        <p class="text-xs text-muted-text">Khairul Anwar, S.Pd. &bull; Ruang B-2</p>
                    </div>

                    <div class="pt-3 flex flex-col gap-1.5">
                        <span class="text-xs font-black text-darkgreen">09.30 - 11.00 WIB</span>
                        <p class="text-sm font-bold text-darkgreen">Seni Budaya &amp; Prakarya</p>
                        <p class="text-xs text-muted-text">Dwi Rahayu, S.Pd. &bull; Ruang Kesenian</p>
                    </div>
                </div>
            </div>

            <!-- Jumat -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">JUMAT</h2>
                    <span class="text-xs text-muted-text">Adab &amp; Dhuha Bersama</span>
                </div>

                <div class="p-5 text-center text-muted-text text-xs space-y-2">
                    <x-icon name="academic-cap" class="w-8 h-8 mx-auto text-forest/40" />
                    <p class="font-bold text-darkgreen">Kegiatan Pembiasaan Islami</p>
                    <p class="leading-relaxed">07.30 - 09.00 WIB: Sholat Dhuha, Tadarrus Al-Qur'an, dan pembinaan karakter di Musholla Sekolah.</p>
                </div>
            </div>

            <!-- Sabtu -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="px-5 py-4 bg-soft border-b border-border-soft flex items-center justify-between text-darkgreen">
                    <h2 class="font-bold text-sm tracking-wide">SABTU</h2>
                    <span class="text-xs text-muted-text">Ekstrakurikuler</span>
                </div>

                <div class="p-5 text-center text-muted-text text-xs space-y-2">
                    <x-icon name="trophy" class="w-8 h-8 mx-auto text-forest/40" />
                    <p class="font-bold text-darkgreen">Pengembangan Minat &amp; Bakat</p>
                    <p class="leading-relaxed">Hizbul Wathan (HW), Tapak Suci, English Club, Futsal, dan IPM sesuai pilihan masing-masing siswa.</p>
                </div>
            </div>

        </div>

    </div>
</x-layouts.siswa>
