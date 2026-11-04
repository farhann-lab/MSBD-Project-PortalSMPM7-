<x-layouts.admin 
    title="Data Siswa & Impor Excel" 
    activeMenu="master-siswa"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.siswa')],
        ['label' => 'Data Siswa', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen Data Siswa (~30 Kolom Berkelompok)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Kelola data identitas siswa, sekolah asal, wali murid, dan penempatan rombongan belajar kelas aktif.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs sm:text-sm font-semibold flex items-center gap-2 transition-colors">
                    <x-icon name="document-text" class="w-4 h-4 text-forest" />
                    <span>Unduh Template Excel</span>
                </button>
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="user-plus" class="w-4 h-4" />
                    <span>Tambah Siswa Manual</span>
                </button>
            </div>
        </div>

        <!-- Excel Staging Import Section (DESIGN.md 11.2: Unduh Template, Unggah File, Hasil Validasi, Konfirmasi) -->
        <div class="p-6 rounded-3xl bg-linear-to-br from-white to-emerald-50/40 border border-emerald-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-forest text-white flex items-center justify-center">
                        <x-icon name="clipboard-check" class="w-4 h-4" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-darkgreen">Alur Impor Siswa Excel (Tahap Validasi Staging)</h3>
                        <p class="text-[11px] text-muted-text">Data diverifikasi terlebih dahulu sebelum masuk ke database utama (tanpa simpan otomatis).</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                    SIAKAD Staging Guard
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                <div class="p-4 rounded-2xl bg-white border border-border-soft space-y-2">
                    <span class="text-[11px] font-bold text-forest uppercase tracking-wider block">Langkah 1: Unduh File</span>
                    <p class="text-xs text-muted-text">Gunakan template resmi sesuai urutan kolom tabel identitas, sekolah asal, dan orang tua/wali.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-border-soft space-y-2">
                    <span class="text-[11px] font-bold text-forest uppercase tracking-wider block">Langkah 2: Unggah & Validasi</span>
                    <p class="text-xs text-muted-text">Sistem memverifikasi duplikasi NIS/NISN, nomor baris error, dan kelengkapan field wajib.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-border-soft space-y-2">
                    <span class="text-[11px] font-bold text-forest uppercase tracking-wider block">Langkah 3: Konfirmasi Impor</span>
                    <p class="text-xs text-muted-text">Hanya baris yang lolos validasi penuh yang di-commit ke database via transaksi ACID.</p>
                </div>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                <input type="file" class="text-xs text-muted-text file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-forest file:text-white hover:file:bg-forest/90 cursor-pointer">
                <button type="button" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-white border border-border-soft text-darkgreen hover:bg-soft text-xs font-semibold shadow-xs">
                    Periksa & Validasi File Excel
                </button>
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
                    placeholder="Cari nama, NIS, atau NISN siswa..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2">
                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Rombel Kelas</option>
                    <option value="7A">Kelas 7A</option>
                    <option value="7B">Kelas 7B</option>
                    <option value="8A">Kelas 8A</option>
                    <option value="8B">Kelas 8B</option>
                    <option value="9A">Kelas 9A</option>
                    <option value="9B">Kelas 9B</option>
                </select>
                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="A" selected>Status Aktif (A)</option>
                    <option value="K">Keluar (K)</option>
                    <option value="M">Mutasi (M)</option>
                    <option value="L">Lulus (L)</option>
                </select>
            </div>
        </div>

        <!-- Master Data Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">No</th>
                            <th class="py-3.5 px-4 sm:px-6">NIS / NISN</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama Lengkap Siswa</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Kelas</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Tahun Ajaran</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @foreach($students as $index => $student)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-xs">
                                    <span class="font-bold text-darkgreen block">{{ 3900 + $index + 1 }}</span>
                                    <span class="text-muted-text">00829182{{ $index + 1 }}</span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="font-bold text-darkgreen block">{{ $student['nama_lengkap'] }}</span>
                                    <span class="text-[11px] text-muted-text">Tempat Lahir: Medan</span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-soft font-bold text-forest text-xs border border-border-soft">
                                        {{ $student['kelas'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-xs text-muted-text">
                                    2026/2027 Ganjil
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        Aktif (A)
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-soft transition-colors" title="Lihat 30 Kolom Lengkap">
                                            <x-icon name="document-text" class="w-4 h-4" />
                                        </button>
                                        <button type="button" class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-soft transition-colors" title="Edit Data Siswa">
                                            <x-icon name="external-link" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination & Count -->
            <div class="p-4 sm:px-6 border-t border-border-soft bg-soft/30 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-muted-text">
                <div>
                    Menampilkan <strong>1 - {{ count($students) }}</strong> dari <strong>356</strong> siswa aktif terdaftar
                </div>
                <div class="flex items-center gap-1">
                    <span class="px-3 py-1.5 rounded-lg bg-forest text-white font-bold">1</span>
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-border-soft hover:bg-soft">2</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-border-soft hover:bg-soft">3</button>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
