<x-layouts.siswa 
    title="Profil Siswa" 
    activeMenu="profil-siswa"
    :breadcrumbs="[
        ['label' => 'Profil Siswa', 'url' => route('siswa.profil')],
    ]"
    kelasSiswa="VIII-B"
>
    @if(config('siakad_rbac.enabled'))
    <section class="space-y-4">
        <div class="p-6 bg-white border border-border-soft rounded-2xl shadow-xs">
            <h1 class="text-xl font-bold text-darkgreen">Profil Siswa Saya</h1>
            <p class="text-xs text-muted-text mt-2">Data ini diambil berdasarkan akun siswa yang sedang login.</p>
            @if($identitasSiswa)
                <dl class="mt-4 grid gap-3 sm:grid-cols-2 text-sm">
                    <div><dt class="text-muted-text">Nama</dt><dd class="font-semibold">{{ $identitasSiswa->nama }}</dd></div>
                    <div><dt class="text-muted-text">Kelas</dt><dd class="font-semibold">{{ $identitasSiswa->nama_kelas }}</dd></div>
                    <div><dt class="text-muted-text">ID Siswa</dt><dd class="font-semibold">{{ $identitasSiswa->id }}</dd></div>
                </dl>
            @else
                <p class="mt-4 text-sm text-muted-text">Penempatan siswa periode aktif belum tersedia.</p>
            @endif
        </div>
        <a class="inline-block text-sm text-forest underline" href="{{ route('password.change') }}">Ubah password saya</a>
    </section>
    @else
    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-forest text-white flex items-center justify-center font-black text-xl shadow-xs">
                    AR
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                        Ahmad Rizky Pratama
                    </h1>
                    <p class="text-xs sm:text-sm text-muted-text mt-0.5">
                        NIS: <strong class="text-darkgreen">2401</strong> &bull; NISN: <strong class="text-darkgreen">0098712301</strong> &bull; Kelas VIII-B
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                    Siswa Aktif
                </span>
                <span class="px-3.5 py-1.5 rounded-full bg-forest/10 text-forest text-xs font-bold">
                    Ketua Kelas VIII-B
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left 2 Cols: Biodata & Data Akademik Siswa -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Data Pokok Siswa -->
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs">
                    <h2 class="text-base font-bold text-darkgreen mb-4 pb-3 border-b border-border-soft">
                        Biodata &amp; Informasi Akademik
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Nama Lengkap</span>
                            <span class="font-bold text-darkgreen text-sm">Ahmad Rizky Pratama</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Jenis Kelamin</span>
                            <span class="font-bold text-darkgreen text-sm">Laki-Laki (L)</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Tempat, Tanggal Lahir</span>
                            <span class="font-bold text-darkgreen text-sm">Medan, 15 Januari 2012</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Agama</span>
                            <span class="font-bold text-darkgreen text-sm">Islam</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Kelas / Rombel</span>
                            <span class="font-bold text-darkgreen text-sm">Kelas VIII-B (Kurikulum Merdeka)</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Wali Kelas</span>
                            <span class="font-bold text-forest text-sm">Budi Santoso, S.Pd.</span>
                        </div>
                    </div>
                </div>

                <!-- Sekolah Asal -->
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs">
                    <h2 class="text-base font-bold text-darkgreen mb-4 pb-3 border-b border-border-soft">
                        Sekolah Asal &amp; Penerimaan
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Asal Sekolah SD / MI</span>
                            <span class="font-bold text-darkgreen text-sm">SD Muhammadiyah 02 Medan</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Tanggal Diterima</span>
                            <span class="font-bold text-darkgreen text-sm">15 Juli 2024</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Keamanan Akun & Ubah Password (DESIGN.md 11) -->
            <div class="space-y-6">
                
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs">
                    <h2 class="text-base font-bold text-darkgreen mb-2">Keamanan Akun</h2>
                    <p class="text-xs text-muted-text leading-relaxed mb-4">
                        Perbarui password akun Anda secara berkala untuk menjaga keamanan data akademik.
                    </p>

                    <a 
                        href="{{ route('password.change') }}" 
                        class="inline-flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold transition-all shadow-xs"
                    >
                        <x-icon name="key" class="w-4 h-4 text-emerald-300" />
                        <span>Ubah Password Akun</span>
                    </a>
                </div>

                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs text-xs text-muted-text space-y-2">
                    <h3 class="font-bold text-darkgreen text-sm">Informasi Data Pribadi</h3>
                    <p class="leading-relaxed">
                        Data identitas siswa dikelola terpusat oleh Tata Usaha dan Administrator SIAKAD sesuai prinsip integritas data (DIR-8). Jika terdapat kekeliruan biodata, silakan melapor ke wali kelas.
                    </p>
                </div>

            </div>

        </div>

    </div>
    @endif
</x-layouts.siswa>
