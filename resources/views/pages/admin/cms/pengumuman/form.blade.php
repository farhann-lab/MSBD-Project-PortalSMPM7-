<x-layouts.admin 
    :title="$isEdit ? 'Edit Pengumuman' : 'Buat Pengumuman Baru'" 
    activeMenu="web-pengumuman"
    :breadcrumbs="[
        ['label' => 'Website Sekolah', 'url' => ''],
        ['label' => 'Pengumuman', 'url' => route('admin.cms.pengumuman.index')],
        ['label' => $isEdit ? 'Edit' : 'Tambah', 'url' => ''],
    ]"
>
    <div class="max-w-4xl space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">
                    {{ $isEdit ? 'Perbarui Pengumuman Sekolah' : 'Buat Pengumuman Baru' }}
                </h2>
                <p class="text-xs sm:text-sm text-muted-text mt-0.5">
                    Informasi penting dan edaran resmi dari pihak SMP Muhammadiyah 7 Medan.
                </p>
            </div>
            <a 
                href="{{ route('admin.cms.pengumuman.index') }}" 
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
            action="{{ $isEdit ? route('admin.cms.pengumuman.update', $pengumuman) : route('admin.cms.pengumuman.store') }}" 
            method="POST" 
            class="p-6 sm:p-8 rounded-3xl bg-white border border-border-soft shadow-xs space-y-6"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- Judul Pengumuman -->
            <div>
                <label for="judul" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Judul Pengumuman <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="judul" 
                    id="judul" 
                    value="{{ old('judul', $pengumuman->judul) }}"
                    required
                    placeholder="Contoh: Jadwal Ujian Tengah Semester Ganjil 2026/2027..."
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                >
            </div>

            <!-- Konten Isi Pengumuman -->
            <div>
                <label for="isi" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                    Konten Isi Pengumuman <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="isi" 
                    id="isi" 
                    rows="10" 
                    required
                    placeholder="Tuliskan edaran pengumuman secara rinci..."
                    class="w-full px-4 py-3 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen leading-relaxed font-sans"
                >{{ old('isi', $pengumuman->isi) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Tanggal Pengumuman -->
                <div>
                    <label for="tanggal" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                        Tanggal Berlaku / Diumumkan <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="tanggal" 
                        id="tanggal" 
                        value="{{ old('tanggal', $pengumuman->tanggal ? $pengumuman->tanggal->format('Y-m-d') : date('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                    >
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label for="status" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                        Status Pengumuman <span class="text-red-500">*</span>
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
    $selectedStatus = old('status', $pengumuman->status);
@endphp

@if($isEdit && ! in_array($selectedStatus, $statusOptions))
    <option value="" selected disabled>Pilih status</option>
@endif

@foreach($statusOptions as $opt)
    <option value="{{ $opt }}" {{ $selectedStatus === $opt ? 'selected' : '' }}>{{ $statusLabels[$opt] }}</option>
@endforeach
</select>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-border-soft flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.cms.pengumuman.index') }}" 
                    class="px-5 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-sm font-semibold transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-sm font-bold shadow-xs transition-colors cursor-pointer"
                >
                    {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan Pengumuman' }}
                </button>
            </div>

        </form>

    </div>
</x-layouts.admin>
