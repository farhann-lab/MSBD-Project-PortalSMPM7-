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
                <button type="button" onclick="openGenerateModal()" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="key" class="w-4 h-4" />
                    <span>Generate Akun Massal</span>
                </button>
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
        <div class="grid grid-cols-2 sm:grid-cols-6 gap-3.5">
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
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] font-semibold text-blue-700 uppercase tracking-wider block">Lulus (L)</span>
                <span class="text-xl font-extrabold text-blue-700 mt-1 block">{{ $stats['lulus'] }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-linear-to-br from-amber-50 to-orange-50 border border-amber-200 shadow-xs col-span-2 sm:col-span-1">
                <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider block">Belum Berakun</span>
                <span class="text-xl font-extrabold text-amber-900 mt-1 block">{{ $stats['belum_berakun'] ?? 0 }}</span>
            </div>
        </div>

        <!-- Hasil Generate Kredensial Sementara Massal (Jika Baru Saja Dijalankan) -->
        @if(session('batch_credentials') || session('batch_skipped'))
            <div id="batchCredentialsCard" class="p-6 rounded-3xl bg-linear-to-br from-amber-500/10 via-white to-amber-50 border-2 border-amber-400 shadow-md space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-600 text-white flex items-center justify-center shrink-0">
                            <x-icon name="key" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-amber-950">
                                Hasil Pembuatan Akun Siswa Massal 
                                @if(session('batch_credentials'))
                                    <span class="text-emerald-700">({{ count(session('batch_credentials')) }} Baru Dibuat)</span>
                                @endif
                                @if(session('batch_skipped'))
                                    <span class="text-amber-700 text-xs font-normal">({{ count(session('batch_skipped')) }} Dilewati - Sudah Ada Akun)</span>
                                @endif
                            </h3>
                            <p class="text-xs text-amber-800 font-medium">
                                ⚠️ <strong>PENTING:</strong> Password sementara ini hanya ditampilkan satu kali demi keamanan! Harap unduh atau salin sebelum menutup halaman.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(session('batch_credentials'))
                            <button type="button" onclick="downloadCsvCredentials()" class="px-3.5 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs flex items-center gap-1.5 transition-colors">
                                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                                <span>Unduh Kredensial (.CSV)</span>
                            </button>
                            <button type="button" onclick="copyAllCredentials()" class="px-3.5 py-2 rounded-xl border border-amber-300 bg-white hover:bg-amber-100/60 text-amber-950 text-xs font-bold flex items-center gap-1.5 transition-colors">
                                <x-icon name="clipboard" class="w-4 h-4 text-amber-700" />
                                <span>Salin Teks</span>
                            </button>
                        @endif
                    </div>
                </div>

                @if(session('batch_credentials'))
                    <div class="overflow-x-auto max-h-64 overflow-y-auto rounded-2xl border border-amber-200 bg-white">
                        <table class="w-full text-left text-xs divide-y divide-amber-100" id="tableBatchCredentials">
                            <thead class="bg-amber-100/60 text-amber-950 font-bold sticky top-0">
                                <tr>
                                    <th class="py-2.5 px-3">No</th>
                                    <th class="py-2.5 px-3">NIS (Username)</th>
                                    <th class="py-2.5 px-3">Nama Siswa</th>
                                    <th class="py-2.5 px-3">Kelas</th>
                                    <th class="py-2.5 px-3">Password Sementara</th>
                                    <th class="py-2.5 px-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-amber-50 font-medium text-darkgreen">
                                @foreach(session('batch_credentials') as $idx => $cred)
                                    <tr class="hover:bg-amber-50/50">
                                        <td class="py-2 px-3 text-muted-text">{{ $idx + 1 }}</td>
                                        <td class="py-2 px-3 font-mono font-bold text-forest">{{ $cred['nis'] }}</td>
                                        <td class="py-2 px-3">{{ $cred['nama'] }}</td>
                                        <td class="py-2 px-3 text-muted-text">{{ $cred['kelas'] }}</td>
                                        <td class="py-2 px-3 font-mono font-bold text-amber-900 bg-amber-50/60">{{ $cred['password_sementara'] }}</td>
                                        <td class="py-2 px-3">
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700">
                                                <x-icon name="check-circle" class="w-3.5 h-3.5 text-emerald-600" />
                                                {{ $cred['keterangan'] ?? 'Akun baru' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if(session('batch_skipped') && count(session('batch_skipped')) > 0)
                    <div class="mt-3 pt-3 border-t border-amber-200/80">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-amber-900">
                                Daftar Siswa yang Dilewati ({{ count(session('batch_skipped')) }} Akun Sudah Terdaftar):
                            </span>
                            <span class="text-[11px] text-muted-text italic">Tidak dibuat ulang demi keamanan kredensial siswa eksisting</span>
                        </div>
                        <div class="overflow-x-auto max-h-40 overflow-y-auto rounded-xl border border-gray-200 bg-gray-50/50">
                            <table class="w-full text-left text-xs divide-y divide-gray-200">
                                <thead class="bg-gray-100 text-gray-700 font-bold sticky top-0">
                                    <tr>
                                        <th class="py-1.5 px-3">No</th>
                                        <th class="py-1.5 px-3">NIS</th>
                                        <th class="py-1.5 px-3">Nama Siswa</th>
                                        <th class="py-1.5 px-3">Kelas</th>
                                        <th class="py-1.5 px-3">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 font-normal text-muted-text">
                                    @foreach(session('batch_skipped') as $sIdx => $skipped)
                                        <tr>
                                            <td class="py-1.5 px-3">{{ $sIdx + 1 }}</td>
                                            <td class="py-1.5 px-3 font-mono font-semibold">{{ $skipped['nis'] }}</td>
                                            <td class="py-1.5 px-3">{{ $skipped['nama'] }}</td>
                                            <td class="py-1.5 px-3">{{ $skipped['kelas'] }}</td>
                                            <td class="py-1.5 px-3">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700">
                                                    <x-icon name="information-circle" class="w-3.5 h-3.5 text-amber-600" />
                                                    {{ $skipped['keterangan'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif

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
        <form method="GET" action="{{ route('admin.master.siswa.index') }}" class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col lg:flex-row items-center justify-between gap-3">
            <div class="w-full lg:w-80 relative">
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

            <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">
                <select name="ta" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="all" {{ $taInput === 'all' ? 'selected' : '' }}>Semua Data Siswa</option>
                    @foreach($daftarTa as $ta)
                        <option value="{{ $ta }}" {{ (string) $taInput === (string) $ta ? 'selected' : '' }}>Periode {{ $ta }}</option>
                    @endforeach
                </select>

                <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Kelas</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" {{ (string) request('kelas') === (string) $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-soft hover:bg-border-soft text-darkgreen text-xs font-semibold">
                    Cari
                </button>
                @if(request()->filled('q') || request()->filled('kelas') || request()->filled('ta'))
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
                            <th class="py-3.5 px-4 sm:px-6">Kelas</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">JK</th>

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
                                <td class="py-3.5 px-4 sm:px-6">
                                    @php
                                        $pen = $filterTa
                                            ? $student->penempatanKelas->firstWhere('ta', $filterTa)
                                            : $student->penempatanKelas->sortByDesc('ta')->first();
                                    @endphp
                                    @if($pen && $pen->kelas)
                                        <span class="font-bold text-darkgreen block">{{ $pen->kelas->nama }}</span>
                                        @if(!$filterTa)
                                            <span class="text-[11px] text-muted-text">TA {{ $pen->ta }}</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-muted-text italic">Belum ada kelas</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-bold {{ $student->jk === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $student->jk === 'L' ? 'L' : 'P' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($student->user)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>{{ $student->user->username }}</span>
                                        </span>
                                    @else
                                        <button 
                                            type="button" 
                                            onclick="openBuatAkunSiswaModal({{ $student->id }}, '{{ addslashes($student->nama) }}', '{{ $student->nis }}')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-forest hover:text-white border border-emerald-200 text-[11px] font-bold transition-colors"
                                            title="Buat Akun Satuan Siswa (Username: NIS)"
                                        >
                                            <x-icon name="plus" class="w-3 h-3" />
                                            <span>Buat Akun</span>
                                        </button>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($student->user)
                                            <button 
                                                type="button" 
                                                onclick="openResetModal({{ $student->id }}, '{{ addslashes($student->nama) }}', '{{ $student->nis }}', '{{ $student->user->username ?? '' }}')"
                                                class="p-1.5 rounded-lg text-muted-text hover:text-amber-600 hover:bg-amber-50 transition-colors" 
                                                title="Reset Password Siswa"
                                            >
                                                <x-icon name="key" class="w-4 h-4" />
                                            </button>
                                        @else
                                            <button 
                                                type="button" 
                                                onclick="openBuatAkunSiswaModal({{ $student->id }}, '{{ addslashes($student->nama) }}', '{{ $student->nis }}')"
                                                class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-forest/10 transition-colors" 
                                                title="Buat Akun Siswa Ini (NIS)"
                                            >
                                                <x-icon name="user-plus" class="w-4 h-4" />
                                            </button>
                                        @endif

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

    <!-- Modal Konfirmasi Generate Akun Siswa Massal -->
    <div id="modalGenerateMassal" class="fixed inset-0 z-50 hidden bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-border-soft relative animate-in fade-in duration-150">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <x-icon name="key" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="text-base font-bold text-darkgreen">Generate Akun Siswa Massal</h3>
                    <p class="text-xs text-muted-text mt-0.5">Buat akun login siswa secara otomatis untuk seluruh siswa aktif yang belum memiliki akun.</p>
                </div>
            </div>

            <form action="{{ route('admin.master.siswa.generate-akun-massal') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 leading-relaxed">
                    <div class="font-bold flex items-center gap-1.5 mb-1.5">
                        <x-icon name="exclamation-circle" class="w-4 h-4 text-amber-700 shrink-0" />
                        <span>Ketentuan Pembuatan Akun:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 pl-1 text-[11px] text-amber-800">
                        <li>Total Siswa Aktif Belum Berakun: <strong>{{ $stats['belum_berakun'] ?? 0 }} siswa</strong></li>
                        <li>Username akan otomatis menggunakan <strong>NIS Siswa</strong> (PRD AKN-9).</li>
                        <li>Siswa yang sudah memiliki akun akan otomatis <strong>dilewati</strong> sehingga tidak terjadi tabrakan/duplikasi data.</li>
                        <li>Password awal digenerate acak sementara dan ter-hash bcrypt.</li>
                        <li>Setiap siswa diwajibkan mengganti kata sandi saat pertama kali login.</li>
                    </ul>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-border-soft">
                    <button type="button" onclick="closeGenerateModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-muted-text hover:bg-soft transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-xs transition-colors flex items-center gap-2">
                        <x-icon name="check" class="w-4 h-4" />
                        <span>Eksekusi Generate Massal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Buat Akun Satuan Siswa (AKN-2) -->
    <div id="modalBuatAkunSiswa" class="fixed inset-0 z-50 hidden bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft relative animate-in fade-in duration-150 space-y-4">
            <h3 class="text-base font-bold text-darkgreen" id="modalBuatAkunSiswaTitle">Buat Akun Satuan Siswa</h3>
            <p class="text-xs text-muted-text">
                Username akun siswa otomatis menggunakan <strong>NIS (Nomor Induk Siswa)</strong>.
            </p>

            <form id="formBuatAkunSiswa" onsubmit="submitBuatAkunSiswa(event)" class="space-y-4">
                @csrf
                <input type="hidden" id="buatAkunSiswaId" value="">

                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                        Username Login (NIS)
                    </label>
                    <input 
                        type="text" 
                        id="buatAkunSiswaNisInput" 
                        readonly
                        class="w-full px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft text-muted-text font-mono font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                        Password Awal (Sementara)
                    </label>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            id="buatAkunSiswaPasswordInput" 
                            placeholder="Biarkan kosong untuk password acak..." 
                            class="flex-1 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest font-mono"
                        >
                        <button type="button" onclick="generateRandomSiswaPassword()" class="px-3 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-xs font-bold text-darkgreen transition-colors">
                            Acak
                        </button>
                    </div>
                    <span class="text-[11px] text-muted-text block mt-1">Siswa diwajibkan mengganti kata sandi ini saat pertama login.</span>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" onclick="closeBuatAkunSiswaModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-muted-text hover:bg-soft transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitBuatAkunSiswa" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                        Buat Akun Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Kredensial Siswa Berhasil Dibuat -->
    <div id="modalKredensialSiswa" class="fixed inset-0 z-50 hidden bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft relative animate-in fade-in duration-150 space-y-4">
            <div class="flex items-center gap-2.5 text-emerald-700">
                <x-icon name="check-circle" class="w-6 h-6 shrink-0" />
                <h3 class="text-base font-bold text-darkgreen">Akun Siswa Berhasil Dibuat</h3>
            </div>
            <p class="text-xs text-muted-text">
                Kredensial login siswa siap diberikan ke wali kelas atau siswa terkait:
            </p>

            <div class="p-4 rounded-2xl bg-soft border border-border-soft space-y-2.5 text-xs font-mono">
                <div class="flex justify-between items-center">
                    <span class="text-muted-text font-sans">Username (NIS):</span>
                    <strong class="text-darkgreen text-sm" id="resultSiswaUsername">-</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-muted-text font-sans">Password Sementara:</span>
                    <strong class="text-forest text-sm font-bold" id="resultSiswaPassword">-</strong>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="copySiswaCredentials()" class="px-4 py-2 rounded-xl bg-soft border border-border-soft hover:bg-forest/10 text-xs font-bold text-darkgreen transition-colors">
                    Salin Teks
                </button>
                <button type="button" onclick="closeKredensialSiswaModal()" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                    Selesai
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password / Buat Akun Satuan Siswa -->
    <div id="modalResetPasswordSiswa" class="fixed inset-0 z-50 hidden bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft relative animate-in fade-in duration-150">
            <h3 class="text-base font-bold text-darkgreen" id="modalResetTitle">Atur Kata Sandi Siswa</h3>
            <p class="text-xs text-muted-text mt-1" id="modalResetSubtitle">
                Masukkan password baru untuk akun siswa ini.
            </p>

            <form id="formResetPasswordSiswa" onsubmit="submitResetPasswordSiswa(event)" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" id="resetSiswaId" value="">

                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                        Password Baru
                    </label>
                    <input 
                        type="text" 
                        id="newPasswordSiswaInput" 
                        placeholder="Kosongkan untuk password otomatis..." 
                        minlength="6"
                        class="w-full px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest font-mono"
                    >
                    <span class="text-[11px] text-muted-text block mt-1">Jika dikosongkan, sistem akan meng-generate password acak otomatis.</span>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" onclick="closeResetModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-muted-text hover:bg-soft transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitResetSiswa" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts untuk Modal & Ekspor Kredensial -->
    <script>
        const csrfToken = '{{ csrf_token() }}';

        function openGenerateModal() {
            document.getElementById('modalGenerateMassal').classList.remove('hidden');
        }

        function closeGenerateModal() {
            document.getElementById('modalGenerateMassal').classList.add('hidden');
        }

        function openBuatAkunSiswaModal(siswaId, nama, nis) {
            document.getElementById('buatAkunSiswaId').value = siswaId;
            document.getElementById('modalBuatAkunSiswaTitle').textContent = `Buat Akun Siswa: ${nama}`;
            document.getElementById('buatAkunSiswaNisInput').value = nis;
            document.getElementById('buatAkunSiswaPasswordInput').value = '';
            document.getElementById('modalBuatAkunSiswa').classList.remove('hidden');
        }

        function closeBuatAkunSiswaModal() {
            document.getElementById('modalBuatAkunSiswa').classList.add('hidden');
        }

        function generateRandomSiswaPassword() {
            const randomStr = Math.random().toString(36).substring(2, 6).toUpperCase();
            document.getElementById('buatAkunSiswaPasswordInput').value = `Smp7${randomStr}`;
        }

        async function submitBuatAkunSiswa(e) {
            e.preventDefault();
            const siswaId = document.getElementById('buatAkunSiswaId').value;
            const password = document.getElementById('buatAkunSiswaPasswordInput').value;
            const btn = document.getElementById('btnSubmitBuatAkunSiswa');

            btn.disabled = true;
            btn.textContent = 'Membuat Akun...';

            try {
                const response = await fetch(`/admin/master/siswa/${siswaId}/buat-akun`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        password: password ? password : null
                    })
                });

                const data = await response.json();
                if (data.success) {
                    closeBuatAkunSiswaModal();
                    document.getElementById('resultSiswaUsername').textContent = data.username;
                    document.getElementById('resultSiswaPassword').textContent = data.password_baru;
                    document.getElementById('modalKredensialSiswa').classList.remove('hidden');
                } else {
                    alert(data.message || 'Gagal membuat akun siswa.');
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Buat Akun Siswa';
            }
        }

        function copySiswaCredentials() {
            const u = document.getElementById('resultSiswaUsername').textContent;
            const p = document.getElementById('resultSiswaPassword').textContent;
            navigator.clipboard.writeText(`NIS: ${u}\nPassword: ${p}`);
            alert('Kredensial berhasil disalin ke clipboard!');
        }

        function closeKredensialSiswaModal() {
            document.getElementById('modalKredensialSiswa').classList.add('hidden');
            location.reload();
        }

        function openResetModal(siswaId, nama, nis, username) {
            document.getElementById('resetSiswaId').value = siswaId;
            document.getElementById('modalResetTitle').textContent = `Kata Sandi: ${nama}`;
            document.getElementById('modalResetSubtitle').textContent = username 
                ? `Username: ${username} (NIS: ${nis}). Mengatur ulang password baru.`
                : `Siswa ini belum memiliki akun. Sistem akan membuatkan akun baru dengan username NIS ${nis}.`;
            document.getElementById('newPasswordSiswaInput').value = '';
            document.getElementById('modalResetPasswordSiswa').classList.remove('hidden');
            document.getElementById('newPasswordSiswaInput').focus();
        }

        function closeResetModal() {
            document.getElementById('modalResetPasswordSiswa').classList.add('hidden');
        }

        async function submitResetPasswordSiswa(e) {
            e.preventDefault();
            const siswaId = document.getElementById('resetSiswaId').value;
            const password = document.getElementById('newPasswordSiswaInput').value;
            const btn = document.getElementById('btnSubmitResetSiswa');

            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            try {
                const response = await fetch(`/admin/master/siswa/${siswaId}/reset-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password_baru: password })
                });

                const data = await response.json();
                if (data.success) {
                    alert(`SUKSES!\nUsername: ${data.username}\nPassword Baru: ${data.password_baru}\n\nCatatan: Siswa wajib mengganti kata sandi saat login.`);
                    closeResetModal();
                    location.reload();
                } else {
                    alert(data.message || 'Gagal mengatur ulang kata sandi.');
                }
            } catch (err) {
                alert('Terjadi kesalahan koneksi.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan Password';
            }
        }

        function downloadCsvCredentials() {
            const table = document.getElementById('tableBatchCredentials');
            if (!table) return;

            let csv = [];
            const rows = table.querySelectorAll('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');
                for (let j = 0; j < cols.length; j++) {
                    let text = cols[j].innerText.replace(/"/g, '""').trim();
                    row.push('"' + text + '"');
                }
                csv.push(row.join(','));
            }

            const csvFile = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const downloadLink = document.createElement('a');
            downloadLink.download = 'kredensial-sementara-siswa-' + new Date().toISOString().slice(0, 10) + '.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        function copyAllCredentials() {
            const table = document.getElementById('tableBatchCredentials');
            if (!table) return;

            let text = 'DAFTAR KREDENSIAL SEMENTARA SISWA SMP MUHAMMADIYAH 7 MEDAN\n';
            text += 'Catatan: Siswa wajib ganti password saat login pertama.\n\n';
            
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach((r, idx) => {
                const cols = r.querySelectorAll('td');
                if (cols.length >= 5) {
                    const nis = cols[1].innerText.trim();
                    const nama = cols[2].innerText.trim();
                    const kelas = cols[3].innerText.trim();
                    const pass = cols[4].innerText.trim();
                    text += `${idx + 1}. NIS: ${nis} | Nama: ${nama} (${kelas}) | Password: ${pass}\n`;
                }
            });

            navigator.clipboard.writeText(text).then(() => {
                alert('Seluruh data kredensial berhasil disalin ke clipboard!');
            }).catch(err => {
                alert('Gagal menyalin: ' + err);
            });
        }
    </script>
    </div>
</x-layouts.admin>
