<x-layouts.admin 
    title="Penugasan Guru Mengajar" 
    activeMenu="master-penugasan"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.mapel.index')],
        ['label' => 'Penugasan Guru', 'url' => ''],
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
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Penugasan Guru per Kelas & Semester</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Atur pengampu mata pelajaran (t_guru_mapel) per rombel kelas untuk periode semester aktif.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" onclick="openModalSalinPenugasan()" class="px-3.5 py-2.5 rounded-xl bg-soft border border-border-soft hover:bg-forest/10 text-darkgreen text-xs sm:text-sm font-bold flex items-center gap-2 transition-colors">
                    <x-icon name="document-duplicate" class="w-4 h-4 text-forest" />
                    <span>Salin dari Periode Lalu</span>
                </button>
                <button type="button" onclick="openModalTambahPenugasan()" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Tetapkan Penugasan Guru</span>
                </button>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
            <form method="GET" action="{{ route('admin.master.penugasan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Periode Semester (TASM)</label>
                    <select name="tasm" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen font-semibold">
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn->tahun }}" {{ $selectedTasm == $thn->tahun ? 'selected' : '' }}>
                                Periode {{ $thn->tahun }} - Semester {{ substr($thn->tahun, -1) == '1' ? 'Ganjil' : 'Genap' }} {{ $thn->aktif === 'Y' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Filter Rombel Kelas</label>
                    <select name="id_kelas" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="">Semua Rombel</option>
                        @foreach($semuaKelas as $k)
                            <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Filter Guru Pengampu</label>
                    <select name="id_guru" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="">Semua Guru</option>
                        @foreach($semuaGuru as $g)
                            <option value="{{ $g->id }}" {{ $selectedGuruId == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Filter Mata Pelajaran</label>
                    <div class="flex gap-2">
                        <select name="id_mapel" onchange="this.form.submit()" class="flex-1 px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            <option value="">Semua Mapel</option>
                            @foreach($semuaMapel as $mp)
                                <option value="{{ $mp->id }}" {{ $selectedMapelId == $mp->id ? 'selected' : '' }}>
                                    [{{ $mp->kd_singkat }}] {{ $mp->nama }}
                                </option>
                            @endforeach
                        </select>
                        @if(!empty($selectedKelasId) || !empty($selectedGuruId) || !empty($selectedMapelId))
                            <a href="{{ route('admin.master.penugasan.index', ['tasm' => $selectedTasm]) }}" class="px-3 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs text-muted-text flex items-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="pt-3 border-t border-border-soft flex items-center justify-between text-xs text-muted-text">
                <div>
                    Periode: <strong class="text-darkgreen font-mono">{{ $selectedTasm }}</strong> | Total Penugasan: <strong class="text-forest">{{ $totalPenugasan }} Baris</strong>
                </div>
                <div>
                    Guru Mengajar: <strong class="text-darkgreen">{{ $totalGuruBertugas }} Orang</strong>
                </div>
            </div>
        </div>

        <!-- Tabel Penugasan Guru -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
            @if($penugasanList->isEmpty())
                <div class="p-12 text-center rounded-2xl border border-dashed border-border-soft text-muted-text text-xs space-y-1">
                    <p class="font-medium text-darkgreen">Tidak ada data penugasan guru pada filter ini.</p>
                    <p>Klik tombol "Tetapkan Penugasan Guru" atau gunakan opsi "Salin dari Periode Lalu".</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-border-soft bg-soft text-darkgreen font-semibold">
                                <th class="py-3 px-4 w-12">No</th>
                                <th class="py-3 px-4">Nama Guru Pengampu</th>
                                <th class="py-3 px-4">Mata Pelajaran</th>
                                <th class="py-3 px-4">Rombel Kelas</th>
                                <th class="py-3 px-4">Tingkat</th>
                                <th class="py-3 px-4">Periode</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-soft">
                            @foreach($penugasanList as $idx => $item)
                                <tr class="hover:bg-soft/40 transition-colors">
                                    <td class="py-3 px-4 text-muted-text">{{ $penugasanList->firstItem() + $idx }}</td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-darkgreen block">{{ $item->guru?->nama ?? '-' }}</span>
                                        <span class="text-[11px] font-mono text-muted-text">{{ $item->guru?->nip ?? 'NIP: -' }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                {{ $item->mapel?->kd_singkat ?? '-' }}
                                            </span>
                                            <span class="font-semibold text-darkgreen">{{ $item->mapel?->nama ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-forest">{{ $item->kelas?->nama ?? '-' }}</td>
                                    <td class="py-3 px-4 text-muted-text">Tingkat {{ $item->kelas?->tingkat ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            {{ $item->tasm }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <form method="POST" action="{{ route('admin.master.penugasan.destroy', $item->id) }}" class="inline" onsubmit="return confirm('Hapus penugasan {{ $item->guru?->nama }} di kelas {{ $item->kelas?->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg border border-border-soft hover:bg-rose-50 text-rose-600" title="Hapus Penugasan">
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
                    {{ $penugasanList->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Tambah Penugasan (Multi-Kelas) -->
    <div id="modalTambahPenugasan" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-xl border border-border-soft"
             style="max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;">

            <!-- Header (tetap, tidak ikut scroll) -->
            <div class="flex items-center justify-between border-b border-border-soft px-6 py-4"
                 style="flex-shrink: 0;">
                <h3 class="text-base font-bold text-darkgreen">Tetapkan Penugasan Guru</h3>
                <button type="button" onclick="closeModalTambahPenugasan()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.master.penugasan.store') }}"
                  style="display: flex; flex-direction: column; flex: 1 1 auto; min-height: 0;">
                @csrf
                <input type="hidden" name="tasm" value="{{ $selectedTasm }}">

                <!-- Area field (bagian ini yang scroll) -->
                <div class="px-6 py-4 space-y-4" style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Periode Semester (TASM)</label>
                        <input type="text" value="{{ $selectedTasm }}" readonly 
                               class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft bg-soft text-muted-text font-mono font-bold" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Pilih Guru Pengampu <span class="text-rose-500">*</span></label>
                        <select name="id_guru" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($semuaGuru as $g)
                                <option value="{{ $g->id }}" {{ $selectedGuruId == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama }} {{ $g->nip ? "({$g->nip})" : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Pilih Mata Pelajaran <span class="text-rose-500">*</span></label>
                        <select name="id_mapel" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($semuaMapel as $mp)
                                <option value="{{ $mp->id }}">
                                    [{{ $mp->kd_singkat }}] {{ $mp->nama }} (Kelompok {{ $mp->kelompok }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Pilih Rombel Kelas yang Diajar <span class="text-rose-500">*</span></label>
                        <p class="text-[11px] text-muted-text mb-2">Pilih satu atau beberapa kelas sekaligus:</p>
                        <div class="grid grid-cols-2 gap-2 p-2 border border-border-soft rounded-xl"
                             style="max-height: 200px; overflow-y: auto;">
                            @foreach($semuaKelas as $k)
                                <label class="flex items-center gap-2 p-1.5 hover:bg-soft rounded-lg cursor-pointer text-xs">
                                    <input type="checkbox" name="kelas_ids[]" value="{{ $k->id }}" class="rounded text-forest focus:ring-forest" />
                                    <span class="font-medium text-darkgreen">{{ $k->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer tombol (tetap terlihat) -->
                <div class="px-6 py-4 border-t border-border-soft flex items-center justify-end gap-2.5"
                     style="flex-shrink: 0;">
                    <button type="button" onclick="closeModalTambahPenugasan()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Simpan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Salin Penugasan Antar-Periode -->
    <div id="modalSalinPenugasan" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5"
             style="max-height: 90vh; overflow-y: auto;">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Salin Penugasan Antar-Periode</h3>
                <button type="button" onclick="closeModalSalinPenugasan()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.penugasan.salin') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Periode Asal <span class="text-rose-500">*</span></label>
                    <select name="tasm_asal" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn->tahun }}" {{ $selectedTasm != $thn->tahun ? 'selected' : '' }}>
                                Periode {{ $thn->tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Periode Tujuan <span class="text-rose-500">*</span></label>
                    <input type="text" name="tasm_tujuan" value="{{ $selectedTasm }}" readonly 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft bg-soft text-muted-text font-mono font-bold" />
                </div>
                <div class="p-3 rounded-xl bg-amber-50 text-[11px] text-amber-800">
                    Sistem akan menyalin ratusan baris penugasan mengajar dari periode asal ke periode tujuan tanpa menimpa baris yang sudah terdaftar.
                </div>
                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalSalinPenugasan()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Salin Seluruh Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalTambahPenugasan() {
            document.getElementById('modalTambahPenugasan').classList.remove('hidden');
        }
        function closeModalTambahPenugasan() {
            document.getElementById('modalTambahPenugasan').classList.add('hidden');
        }

        function openModalSalinPenugasan() {
            document.getElementById('modalSalinPenugasan').classList.remove('hidden');
        }
        function closeModalSalinPenugasan() {
            document.getElementById('modalSalinPenugasan').classList.add('hidden');
        }
    </script>
</x-layouts.admin>