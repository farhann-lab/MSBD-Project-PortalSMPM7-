<x-layouts.admin 
    title="Pengaturan Sekolah" 
    activeMenu="sistem-pengaturan"
    :breadcrumbs="[
        ['label' => 'Akun & Sistem', 'url' => route('admin.sistem.pengguna')],
        ['label' => 'Pengaturan Sekolah', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Konfigurasi Sistem & Identitas Lembaga</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Atur profil resmi sekolah, legalitas raport, parameter kelulusan & presensi, serta pemeliharaan database.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" class="px-5 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="check" class="w-4 h-4" />
                    <span>Simpan Seluruh Perubahan</span>
                </button>
            </div>
        </div>

        <!-- Setting Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Section 1: Identitas Sekolah -->
            <div class="lg:col-span-2 bg-white rounded-3xl border border-border-soft p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-border-soft pb-4">
                    <h3 class="text-base font-bold text-darkgreen flex items-center gap-2">
                        <x-icon name="school" class="w-5 h-5 text-forest" />
                        <span>Identitas Resmi Lembaga Pendidikan</span>
                    </h3>
                    <p class="text-xs text-muted-text mt-1">Data ini digunakan pada kop surat, cetak leger nilai, dan dokumen rapor resmi siswa.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-darkgreen mb-1.5">Nama Resmi Sekolah</label>
                        <input type="text" value="SMP Muhammadiyah 7 Medan" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-darkgreen mb-1.5">NPSN (Nomor Pokok Sekolah Nasional)</label>
                        <input type="text" value="10210877" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-mono focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-darkgreen mb-1.5">NSS (Nomor Statistik Sekolah)</label>
                        <input type="text" value="202026001007" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-mono focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-darkgreen mb-1.5">Status Akreditasi</label>
                        <select class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                            <option value="A" selected>Terakreditasi A (Unggul)</option>
                            <option value="B">Terakreditasi B (Baik)</option>
                            <option value="C">Terakreditasi C</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-darkgreen mb-1.5">Alamat Lengkap</label>
                        <textarea rows="2" class="w-full px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">Jl. Pelita II No. 17, Sidorame Barat II, Kec. Medan Perjuangan, Kota Medan, Sumatera Utara 20236</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-darkgreen mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input type="text" value="(061) 6625877 / 0812-6455-8899" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-darkgreen mb-1.5">Email Resmi</label>
                        <input type="email" value="info@smpm7medan.sch.id" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>
                </div>

                <div class="border-t border-border-soft pt-4 space-y-4">
                    <h4 class="text-sm font-bold text-darkgreen">Pimpinan & Penandatangan Dokumen</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-darkgreen mb-1.5">Nama Kepala Sekolah</label>
                            <input type="text" value="Dra. Hj. Siti Rahmah, M.Pd" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-darkgreen mb-1.5">NIP Kepala Sekolah</label>
                            <input type="text" value="197508142000032001" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-mono focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Konfigurasi Parameter SIAKAD & Database -->
            <div class="space-y-6">

                <!-- Parameter Nilai & Presensi -->
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-darkgreen flex items-center gap-2">
                        <x-icon name="academic-cap" class="w-4 h-4 text-forest" />
                        <span>Parameter Akademik</span>
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-darkgreen mb-1">KKM Default / Nilai Minimum</label>
                            <input type="number" value="75" class="w-full px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm font-bold text-darkgreen">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-darkgreen mb-1">Batas Minimum Kehadiran Kenaikan (%)</label>
                            <input type="number" value="85" class="w-full px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm font-bold text-darkgreen">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-darkgreen mb-1">Status Penginputan Nilai Guru</label>
                            <select class="w-full px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-semibold">
                                <option value="open" selected>Dibuka (Guru Bebas Input Nilai)</option>
                                <option value="locked">Dikunci (Persiapan Cetak Rapor)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Backup & Pemeliharaan Database -->
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-darkgreen flex items-center gap-2">
                        <x-icon name="circle-stack" class="w-4 h-4 text-forest" />
                        <span>Database & Cadangan</span>
                    </h3>

                    <div class="space-y-2 text-xs text-muted-text">
                        <div class="flex justify-between py-1 border-b border-border-soft">
                            <span>Engine Basis Data:</span>
                            <span class="font-bold text-darkgreen font-mono">MySQL 8.0</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border-soft">
                            <span>Nama Database:</span>
                            <span class="font-bold text-darkgreen font-mono">db_portal_smpm7</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border-soft">
                            <span>Pencadangan Terakhir:</span>
                            <span class="font-bold text-darkgreen">Hari ini, 03:00 WIB</span>
                        </div>
                    </div>

                    <button type="button" class="w-full py-2.5 px-4 rounded-xl border border-forest text-forest hover:bg-forest hover:text-white font-bold text-xs transition-colors flex items-center justify-center gap-2">
                        <x-icon name="arrow-down-tray" class="w-4 h-4" />
                        <span>Unduh Backup SQL Sekarang</span>
                    </button>
                </div>

            </div>

        </div>

    </div>
</x-layouts.admin>