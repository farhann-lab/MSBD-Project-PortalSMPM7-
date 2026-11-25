<x-layouts.admin title="Data Ekstrakurikuler" activeMenu="master-ekskul"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => ''],
        ['label' => 'Ekstrakurikuler', 'url' => ''],
    ]"
>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-border-soft bg-white p-6 shadow-xs sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-muted-text">
                Kelola nama kegiatan yang dipakai RKM dan informasi yang tampil di website sekolah.
            </p>
            <x-button :href="route('admin.master.ekskul.create')" icon="plus" class="shrink-0">
                Tambah Ekstrakurikuler
            </x-button>
        </div>

        <div class="rounded-3xl border border-border-soft bg-white p-4 shadow-xs sm:p-6">
            <form action="{{ route('admin.master.ekskul') }}" method="GET" class="mb-4 flex flex-wrap gap-2">
                <x-search-input :value="$search" placeholder="Cari nama ekstrakurikuler" class="min-w-44" />
                <x-button type="submit" variant="outline">Cari</x-button>
                @if($search !== '')
                    <x-button :href="route('admin.master.ekskul')" variant="ghost">Reset</x-button>
                @endif
            </form>

            @if($ekskulList->isEmpty())
                <x-empty-state title="Ekstrakurikuler tidak ditemukan"
                    :message="$search === '' ? 'Tambahkan kegiatan untuk mulai mengelola data ekstrakurikuler.' : 'Coba cari dengan nama kegiatan yang lain.'" />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-soft text-darkgreen">
                            <tr>
                                <th scope="col" class="px-4 py-3">Nama kegiatan</th>
                                <th scope="col" class="px-4 py-3">Status publikasi</th>
                                <th scope="col" class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-soft">
                            @foreach($ekskulList as $ekskul)
                                <tr>
                                    <th scope="row" class="px-4 py-3 font-semibold text-darkgreen">{{ $ekskul->nama }}</th>
                                    <td class="px-4 py-3">
                                        <x-badge :variant="$ekskul->publik?->status === 'published' ? 'sage' : 'soft'">
                                            {{ $statusLabels[$ekskul->publik?->status] ?? 'Belum diisi' }}
                                        </x-badge>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <x-button :href="route('admin.master.ekskul.edit', $ekskul)" size="sm" variant="outline"
                                                :aria-label="'Ubah nama '.$ekskul->nama">Ubah Nama</x-button>
                                            <x-button :href="route('admin.master.ekskul.publik.edit', $ekskul)" size="sm" variant="soft"
                                                :aria-label="'Kelola informasi publik '.$ekskul->nama">Kelola Informasi Publik</x-button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $ekskulList->links() }}
            @endif
        </div>
    </div>
</x-layouts.admin>
