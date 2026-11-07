<x-layouts.admin 
    title="Kelola Prestasi Publik" 
    activeMenu="web-prestasi"
    :breadcrumbs="[
        ['label' => 'Website Sekolah', 'url' => ''],
        ['label' => 'Prestasi Publik', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-forest/10 text-forest text-xs font-bold">
                    <x-icon name="trophy" class="w-3.5 h-3.5" />
                    <span>Prestasi & Penghargaan</span>
                </div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen Prestasi Publik</h2>
                <p class="text-xs sm:text-sm text-muted-text">
                    Kelola pencapaian juara dan kejuaraan siswa yang dipublikasikan di halaman kesiswaan sekolah.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a 
                    href="{{ route('admin.cms.prestasi.create') }}" 
                    class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors cursor-pointer"
                >
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Tambah Prestasi Baru</span>
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs text-muted-text font-medium block">Total Prestasi</span>
                <span class="text-2xl font-extrabold text-darkgreen mt-1 block">{{ $stats['total'] }}</span>
            </div>
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs text-muted-text font-medium block">Published</span>
                <span class="text-2xl font-extrabold text-emerald-700 mt-1 block">{{ $stats['published'] }}</span>
            </div>
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs text-muted-text font-medium block">Draft</span>
                <span class="text-2xl font-extrabold text-amber-600 mt-1 block">{{ $stats['draft'] }}</span>
            </div>
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs text-muted-text font-medium block">Variasi Tingkat</span>
                <span class="text-2xl font-extrabold text-forest mt-1 block">{{ $stats['tingkat'] }}</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.cms.prestasi.index') }}" class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                    <x-icon name="search" class="w-4 h-4 text-sage" />
                </span>
                <input 
                    type="text" 
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari judul prestasi..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2">
                <select 
                    name="status" 
                    onchange="this.form.submit()"
                    class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                >
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>

                <select 
                    name="tingkat" 
                    onchange="this.form.submit()"
                    class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                >
                    <option value="">Semua Tingkat</option>
                    <option value="Kota" {{ request('tingkat') === 'Kota' ? 'selected' : '' }}>Tingkat Kota</option>
                    <option value="Provinsi" {{ request('tingkat') === 'Provinsi' ? 'selected' : '' }}>Tingkat Provinsi</option>
                    <option value="Nasional" {{ request('tingkat') === 'Nasional' ? 'selected' : '' }}>Tingkat Nasional</option>
                    <option value="Internasional" {{ request('tingkat') === 'Internasional' ? 'selected' : '' }}>Tingkat Internasional</option>
                </select>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-forest text-white text-xs sm:text-sm font-semibold hover:bg-forest/90 transition-colors">
                    Cari
                </button>

                @if(request()->hasAny(['q', 'status', 'tingkat']))
                    <a href="{{ route('admin.cms.prestasi.index') }}" class="px-3 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs flex items-center gap-1 transition-colors">
                        <x-icon name="x-mark" class="w-3.5 h-3.5" />
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>

        <!-- Table Card -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Prestasi</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Tingkat</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status</th>
                            <th class="py-3.5 px-4 sm:px-6">Tanggal Kejuaraan</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @forelse($prestasiList as $item)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 max-w-md">
                                    <div class="flex items-start gap-3">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-border-soft">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-border-soft">
                                                <x-icon name="trophy" class="w-5 h-5" />
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.cms.prestasi.edit', $item) }}" class="font-bold text-darkgreen hover:text-forest transition-colors block">
                                                {{ $item->judul }}
                                            </a>
                                            <p class="text-xs text-muted-text line-clamp-1 mt-0.5">{{ $item->deskripsi ?? 'Tanpa rincian deskripsi' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ $item->tingkat ?? 'Sekolah' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($item->status === 'published')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Published</span>
                                        </span>
                                    @elseif($item->status === 'draft')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Draft</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                            <span>Archived</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-xs text-muted-text">
                                    {{ $item->formatted_date }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <a 
                                            href="{{ route('admin.cms.prestasi.edit', $item) }}" 
                                            class="p-2 rounded-lg border border-border-soft hover:bg-soft text-darkgreen hover:text-forest transition-colors"
                                            title="Edit Prestasi"
                                        >
                                            <x-icon name="pencil" class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('admin.cms.prestasi.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus prestasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="p-2 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer"
                                                title="Hapus Prestasi"
                                            >
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-muted-text text-sm">
                                    Belum ada data prestasi yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($prestasiList->hasPages())
                <div class="p-4 border-t border-border-soft">
                    {{ $prestasiList->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.admin>
