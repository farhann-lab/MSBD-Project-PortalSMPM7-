<x-layouts.admin title="Dashboard Admin" activeMenu="dashboard">
    <div class="space-y-8">
        
        <!-- Welcome Banner Section (Forest Green Theme) -->
        <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#0F1D0F] via-[#162719] to-[#344B39] text-white p-6 sm:p-8 shadow-md">
            <div class="relative z-10 max-w-3xl space-y-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight font-sans">
                    Selamat Datang di SIAKAD Admin, {{ auth()->user()->username ?? 'Administrator' }}!
                </h1>
                <p class="text-sm sm:text-base text-white/80 leading-relaxed">
                    Pusat kendali operasional sekolah, direktori guru & siswa, konten informasi publik, dan administrasi akademik SMP Muhammadiyah 7 Medan.
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <a href="#siswa" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-darkgreen text-xs sm:text-sm font-bold shadow-xs hover:bg-mint transition-colors">
                        <x-icon name="user-group" class="w-4 h-4 text-forest" />
                        <span>Data Siswa & Impor</span>
                    </a>
                    <a href="#berita" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 text-white text-xs sm:text-sm font-medium hover:bg-white/25 transition-colors border border-white/20">
                        <x-icon name="newspaper" class="w-4 h-4 text-mint" />
                        <span>Kelola Berita & Informasi</span>
                    </a>
                </div>
            </div>

            <!-- Background Decorative Overlay -->
            <div class="absolute -right-10 -bottom-10 w-80 h-80 rounded-full bg-mint/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-12 top-1/2 -translate-y-1/2 hidden lg:block opacity-15 pointer-events-none">
                <img src="{{ asset('images/school/logo.png') }}" alt="" class="w-48 h-48 object-contain">
            </div>
        </div>

        <!-- 5 Key Summary Cards (DESIGN.md 10.1: Guru, Siswa, Kelas, Mata Pelajaran, Tahun Ajaran) -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-darkgreen">Statistik Utama Sekolah</h2>
                <span class="text-xs text-muted-text">Diperbarui secara otomatis</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                
                <!-- Guru Card -->
                <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-muted-text uppercase tracking-wider">Guru & Tendik</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-extrabold text-darkgreen">17</span>
                        <span class="text-xs text-muted-text ml-1">Pendidik</span>
                    </div>
                </div>

                <!-- Siswa Card -->
                <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-muted-text uppercase tracking-wider">Total Siswa</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-extrabold text-darkgreen">356</span>
                        <span class="text-xs text-muted-text ml-1">Siswa Terdaftar</span>
                    </div>
                </div>

                <!-- Kelas Card -->
                <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-muted-text uppercase tracking-wider">Rombel Kelas</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-extrabold text-darkgreen">10</span>
                        <span class="text-xs text-muted-text ml-1">Rombel</span>
                    </div>
                </div>

                <!-- Mata Pelajaran Card -->
                <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-muted-text uppercase tracking-wider">Mata Pelajaran</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-extrabold text-darkgreen">14</span>
                        <span class="text-xs text-muted-text ml-1">Mata Pelajaran</span>
                    </div>
                </div>

                <!-- Tahun Ajaran Card -->
                <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-muted-text uppercase tracking-wider">Tahun Ajaran</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-extrabold text-darkgreen">2026/2027</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Quick Action & Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Quick Management Shortcuts (2 Columns on large screens) -->
            <div class="lg:col-span-2 space-y-6">
                
                <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-darkgreen">Pintasan Pengelolaan Cepat</h3>
                            <p class="text-xs text-muted-text">Akses fitur administratif yang sering digunakan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <a href="#siswa-impor" class="p-4 rounded-2xl border border-border-soft hover:border-forest/40 hover:bg-soft/70 transition-all flex items-start gap-3.5 group">
                            <div class="w-10 h-10 rounded-xl bg-forest text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <x-icon name="user-plus" class="w-5 h-5" />
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-darkgreen group-hover:text-forest transition-colors">Impor Siswa Excel</h4>
                                <p class="text-xs text-muted-text mt-0.5">Unggah data rombel siswa dari format XLS/XLSX.</p>
                            </div>
                        </a>

                        <a href="#berita-baru" class="p-4 rounded-2xl border border-border-soft hover:border-forest/40 hover:bg-soft/70 transition-all flex items-start gap-3.5 group">
                            <div class="w-10 h-10 rounded-xl bg-forest text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <x-icon name="newspaper" class="w-5 h-5" />
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-darkgreen group-hover:text-forest transition-colors">Tulis Berita Baru</h4>
                                <p class="text-xs text-muted-text mt-0.5">Publikasikan informasi kegiatan ke website resmi.</p>
                            </div>
                        </a>

                        <a href="#penugasan-guru" class="p-4 rounded-2xl border border-border-soft hover:border-forest/40 hover:bg-soft/70 transition-all flex items-start gap-3.5 group">
                            <div class="w-10 h-10 rounded-xl bg-forest text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <x-icon name="clipboard-check" class="w-5 h-5" />
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-darkgreen group-hover:text-forest transition-colors">Atur Penugasan Guru</h4>
                                <p class="text-xs text-muted-text mt-0.5">Tetapkan mata pelajaran dan kelas yang diampu guru.</p>
                            </div>
                        </a>

                        <a href="#reset-password" class="p-4 rounded-2xl border border-border-soft hover:border-forest/40 hover:bg-soft/70 transition-all flex items-start gap-3.5 group">
                            <div class="w-10 h-10 rounded-xl bg-forest text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <x-icon name="key" class="w-5 h-5" />
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-darkgreen group-hover:text-forest transition-colors">Reset Sandi Pengguna</h4>
                                <p class="text-xs text-muted-text mt-0.5">Reset kata sandi akun guru, wali kelas, atau siswa.</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Content Activity / Monitoring -->
                <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-darkgreen">Aktivitas Terkini Konten & Data</h3>
                            <p class="text-xs text-muted-text">Status sinkronisasi data master dan publikasi</p>
                        </div>
                    </div>

                    <div class="divide-y divide-border-soft text-xs">
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <div>
                                    <p class="font-semibold text-darkgreen">Struktur Organisasi & Profil Diperbarui</p>
                                    <p class="text-[11px] text-muted-text">Halaman profil sekolah siap diakses publik</p>
                                </div>
                            </div>
                            <span class="text-muted-text">Hari ini</span>
                        </div>
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <div>
                                    <p class="font-semibold text-darkgreen">Data 356 Siswa Aktif Terverifikasi</p>
                                    <p class="text-[11px] text-muted-text">10 Rombongan Belajar terisi lengkap</p>
                                </div>
                            </div>
                            <span class="text-muted-text">Kemarin</span>
                        </div>
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <div>
                                    <p class="font-semibold text-darkgreen">17 Guru Pengampu Terjadwal</p>
                                    <p class="text-[11px] text-muted-text">Penugasan jadwal semester ganjil aktif</p>
                                </div>
                            </div>
                            <span class="text-muted-text">2 hari lalu</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- System Info & Admin Help Card -->
            <div class="space-y-6">
                
                <!-- School Info Card -->
                <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-darkgreen">Informasi Satuan Pendidikan</h3>
                    
                    <div class="space-y-3 text-xs">
                        <div class="p-3 rounded-xl bg-soft">
                            <span class="text-muted-text block text-[11px]">NPSN Sekolah</span>
                            <span class="font-bold text-darkgreen text-sm">{{ config('sekolah.npsn', '10210871') }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-soft">
                            <span class="text-muted-text block text-[11px]">Nama Satuan</span>
                            <span class="font-bold text-darkgreen text-sm">{{ config('sekolah.nama', 'SMP Muhammadiyah 7 Medan') }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-soft">
                            <span class="text-muted-text block text-[11px]">Akreditasi</span>
                            <span class="font-bold text-emerald-700 text-sm">Akreditasi A (Unggul)</span>
                        </div>
                        <div class="p-3 rounded-xl bg-soft">
                            <span class="text-muted-text block text-[11px]">Alamat</span>
                            <span class="font-medium text-darkgreen text-xs">{{ config('sekolah.kontak.alamat', 'Jl. Pelita II No. 17, Sidorame Barat II, Kec. Medan Perjuangan, Kota Medan') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Support & Help -->
                <div class="p-6 rounded-3xl bg-linear-to-br from-[#F5F8F5] to-emerald-50 border border-emerald-200/60 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-forest font-bold text-sm">
                        <x-icon name="help" class="w-5 h-5 text-forest" />
                        <span>Panduan Administrator</span>
                    </div>
                    <p class="text-xs text-darkgreen/80 leading-relaxed">
                        Perlu bantuan dalam sinkronisasi data master atau reset akun pengguna? Silakan lihat dokumentasi sistem atau hubungi tim teknis.
                    </p>
                    <a href="mailto:{{ config('sekolah.kontak.email', 'smpmuhammadiyah7medan@gmail.com') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-forest hover:text-darkgreen hover:underline">
                        <span>Hubungi Bantuan Teknis</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                    </a>
                </div>

            </div>

        </div>

    </div>
</x-layouts.admin>
