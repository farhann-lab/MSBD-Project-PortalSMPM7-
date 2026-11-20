<x-layouts.admin 
    title="Manajemen Tahun Ajaran & Semester" 
    activeMenu="master-tahun-ajaran"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.tahun-ajaran.index')],
        ['label' => 'Tahun Ajaran', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Flash Message Alerts -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2">
                    <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 shrink-0" />
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2">
                    <x-icon name="exclamation-circle" class="w-5 h-5 text-rose-600 shrink-0" />
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <x-icon name="exclamation-circle" class="w-4 h-4 text-rose-600" />
                    <span>Terjadi kesalahan validasi:</span>
                </div>
                <ul class="list-disc list-inside text-rose-700 pl-4 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Active Period Banner (TAJ-5) -->
        <div class="p-6 rounded-3xl bg-linear-to-br from-forest to-darkgreen text-white shadow-md relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="relative z-10 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-emerald-400 text-darkgreen shadow-xs">
                        Periode Aktif Saat Ini
                    </span>
                    <span class="font-mono text-xs text-white/80">TASM: {{ $periodeAktif?->tahun ?? '-' }}</span>
                </div>
                <h2 class="text-2xl font-bold tracking-tight">
                    {{ $periodeAktif?->nama_lengkap ?? 'Belum ada periode yang aktif' }}
                </h2>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs text-white/80 pt-1">
                    <div>
                        Kepala Sekolah: <strong class="text-white">{{ $periodeAktif?->nama_kepsek ?? '-' }}</strong>
                        @if($periodeAktif?->nip_kepsek)
                            <span class="font-mono text-white/70">({{ $periodeAktif->nip_kepsek }})</span>
                        @endif
                    </div>
                    <div>
                        Tgl Rapor Kelas 7 & 8: <strong class="text-white">{{ $periodeAktif?->tgl_raport?->format('d M Y') ?? '-' }}</strong>
                    </div>
                    <div>
                        Tgl Rapor Kelas 9: <strong class="text-white">{{ $periodeAktif?->tgl_raport_kelas3?->format('d M Y') ?? '-' }}</strong>
                    </div>
                </div>
            </div>
            
            <div class="relative z-10 shrink-0 flex items-center gap-3">
                <button type="button" onclick="openModalTambahPeriode()" class="px-4 py-2.5 rounded-xl bg-white text-forest hover:bg-white/90 text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Tambah Periode Baru</span>
                </button>
            </div>

            <!-- Background decorative watermark -->
            <div class="absolute -right-6 -bottom-10 opacity-10 pointer-events-none text-white">
                <x-icon name="calendar" class="w-56 h-56" />
            </div>
        </div>

        <!-- Quick Summary Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] text-muted-text uppercase font-semibold">Total Periode Terdaftar</span>
                <p class="text-2xl font-bold text-darkgreen mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] text-muted-text uppercase font-semibold">Semester Ganjil</span>
                <p class="text-2xl font-bold text-forest mt-1">{{ $stats['ganjil'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] text-muted-text uppercase font-semibold">Semester Genap</span>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['genap'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] text-muted-text uppercase font-semibold">Status Konteks Akademik</span>
                <p class="text-sm font-bold text-emerald-700 mt-2 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Tersinkronisasi</span>
                </p>
            </div>
        </div>

        <!-- Table Card: Daftar Periode Tahun Ajaran -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div>
                    <h3 class="text-sm font-bold text-darkgreen">Daftar Periode Tahun Ajaran & Semester</h3>
                    <p class="text-xs text-muted-text">Semua periode yang terdaftar pada sistem database bersama RKM (tabel tahun).</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-border-soft bg-soft text-darkgreen font-semibold">
                            <th class="py-3 px-4 w-12">No</th>
                            <th class="py-3 px-4">Kode (TASM)</th>
                            <th class="py-3 px-4">Tahun Ajaran & Semester</th>
                            <th class="py-3 px-4">Kepala Sekolah & NIP</th>
                            <th class="py-3 px-4">Tgl Rapor Kelas 7 & 8</th>
                            <th class="py-3 px-4">Tgl Rapor Kelas 9</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach($periodeList as $idx => $item)
                            <tr class="hover:bg-soft/40 transition-colors {{ $item->isAktif() ? 'bg-emerald-50/40' : '' }}">
                                <td class="py-3 px-4 text-muted-text">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-darkgreen">
                                    <span class="px-2 py-0.5 rounded {{ $item->isAktif() ? 'bg-forest text-white' : 'bg-gray-100 text-darkgreen' }}">
                                        {{ $item->tahun }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-darkgreen block">{{ $item->nama_lengkap }}</span>
                                    <span class="text-[11px] text-muted-text">Semester {{ $item->semester }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-darkgreen block">{{ $item->nama_kepsek }}</span>
                                    <span class="text-[11px] font-mono text-muted-text">{{ $item->nip_kepsek ?? 'NIP: -' }}</span>
                                </td>
                                <td class="py-3 px-4 text-muted-text">
                                    {{ $item->tgl_raport ? \Carbon\Carbon::parse($item->tgl_raport)->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-muted-text">
                                    {{ $item->tgl_raport_kelas3 ? \Carbon\Carbon::parse($item->tgl_raport_kelas3)->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->isAktif())
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-2xs">
                                            Aktif (Acuan)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-gray-100 text-muted-text">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right space-x-1.5 whitespace-nowrap">
                                    @if(!$item->isAktif())
                                        <form method="POST" action="{{ route('admin.master.tahun-ajaran.aktifkan', $item->id) }}" class="inline" onsubmit="return confirm('PENTING: Aktifkan periode {{ $item->nama_lengkap }} ({{ $item->tahun }}) sebagai acuan seluruh proses akademik sistem?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold shadow-2xs transition-colors">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" 
                                            onclick="openModalEditPeriode('{{ $item->id }}', '{{ $item->tahun }}', '{{ addslashes($item->nama_lengkap) }}', '{{ addslashes($item->nama_kepsek) }}', '{{ $item->nip_kepsek }}', '{{ $item->tgl_raport ? \Carbon\Carbon::parse($item->tgl_raport)->format('Y-m-d') : '' }}', '{{ $item->tgl_raport_kelas3 ? \Carbon\Carbon::parse($item->tgl_raport_kelas3)->format('Y-m-d') : '' }}')" 
                                            class="p-1.5 rounded-lg border border-border-soft hover:bg-forest/10 text-forest" title="Edit Informasi">
                                        <x-icon name="pencil" class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Tambah Periode Tahun Ajaran -->
    <div id="modalTambahPeriode" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Tambah Periode Tahun Ajaran Baru</h3>
                <button type="button" onclick="closeModalTambahPeriode()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.tahun-ajaran.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Kode TASM (5 Digit) <span class="text-rose-500">*</span></label>
                    <input type="text" name="tahun" maxlength="5" required placeholder="Contoh: 20251 (Ganjil) atau 20252 (Genap)" 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen font-mono font-bold" />
                    <p class="text-[11px] text-muted-text mt-1">Format: 4 digit tahun awal ajaran + digit semester (1 = Ganjil, 2 = Genap).</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Nama Kepala Sekolah <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kepsek" required placeholder="Nama lengkap & gelar" 
                               value="{{ $periodeAktif?->nama_kepsek }}"
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">NIP Kepala Sekolah</label>
                        <input type="text" name="nip_kepsek" placeholder="Nomor Induk Pegawai" 
                               value="{{ $periodeAktif?->nip_kepsek }}"
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen font-mono" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Tgl Tanda Tangan Rapor Kelas 7 & 8 <span class="text-rose-500">*</span></label>
                        <input type="date" name="tgl_raport" required 
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Tgl Tanda Tangan Rapor Kelas 9 <span class="text-rose-500">*</span></label>
                        <input type="date" name="tgl_raport_kelas3" required 
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalTambahPeriode()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Simpan Periode
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Periode Tahun Ajaran -->
    <div id="modalEditPeriode" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Edit Informasi Periode & Rapor</h3>
                <button type="button" onclick="closeModalEditPeriode()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" id="formEditPeriode" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Periode Tahun Ajaran (TASM)</label>
                    <input type="text" id="editPeriodeLabel" readonly 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft bg-soft text-muted-text font-bold" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Nama Kepala Sekolah <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kepsek" id="editNamaKepsek" required 
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">NIP Kepala Sekolah</label>
                        <input type="text" name="nip_kepsek" id="editNipKepsek" 
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen font-mono" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Tgl Rapor Kelas 7 & 8 <span class="text-rose-500">*</span></label>
                        <input type="date" name="tgl_raport" id="editTglRaport" required 
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Tgl Rapor Kelas 9 <span class="text-rose-500">*</span></label>
                        <input type="date" name="tgl_raport_kelas3" id="editTglRaportK3" required 
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalEditPeriode()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Perbarui Informasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalTambahPeriode() {
            document.getElementById('modalTambahPeriode').classList.remove('hidden');
        }
        function closeModalTambahPeriode() {
            document.getElementById('modalTambahPeriode').classList.add('hidden');
        }

        function openModalEditPeriode(id, tahun, namaLengkap, namaKepsek, nipKepsek, tglRaport, tglRaportK3) {
            document.getElementById('formEditPeriode').action = '{{ url("admin/master/tahun-ajaran") }}/' + id;
            document.getElementById('editPeriodeLabel').value = namaLengkap + ' (' + tahun + ')';
            document.getElementById('editNamaKepsek').value = namaKepsek;
            document.getElementById('editNipKepsek').value = nipKepsek || '';
            document.getElementById('editTglRaport').value = tglRaport;
            document.getElementById('editTglRaportK3').value = tglRaportK3;
            document.getElementById('modalEditPeriode').classList.remove('hidden');
        }
        function closeModalEditPeriode() {
            document.getElementById('modalEditPeriode').classList.add('hidden');
        }
    </script>
</x-layouts.admin>
