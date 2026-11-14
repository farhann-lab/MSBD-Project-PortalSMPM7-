<x-layouts.admin 
    title="Data Siswa & Impor Excel" 
    activeMenu="master-siswa"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.siswa.index')],
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
                <a href="{{ route('admin.master.siswa.template-impor') }}" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs sm:text-sm font-semibold flex items-center gap-2 transition-colors">
                    <x-icon name="document-text" class="w-4 h-4 text-forest" />
                    <span>Unduh Template Excel (.xls)</span>
                </a>
                <a href="{{ route('admin.master.siswa.create') }}" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="user-plus" class="w-4 h-4" />
                    <span>Tambah Siswa Manual</span>
                </a>
            </div>
        </div>

        <!-- Statistik Ringkas Siswa -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5">
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] font-semibold text-muted-text uppercase tracking-wider block">Total Siswa</span>
                <span class="text-xl font-extrabold text-darkgreen mt-1 block">{{ $stats['total'] }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider block">Aktif (A)</span>
                <span class="text-xl font-extrabold text-emerald-700 mt-1 block">{{ $stats['aktif'] }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] font-semibold text-rose-700 uppercase tracking-wider block">Keluar (K)</span>
                <span class="text-xl font-extrabold text-rose-700 mt-1 block">{{ $stats['keluar'] }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] font-semibold text-amber-700 uppercase tracking-wider block">Mutasi (M)</span>
                <span class="text-xl font-extrabold text-amber-700 mt-1 block">{{ $stats['mutasi'] }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs col-span-2 sm:col-span-1">
                <span class="text-[11px] font-semibold text-blue-700 uppercase tracking-wider block">Lulus (L)</span>
                <span class="text-xl font-extrabold text-blue-700 mt-1 block">{{ $stats['lulus'] }}</span>
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

            <form action="{{ route('admin.master.siswa.impor.upload') }}" method="POST" enctype="multipart/form-data" class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                @csrf
                <input 
                    type="file" 
                    name="file_excel" 
                    accept=".xls,.xlsx" 
                    required 
                    class="text-xs text-muted-text file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-forest file:text-white hover:file:bg-forest/90 cursor-pointer"
                >
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5 transition-colors">
                    <x-icon name="arrow-up-tray" class="w-4 h-4" />
                    <span>Periksa & Validasi File Excel</span>
                </button>
            </form>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.master.siswa.index') }}" class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                    <x-icon name="search" class="w-4 h-4 text-sage" />
                </span>
                <input 
                    type="text" 
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari nama, NIS, atau NISN siswa..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="A" {{ request('status', 'A') === 'A' ? 'selected' : '' }}>Aktif (A)</option>
                    <option value="K" {{ request('status') === 'K' ? 'selected' : '' }}>Keluar (K)</option>
                    <option value="M" {{ request('status') === 'M' ? 'selected' : '' }}>Mutasi (M)</option>
                    <option value="L" {{ request('status') === 'L' ? 'selected' : '' }}>Lulus (L)</option>
                </select>
                <button type="submit" class="px-4 py-2 rounded-xl bg-soft hover:bg-border-soft text-darkgreen text-xs font-semibold">
                    Cari
                </button>
                @if(request()->filled('q') || request()->filled('status'))
                    <a href="{{ route('admin.master.siswa.index') }}" class="px-3 py-2 rounded-xl border border-border-soft hover:bg-soft text-rose-600 text-xs font-semibold">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Master Data Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">No</th>
                            <th class="py-3.5 px-4 sm:px-6">NIS / NISN</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama Lengkap Siswa</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">JK</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Akun SIAKAD</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @forelse($students as $index => $student)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text">
                                    {{ $students->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-xs">
                                    <span class="font-bold text-darkgreen block">{{ $student->nis }}</span>
                                    <span class="text-muted-text">{{ $student->nisn && $student->nisn !== '0' ? $student->nisn : '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span class="font-bold text-darkgreen block">{{ $student->nama }}</span>
                                    <span class="text-[11px] text-muted-text">
                                        {{ $student->tmp_lahir ?: '-' }}, {{ $student->getFormattedDate('tgl_lahir') ?: '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-bold {{ $student->jk === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $student->jk === 'L' ? 'L' : 'P' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @php
                                        $statusBadges = [
                                            'A' => ['bg' => 'bg-emerald-100 text-emerald-800', 'label' => 'Aktif (A)'],
                                            'K' => ['bg' => 'bg-rose-100 text-rose-800', 'label' => 'Keluar (K)'],
                                            'M' => ['bg' => 'bg-amber-100 text-amber-800', 'label' => 'Mutasi (M)'],
                                            'L' => ['bg' => 'bg-blue-100 text-blue-800', 'label' => 'Lulus (L)'],
                                        ];
                                        $badge = $statusBadges[$student->stat_data] ?? ['bg' => 'bg-gray-100 text-gray-800', 'label' => $student->stat_data];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $badge['bg'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($student->user)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>{{ $student->user->username }}</span>
                                        </span>
                                    @else
                                        <span class="text-xs text-muted-text italic">Belum dibuat</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a 
                                            href="{{ route('admin.master.siswa.edit', $student) }}" 
                                            class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-soft transition-colors" 
                                            title="Edit Data Siswa"
                                        >
                                            <x-icon name="pencil-square" class="w-4 h-4" />
                                        </a>

                                        @if($student->stat_data === 'A')
                                            <form 
                                                action="{{ route('admin.master.siswa.destroy', $student) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('Nonaktifkan status siswa {{ $student->nama }} menjadi Keluar (K)?')"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="p-1.5 rounded-lg text-muted-text hover:text-rose-600 hover:bg-rose-50 transition-colors" 
                                                    title="Nonaktifkan Siswa (Set Status K)"
                                                >
                                                    <x-icon name="trash" class="w-4 h-4" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-muted-text text-xs sm:text-sm">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <x-icon name="users" class="w-8 h-8 text-muted-text/50 mx-auto" />
                                        <p class="font-semibold text-darkgreen">Tidak ada data siswa ditemukan.</p>
                                        <p class="text-xs text-muted-text">Coba ubah kata kunci pencarian atau filter status.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination & Count -->
            <div class="p-4 sm:px-6 border-t border-border-soft bg-soft/30 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-muted-text">
                <div>
                    Menampilkan <strong>{{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }}</strong> dari <strong>{{ $students->total() }}</strong> siswa terdaftar
                </div>
                <div>
                    {{ $students->links() }}
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
