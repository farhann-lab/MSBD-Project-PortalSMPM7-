<x-layouts.admin 
    title="Manajemen Mata Pelajaran" 
    activeMenu="master-mapel"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.mapel.index')],
        ['label' => 'Mata Pelajaran', 'url' => ''],
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

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Kurikulum & Mata Pelajaran</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Kelola data referensi mata pelajaran (m_mapel) sesuai kelompok kurikulum nasional dan muatan lokal.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('admin.master.penugasan.index') }}" class="px-3.5 py-2.5 rounded-xl bg-soft border border-border-soft hover:bg-forest/10 text-darkgreen text-xs sm:text-sm font-bold flex items-center gap-2 transition-colors">
                    <x-icon name="clipboard-check" class="w-4 h-4 text-forest" />
                    <span>Penugasan Guru</span>
                </a>
                <button type="button" onclick="openModalTambahMapel()" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Tambah Mata Pelajaran</span>
                </button>
            </div>
        </div>

        <!-- Quick Summary Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] text-muted-text uppercase font-semibold">Total Mapel</span>
                <p class="text-2xl font-bold text-darkgreen mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] text-muted-text uppercase font-semibold">Kelompok A (Umum)</span>
                <p class="text-2xl font-bold text-forest mt-1">{{ $stats['kelompok_a'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] text-muted-text uppercase font-semibold">Kelompok B (Keterampilan)</span>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['kelompok_b'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-[11px] text-muted-text uppercase font-semibold">Kelompok C (Peminatan/Lokal)</span>
                <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['kelompok_c'] }}</p>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.master.mapel.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama atau kode singkat..." 
                           class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                    <x-icon name="search" class="w-4 h-4 text-muted-text absolute left-3 top-2.5" />
                </div>

                <select name="kelompok" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                    <option value="">Semua Kelompok</option>
                    <option value="A" {{ $kelompok === 'A' ? 'selected' : '' }}>Kelompok A</option>
                    <option value="B" {{ $kelompok === 'B' ? 'selected' : '' }}>Kelompok B</option>
                    <option value="C" {{ $kelompok === 'C' ? 'selected' : '' }}>Kelompok C</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-semibold">
                    Filter
                </button>
                @if(!empty($q) || !empty($kelompok))
                    <a href="{{ route('admin.master.mapel.index') }}" class="text-xs text-muted-text hover:text-rose-600 underline">
                        Reset
                    </a>
                @endif
            </form>

            <div class="text-xs text-muted-text self-end md:self-center">
                Menampilkan <strong class="text-darkgreen">{{ $mapels->total() }}</strong> mata pelajaran
            </div>
        </div>

        <!-- Tabel Mata Pelajaran -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
            @if($mapels->isEmpty())
                <div class="p-12 text-center rounded-2xl border border-dashed border-border-soft text-muted-text text-xs space-y-1">
                    <p class="font-medium text-darkgreen">Tidak ada data mata pelajaran yang ditemukan.</p>
                    <p>Silakan sesuaikan kata kunci pencarian atau tambah mata pelajaran baru.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-border-soft bg-soft text-darkgreen font-semibold">
                                <th class="py-3 px-4 w-12">No</th>
                                <th class="py-3 px-4 w-24">Kode</th>
                                <th class="py-3 px-4">Nama Mata Pelajaran</th>
                                <th class="py-3 px-4">Kelompok</th>
                                <th class="py-3 px-4">Kategori Sub</th>
                                <th class="py-3 px-4 text-center">Penilaian Sikap</th>
                                <th class="py-3 px-4 text-center">Digunakan Penugasan</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-soft">
                            @foreach($mapels as $idx => $m)
                                <tr class="hover:bg-soft/40 transition-colors">
                                    <td class="py-3 px-4 text-muted-text">{{ $mapels->firstItem() + $idx }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-darkgreen">
                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ $m->kd_singkat }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-darkgreen">{{ $m->nama }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $m->kelompok === 'A' ? 'bg-blue-50 text-blue-700' : ($m->kelompok === 'B' ? 'bg-amber-50 text-amber-700' : 'bg-purple-50 text-purple-700') }}">
                                            Kelompok {{ $m->kelompok }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($m->tambahan_sub === 'PAI')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                PAI / Keagamaan
                                            </span>
                                        @elseif($m->tambahan_sub === 'MULOK')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Muatan Lokal
                                            </span>
                                        @else
                                            <span class="text-muted-text">Standar (NO)</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($m->is_sikap == '1')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                                                Ya
                                            </span>
                                        @else
                                            <span class="text-muted-text">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $m->penugasan_count > 0 ? 'bg-forest/10 text-forest' : 'bg-gray-100 text-muted-text' }}">
                                            {{ $m->penugasan_count }} Kelas
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1">
                                        <button type="button" 
                                                onclick="openModalEditMapel('{{ $m->id }}', '{{ addslashes($m->nama) }}', '{{ $m->kd_singkat }}', '{{ $m->kelompok }}', '{{ $m->tambahan_sub }}', '{{ $m->is_sikap }}')" 
                                                class="p-1.5 rounded-lg border border-border-soft hover:bg-forest/10 text-forest" title="Edit Mapel">
                                            <x-icon name="pencil" class="w-4 h-4" />
                                        </button>
                                        <form method="POST" action="{{ route('admin.master.mapel.destroy', $m->id) }}" class="inline" onsubmit="return confirm('Hapus mata pelajaran {{ $m->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg border border-border-soft hover:bg-rose-50 text-rose-600" title="Hapus Mapel">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-border-soft">
                    {{ $mapels->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Tambah Mapel -->
    <div id="modalTambahMapel" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Tambah Mata Pelajaran</h3>
                <button type="button" onclick="closeModalTambahMapel()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.mapel.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Nama Mata Pelajaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Contoh: Matematika, Bahasa Arab" 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Kode Singkat (Maks. 5 Karakter) <span class="text-rose-500">*</span></label>
                    <input type="text" name="kd_singkat" maxlength="5" required placeholder="Contoh: MTK, IPA, PAI" 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen uppercase font-mono font-bold" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Kelompok <span class="text-rose-500">*</span></label>
                        <select name="kelompok" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            <option value="A">Kelompok A (Umum)</option>
                            <option value="B">Kelompok B (Keterampilan)</option>
                            <option value="C">Kelompok C (Lokal/Peminatan)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Jenis Tambahan <span class="text-rose-500">*</span></label>
                        <select name="tambahan_sub" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            <option value="NO">NO (Standar)</option>
                            <option value="PAI">PAI</option>
                            <option value="MULOK">MULOK</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Penilaian Sikap Spiritual/Sosial <span class="text-rose-500">*</span></label>
                    <select name="is_sikap" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="0">Tidak (0)</option>
                        <option value="1">Ya, Melakukan Penilaian Sikap (1)</option>
                    </select>
                </div>
                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalTambahMapel()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Simpan Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Mapel -->
    <div id="modalEditMapel" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Edit Mata Pelajaran</h3>
                <button type="button" onclick="closeModalEditMapel()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" id="formEditMapel" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Nama Mata Pelajaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" id="editNama" required 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Kode Singkat <span class="text-rose-500">*</span></label>
                    <input type="text" name="kd_singkat" id="editKdSingkat" maxlength="5" required 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen uppercase font-mono font-bold" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Kelompok <span class="text-rose-500">*</span></label>
                        <select name="kelompok" id="editKelompok" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            <option value="A">Kelompok A</option>
                            <option value="B">Kelompok B</option>
                            <option value="C">Kelompok C</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Jenis Tambahan <span class="text-rose-500">*</span></label>
                        <select name="tambahan_sub" id="editTambahanSub" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            <option value="NO">NO (Standar)</option>
                            <option value="PAI">PAI</option>
                            <option value="MULOK">MULOK</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Penilaian Sikap <span class="text-rose-500">*</span></label>
                    <select name="is_sikap" id="editIsSikap" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="0">Tidak (0)</option>
                        <option value="1">Ya, Melakukan Penilaian Sikap (1)</option>
                    </select>
                </div>
                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalEditMapel()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Perbarui Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalTambahMapel() {
            document.getElementById('modalTambahMapel').classList.remove('hidden');
        }
        function closeModalTambahMapel() {
            document.getElementById('modalTambahMapel').classList.add('hidden');
        }

        function openModalEditMapel(id, nama, kdSingkat, kelompok, tambahanSub, isSikap) {
            document.getElementById('formEditMapel').action = '{{ url("admin/master/mapel") }}/' + id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editKdSingkat').value = kdSingkat;
            document.getElementById('editKelompok').value = kelompok;
            document.getElementById('editTambahanSub').value = tambahanSub;
            document.getElementById('editIsSikap').value = isSikap;
            document.getElementById('modalEditMapel').classList.remove('hidden');
        }
        function closeModalEditMapel() {
            document.getElementById('modalEditMapel').classList.add('hidden');
        }
    </script>
</x-layouts.admin>
