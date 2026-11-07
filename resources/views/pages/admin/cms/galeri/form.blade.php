<x-layouts.admin 
    :title="$isEdit ? 'Edit Album Galeri' : 'Buat Album Galeri Baru'" 
    activeMenu="web-galeri"
    :breadcrumbs="[
        ['label' => 'Website Sekolah', 'url' => ''],
        ['label' => 'Galeri Foto', 'url' => route('admin.cms.galeri.index')],
        ['label' => $isEdit ? 'Edit' : 'Tambah', 'url' => ''],
    ]"
>
    <div class="max-w-4xl space-y-6">

        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">
                    {{ $isEdit ? 'Perbarui Album Galeri' : 'Buat Album Galeri Baru' }}
                </h2>
                <p class="text-xs sm:text-sm text-muted-text mt-0.5">
                    Unggah kumpulan dokumentasi foto kegiatan siswa, guru, dan sekolah.
                </p>
            </div>
            <a 
                href="{{ route('admin.cms.galeri.index') }}" 
                class="px-3.5 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs font-semibold flex items-center gap-1.5 transition-colors"
            >
                <x-icon name="arrow-left" class="w-4 h-4" />
                <span>Kembali</span>
            </a>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm space-y-1 shadow-xs">
                <div class="font-bold flex items-center gap-2">
                    <x-icon name="exclamation-circle" class="w-4 h-4 text-red-600" />
                    <span>Periksa kembali isian formulir:</span>
                </div>
                <ul class="list-disc list-inside pl-2 space-y-0.5 text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form 
            action="{{ $isEdit ? route('admin.cms.galeri.update', $galeri) : route('admin.cms.galeri.store') }}" 
            method="POST" 
            enctype="multipart/form-data"
            class="p-6 sm:p-8 rounded-3xl bg-white border border-border-soft shadow-xs space-y-6"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div>
                <label for="judul" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Judul Album / Kegiatan <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="judul" 
                    id="judul" 
                    value="{{ old('judul', $galeri->judul) }}"
                    required
                    placeholder="Contoh: Upacara Bendera HUT RI ke-81, Pesantren Ramadhan..."
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                >
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Deskripsi Singkat Album
                </label>
                <textarea 
                    name="deskripsi" 
                    id="deskripsi" 
                    rows="3" 
                    placeholder="Tuliskan keterangan waktu, tempat, atau tujuan kegiatan ini..."
                    class="w-full px-4 py-3 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen leading-relaxed"
                >{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Status Album <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status" 
                    required
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                >
                   @php
    $statusLabels = [
        'published' => 'Published (Tampil di Website)',
        'draft'     => 'Draft (Disimpan Sementara)',
        'archived'  => 'Archived (Diarsipkan)',
    ];
    $selectedStatus = old('status', $galeri->status);
@endphp

@if($isEdit && ! in_array($selectedStatus, $statusOptions))
    <option value="" selected disabled>Pilih status</option>
@endif

@foreach($statusOptions as $opt)
    <option value="{{ $opt }}" {{ $selectedStatus === $opt ? 'selected' : '' }}>{{ $statusLabels[$opt] }}</option>
@endforeach </select>
            </div>

            <!-- Upload Multiple Foto -->
            <div>
                <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Unggah Foto (Bisa pilih lebih dari satu foto)
                </label>
                <input 
                    type="file" 
                    name="fotos[]" 
                    id="fotos" 
                    multiple
                    accept="image/png,image/jpeg,image/webp"
                    class="block w-full text-xs text-muted-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-forest file:text-white hover:file:bg-forest/90 cursor-pointer"
                >
                <p class="text-[11px] text-muted-text mt-1">Format: JPG, PNG, WEBP (maks. 3MB per foto). Anda dapat memilih beberapa file sekaligus.</p>
            </div>

            <!-- Foto yang sudah terunggah (khusus edit) -->
            @if($isEdit && $galeri->foto->isNotEmpty())
                <div class="pt-4 border-t border-border-soft">
                    <span class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-3">
                        Daftar Foto Dalam Album Ini ({{ $galeri->foto->count() }} Foto)
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach($galeri->foto as $f)
                            <div class="relative group rounded-2xl overflow-hidden border border-border-soft bg-soft">
                                <img src="{{ $f->image_url }}" alt="" class="w-full h-28 object-cover">
                                <div class="absolute inset-0 bg-darkgreen/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2">
                                    <form action="{{ route('admin.cms.galeri.destroy-foto', $f) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari galeri?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors cursor-pointer text-xs flex items-center gap-1 shadow-md">
                                            <x-icon name="trash" class="w-3.5 h-3.5" />
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="pt-4 border-t border-border-soft flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.cms.galeri.index') }}" 
                    class="px-5 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-sm font-semibold transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-sm font-bold shadow-xs transition-colors cursor-pointer"
                >
                    {{ $isEdit ? 'Simpan Perubahan' : 'Buat Album' }}
                </button>
            </div>
        </form>

    </div>
</x-layouts.admin>
