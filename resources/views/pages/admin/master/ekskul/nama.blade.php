@php
    $title = $ekskul->exists ? 'Ubah Nama Ekstrakurikuler' : 'Tambah Ekstrakurikuler';
    $action = $ekskul->exists ? route('admin.master.ekskul.update', $ekskul) : route('admin.master.ekskul.store');
@endphp

<x-layouts.admin :title="$title" activeMenu="master-ekskul"
    :breadcrumbs="[
        ['label' => 'Ekstrakurikuler', 'url' => route('admin.master.ekskul')],
        ['label' => $title, 'url' => ''],
    ]"
>
    <form action="{{ $action }}" method="POST" class="max-w-2xl space-y-6 rounded-3xl border border-border-soft bg-white p-6 shadow-xs sm:p-8">
        @csrf
        @if($ekskul->exists)
            @method('PUT')
        @endif

        <div>
            <label for="nama" class="mb-2 block text-sm font-semibold text-darkgreen">Nama ekstrakurikuler</label>
            <input id="nama" name="nama" type="text" value="{{ old('nama', $ekskul->nama) }}" maxlength="50" required
                @error('nama') aria-invalid="true" aria-describedby="nama-error" @enderror
                class="min-h-11 w-full rounded-xl border border-border-soft bg-white px-4 py-2 text-sm text-darkgreen focus:outline-2 focus:outline-forest">
            @error('nama')
                <p id="nama-error" role="alert" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-wrap gap-2">
            <x-button type="submit">Simpan Nama</x-button>
            <x-button :href="route('admin.master.ekskul')" variant="outline">Batal</x-button>
        </div>
    </form>
</x-layouts.admin>
