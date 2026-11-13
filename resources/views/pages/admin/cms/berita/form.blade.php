<x-layouts.admin 
    :title="$isEdit ? 'Edit Berita' : 'Tulis Berita Baru'" 
    activeMenu="web-berita"
    :breadcrumbs="[
        ['label' => 'Website Sekolah', 'url' => ''],
        ['label' => 'Kelola Berita', 'url' => route('admin.cms.berita.index')],
        ['label' => $isEdit ? 'Edit' : 'Tambah', 'url' => ''],
    ]"
>
    <div class="max-w-4xl space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">
                    {{ $isEdit ? 'Perbarui Berita / Artikel' : 'Tulis Berita / Artikel Baru' }}
                </h2>
                <p class="text-xs sm:text-sm text-muted-text mt-0.5">
                    Isi detail informasi berita sekolah secara lengkap untuk dipublikasikan.
                </p>
            </div>
            <a 
                href="{{ route('admin.cms.berita.index') }}" 
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

        <!-- Form Card -->
        <form 
            action="{{ $isEdit ? route('admin.cms.berita.update', $berita) : route('admin.cms.berita.store') }}" 
            method="POST" 
            enctype="multipart/form-data"
            class="p-6 sm:p-8 rounded-3xl bg-white border border-border-soft shadow-xs space-y-6"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- Judul Berita -->
            <div>
                <label for="judul" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Judul Berita <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="judul" 
                    id="judul" 
                    value="{{ old('judul', $berita->judul) }}"
                    required
                    placeholder="Masukkan judul berita yang informatif dan menarik..."
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                >
            </div>

            <!-- Konten Isi Berita -->
            <div>
                <label for="isi" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Konten Berita <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="isi" 
                    id="isi" 
                    rows="10" 
                    required
                    placeholder="Tuliskan isi berita atau artikel secara lengkap. Mendukung format paragraf HTML..."
                    class="w-full px-4 py-3 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen leading-relaxed font-sans"
                >{{ old('isi', $berita->isi) }}</textarea>
                <p class="text-[11px] text-muted-text mt-1">Anda dapat menggunakan tag paragraf &lt;p&gt; untuk memisahkan alinea.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Status Publikasi -->
                <div>
                    <label for="status" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                        Status Publikasi <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="status" 
                        id="status" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                    >@php
    $statusLabels = [
        'published' => 'Published (Tampil di Website)',
        'draft'     => 'Draft (Disimpan Sementara)',
        'archived'  => 'Archived (Diarsipkan)',
    ];
    $selectedStatus = old('status', $berita->status);
@endphp

@if($isEdit && ! in_array($selectedStatus, $statusOptions))
    <option value="" selected disabled>Pilih status</option>
@endif

@foreach($statusOptions as $opt)
    <option value="{{ $opt }}" {{ $selectedStatus === $opt ? 'selected' : '' }}>{{ $statusLabels[$opt] }}</option>
@endforeach
                    </select>
                </div>

                <!-- Tanggal Publikasi -->
                <div>
                    <label for="published_at" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                        Waktu Publikasi
                    </label>
                    <input 
                        type="datetime-local" 
                        name="published_at" 
                        id="published_at" 
                        value="{{ old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                    >
                    <p class="text-[11px] text-muted-text mt-1">Biarkan kosong saat memilih status published untuk menggunakan waktu sekarang.</p>
                </div>
            </div>

            <!-- Upload Gambar / Thumbnail -->
            <div>
                <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Foto Sampul / Gambar Berita
                </label>
                
                @if($isEdit && $berita->image_url)
                    <div class="mb-4 flex items-center gap-4 p-3 rounded-2xl bg-soft border border-border-soft">
                        <img src="{{ $berita->image_url }}" alt="Preview" class="w-20 h-20 rounded-xl object-cover border border-border-soft">
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-darkgreen block">Gambar Saat Ini</span>
                            <label class="inline-flex items-center gap-2 text-xs text-red-600 hover:text-red-800 cursor-pointer">
                                <input type="checkbox" name="hapus_gambar" value="1" class="rounded text-red-600 focus:ring-red-500">
                                <span>Hapus gambar ini</span>
                            </label>
                        </div>
                    </div>
                @endif

                <input 
                    type="file" 
                    name="gambar" 
                    id="gambar" 
                    accept="image/png,image/jpeg,image/webp"
                    class="block w-full text-xs text-muted-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-forest file:text-white hover:file:bg-forest/90 cursor-pointer"
                >
                <p class="text-[11px] text-muted-text mt-1">Format didukung: JPG, PNG, WEBP. Maksimal ukuran: 2 MB.</p>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-border-soft flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.cms.berita.index') }}" 
                    class="px-5 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-sm font-semibold transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-sm font-bold shadow-xs transition-colors cursor-pointer"
                >
                    {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan Berita' }}
                </button>
            </div>

        </form>

    </div>
</x-layouts.admin>
