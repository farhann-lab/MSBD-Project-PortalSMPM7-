@php($gambarUrl = $publik->imageUrl(route('admin.master.ekskul.image', $ekskul)))

<x-layouts.admin title="Informasi Publik Ekstrakurikuler" activeMenu="master-ekskul"
    :breadcrumbs="[
        ['label' => 'Ekstrakurikuler', 'url' => route('admin.master.ekskul')],
        ['label' => 'Informasi Publik', 'url' => ''],
    ]"
>
    <form action="{{ route('admin.master.ekskul.publik.update', $ekskul) }}" method="POST" enctype="multipart/form-data"
        class="max-w-2xl space-y-6 rounded-3xl border border-border-soft bg-white p-6 shadow-xs sm:p-8">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-bold text-darkgreen">{{ $ekskul->nama }}</h2>

        <div>
            <label for="deskripsi" class="mb-2 block text-sm font-semibold text-darkgreen">Deskripsi publik</label>
            <textarea id="deskripsi" name="deskripsi" rows="5"
                @error('deskripsi') aria-invalid="true" aria-describedby="deskripsi-error" @enderror
                class="w-full rounded-xl border border-border-soft px-4 py-3 text-sm text-darkgreen focus:outline-2 focus:outline-forest">{{ old('deskripsi', $publik->deskripsi) }}</textarea>
            @error('deskripsi')
                <p id="deskripsi-error" role="alert" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="gambar" class="mb-2 block text-sm font-semibold text-darkgreen">Gambar kegiatan</label>
            @if($gambarUrl)
                <img src="{{ $gambarUrl }}" alt="Gambar {{ $ekskul->nama }}" class="mb-3 aspect-video w-48 rounded-xl object-cover">
                <label class="mb-3 flex items-center gap-2 text-sm text-darkgreen">
                    <input type="checkbox" name="hapus_gambar" value="1" @checked(old('hapus_gambar'))>
                    Hapus gambar saat ini
                </label>
            @endif
            <input id="gambar" name="gambar" type="file" accept="image/jpeg,image/png,image/webp"
                aria-describedby="gambar-format @error('gambar') gambar-error @enderror" @error('gambar') aria-invalid="true" @enderror
                class="w-full rounded-xl border border-border-soft px-3 py-2 text-sm text-darkgreen file:mr-3 file:rounded-lg file:bg-soft file:px-3 file:py-1 file:font-semibold">
            <p id="gambar-format" class="mt-2 text-xs text-muted-text">JPG, PNG, atau WebP. Maksimal 2 MB.</p>
            @error('gambar')
                <p id="gambar-error" role="alert" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status" class="mb-2 block text-sm font-semibold text-darkgreen">Status publikasi</label>
            <select id="status" name="status" required aria-describedby="status-info @error('status') status-error @enderror"
                @error('status') aria-invalid="true" @enderror
                class="min-h-11 w-full rounded-xl border border-border-soft bg-white px-4 py-2 text-sm text-darkgreen focus:outline-2 focus:outline-forest">
                @foreach($statusLabels as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $publik->status) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <p id="status-info" class="mt-2 text-xs text-muted-text">Pilih Published untuk menampilkan kegiatan di website sekolah.</p>
            @error('status')
                <p id="status-error" role="alert" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-wrap gap-2">
            <x-button type="submit">Simpan Informasi Publik</x-button>
            <x-button :href="route('admin.master.ekskul')" variant="outline">Batal</x-button>
        </div>
    </form>
</x-layouts.admin>
