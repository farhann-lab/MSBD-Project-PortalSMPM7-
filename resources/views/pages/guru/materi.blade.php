<x-layouts.guru 
    title="Materi Pembelajaran" 
    activeMenu="materi"
    :breadcrumbs="[
        ['label' => 'Guru', 'url' => route('dashboard.guru')],
        ['label' => 'Materi Pembelajaran', 'url' => route('guru.materi')],
    ]"
    :isWaliKelas="$isWaliKelas"
    :kelasPerwalian="$kelasPerwalian"
>
    <div class="space-y-6" x-data="{ modalTambah: false, modalEdit: false, editData: {} }">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold uppercase tracking-wider">
                        Bahan Ajar &amp; Sumber Belajar (MTR-1 s.d. MTR-8)
                    </span>
                    <span class="text-xs text-muted-text">&bull; Semester {{ $tasmAktif }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Kelola Materi Pembelajaran
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Unggah modul, bahan ajar (PDF/Doc/Ppt maks 15MB), atau bagikan tautan video edukasi untuk kelas yang Anda ajar.
                </p>
            </div>

            <button 
                type="button" 
                @click="modalTambah = true"
                class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors shrink-0">
                <x-icon name="plus" class="w-4 h-4" />
                <span>Tambah Materi Baru</span>
            </button>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5">
                <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2.5">
                <x-icon name="exclamation-circle" class="w-5 h-5 text-rose-600 shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <p class="font-bold mb-1">Terdapat kesalahan pengisian:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter & Search Controls -->
        <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('guru.materi') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Filter Mapel</label>
                    <select name="mapel_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($mapelList as $mpl)
                            <option value="{{ $mpl->id }}" {{ request('mapel_id') == $mpl->id ? 'selected' : '' }}>
                                {{ $mpl->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Status</label>
                    <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="arsip" {{ request('status') === 'arsip' ? 'selected' : '' }}>Arsip</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Cari Judul / Deskripsi</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari materi..." class="pl-8 pr-3.5 py-2 rounded-xl border border-border-soft text-xs sm:text-sm focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <x-icon name="magnifying-glass" class="w-4 h-4 text-muted-text absolute left-2.5 top-2.5" />
                    </div>
                </div>
            </form>

            <div class="text-xs text-muted-text">
                Total Materi: <strong class="text-darkgreen">{{ $materiList->total() }}</strong>
            </div>
        </div>

        <!-- Materi Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($materiList as $mtr)
                @php
                    $isTautan = $mtr->jenis === 'tautan';
                    $kelasTargets = $mtr->materiKelas->pluck('guruMapel.kelas.nama')->filter()->unique();
                @endphp
                <div class="bg-white rounded-3xl border border-border-soft p-5 shadow-xs flex flex-col justify-between space-y-4 hover:border-forest/40 transition-all">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $isTautan ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $isTautan ? 'Tautan Eksternal' : 'Dokumen / File' }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $mtr->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-soft text-muted-text' }}">
                                {{ ucfirst($mtr->status) }}
                            </span>
                        </div>

                        <div>
                            <h3 class="font-bold text-darkgreen text-sm sm:text-base line-clamp-2">
                                {{ $mtr->judul }}
                            </h3>
                            <p class="text-xs text-muted-text font-medium mt-0.5">
                                {{ $mtr->mapel->nama ?? '-' }}
                            </p>
                        </div>

                        <p class="text-xs text-muted-text line-clamp-3 leading-relaxed">
                            {{ $mtr->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}
                        </p>

                        <!-- Target Rombel Kelas -->
                        <div class="pt-2 border-t border-border-soft flex flex-wrap gap-1">
                            <span class="text-[11px] text-muted-text block w-full mb-0.5">Kelas Tujuan:</span>
                            @forelse($kelasTargets as $kls)
                                <span class="px-2 py-0.5 rounded-md bg-soft border border-border-soft text-[11px] font-semibold text-darkgreen">
                                    Kelas {{ $kls }}
                                </span>
                            @empty
                                <span class="text-[11px] text-muted-text italic">Belum ditentukan</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-soft flex items-center justify-between gap-2">
                        @if($isTautan)
                            <a href="{{ $mtr->tautan_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-bold text-forest hover:underline">
                                <x-icon name="arrow-top-right-on-square" class="w-4 h-4" />
                                <span>Buka Tautan</span>
                            </a>
                        @else
                            <a href="{{ route('materi.download', $mtr->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-forest hover:underline">
                                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                                <span>Unduh ({{ $mtr->ukuran_formatted }})</span>
                            </a>
                        @endif

                        <div class="flex items-center gap-1">
                            <button 
                                type="button" 
                                @click="editData = { id: {{ $mtr->id }}, judul: '{{ addslashes($mtr->judul) }}', deskripsi: '{{ addslashes($mtr->deskripsi ?? '') }}', status: '{{ $mtr->status }}', jenis: '{{ $mtr->jenis }}', tautan_url: '{{ addslashes($mtr->tautan_url ?? '') }}' }; modalEdit = true"
                                class="p-1.5 rounded-lg text-muted-text hover:text-darkgreen hover:bg-soft transition-colors" title="Edit Materi">
                                <x-icon name="pencil-square" class="w-4 h-4" />
                            </button>

                            <form action="{{ route('guru.materi.destroy', $mtr->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-muted-text hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Materi">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-8 text-center bg-white rounded-3xl border border-border-soft text-muted-text text-xs italic">
                    Belum ada materi pembelajaran yang dibuat. Silakan tambahkan materi baru untuk siswa Anda.
                </div>
            @endforelse
        </div>

        @if($materiList->hasPages())
            <div class="p-4 bg-white rounded-2xl border border-border-soft shadow-xs">
                {{ $materiList->links() }}
            </div>
        @endif

        <!-- ================= MODAL TAMBAH MATERI ================= -->
        <div 
            x-show="modalTambah" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                @click.away="modalTambah = false" 
                class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl border border-border-soft space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div>
                        <h3 class="text-base font-bold text-darkgreen">Tambah Materi Pembelajaran</h3>
                        <p class="text-xs text-muted-text">Publikasikan materi ke satu atau beberapa rombel kelas</p>
                    </div>
                    <button @click="modalTambah = false" class="text-muted-text hover:text-darkgreen">
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                </div>

                <form method="POST" action="{{ route('guru.materi.store') }}" enctype="multipart/form-data" class="space-y-4" x-data="{ jenisMateri: 'file', selectedMapel: '' }">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                        <select name="id_mapel" x-model="selectedMapel" required class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapelList as $mpl)
                                <option value="{{ $mpl->id }}">{{ $mpl->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Pilih Kelas Tujuan <span class="text-rose-500">*</span></label>
                        <div class="p-3 rounded-xl border border-border-soft bg-soft/50 max-h-36 overflow-y-auto space-y-2">
                            @forelse($penugasanAktif as $gm)
                                <label class="flex items-center gap-2 text-xs text-darkgreen cursor-pointer" x-show="!selectedMapel || selectedMapel == '{{ $gm->id_mapel }}'">
                                    <input type="checkbox" name="id_guru_mapel[]" value="{{ $gm->id }}" class="rounded text-forest focus:ring-mint">
                                    <span>Kelas {{ $gm->kelas->nama ?? '-' }} ({{ $gm->mapel->nama ?? '-' }})</span>
                                </label>
                            @empty
                                <span class="text-xs text-muted-text italic">Belum ada kelas penugasan mengajar aktif.</span>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Judul Materi <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" required placeholder="Contoh: Modul 1 Persamaan Linear Dua Variabel" class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Deskripsi / Petunjuk Belajar</label>
                        <textarea name="deskripsi" rows="2" placeholder="Catatan instruksi untuk siswa..." class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs focus:ring-2 focus:ring-mint focus:outline-hidden"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Jenis Materi</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-border-soft cursor-pointer text-xs" :class="jenisMateri === 'file' ? 'bg-forest/5 border-forest font-bold text-darkgreen' : 'text-muted-text'">
                                <input type="radio" name="jenis" value="file" x-model="jenisMateri" class="sr-only">
                                <span>Dokumen / Berkas</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-border-soft cursor-pointer text-xs" :class="jenisMateri === 'tautan' ? 'bg-forest/5 border-forest font-bold text-darkgreen' : 'text-muted-text'">
                                <input type="radio" name="jenis" value="tautan" x-model="jenisMateri" class="sr-only">
                                <span>Tautan Eksternal</span>
                            </label>
                        </div>
                    </div>

                    <div x-show="jenisMateri === 'file'">
                        <label class="block text-xs font-bold text-darkgreen mb-1">Unggah Berkas (PDF, DOC, PPT, XLS, Gambar maks 15 MB) <span class="text-rose-500">*</span></label>
                        <input type="file" name="file_materi" class="w-full text-xs text-muted-text file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-forest file:text-white hover:file:bg-forest/90">
                    </div>

                    <div x-show="jenisMateri === 'tautan'">
                        <label class="block text-xs font-bold text-darkgreen mb-1">Alamat Tautan URL (Google Drive / YouTube / Web) <span class="text-rose-500">*</span></label>
                        <input type="url" name="tautan_url" placeholder="https://..." class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-border-soft">
                        <button type="button" @click="modalTambah = false" class="px-4 py-2 rounded-xl border border-border-soft text-xs font-semibold text-muted-text hover:bg-soft transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold text-xs shadow-xs transition-colors">
                            Simpan Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL EDIT MATERI ================= -->
        <div 
            x-show="modalEdit" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                @click.away="modalEdit = false" 
                class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl border border-border-soft space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div>
                        <h3 class="text-base font-bold text-darkgreen">Edit Materi Pembelajaran</h3>
                        <p class="text-xs text-muted-text">Sesuaikan judul, deskripsi, atau ganti file</p>
                    </div>
                    <button @click="modalEdit = false" class="text-muted-text hover:text-darkgreen">
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                </div>

                <form method="POST" :action="'{{ url('guru/materi') }}/' + editData.id" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Judul Materi <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" x-model="editData.judul" required class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Deskripsi</label>
                        <textarea name="deskripsi" x-model="editData.deskripsi" rows="2" class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs focus:ring-2 focus:ring-mint focus:outline-hidden"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Status Publikasi</label>
                        <select name="status" x-model="editData.status" required class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                            <option value="aktif">Aktif (Dapat dilihat siswa)</option>
                            <option value="arsip">Arsip (Disembunyikan)</option>
                        </select>
                    </div>

                    <div x-show="editData.jenis === 'file'">
                        <label class="block text-xs font-bold text-darkgreen mb-1">Ganti Berkas (Opsional)</label>
                        <input type="file" name="file_materi" class="w-full text-xs text-muted-text file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-forest file:text-white hover:file:bg-forest/90">
                    </div>

                    <div x-show="editData.jenis === 'tautan'">
                        <label class="block text-xs font-bold text-darkgreen mb-1">Tautan URL Eksternal</label>
                        <input type="url" name="tautan_url" x-model="editData.tautan_url" class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-border-soft">
                        <button type="button" @click="modalEdit = false" class="px-4 py-2 rounded-xl border border-border-soft text-xs font-semibold text-muted-text hover:bg-soft transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold text-xs shadow-xs transition-colors">
                            Perbarui Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.guru>
