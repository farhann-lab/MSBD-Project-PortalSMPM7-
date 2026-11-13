<x-layouts.admin 
    title="Kelola Pengumuman Sekolah" 
    activeMenu="web-pengumuman"
    :breadcrumbs="[
        ['label' => 'Website Sekolah', 'url' => ''],
        ['label' => 'Pengumuman', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-forest/10 text-forest text-xs font-bold">
                    <x-icon name="announcement" class="w-3.5 h-3.5" />
                    <span>Edaran & Informasi Resmi</span>
                </div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen Pengumuman</h2>
                <p class="text-xs sm:text-sm text-muted-text">
                    Kelola surat edaran, instruksi akademik, dan pengumuman resmi sekolah untuk siswa, orang tua, dan guru.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a 
                    href="{{ route('admin.cms.pengumuman.create') }}" 
                    class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors cursor-pointer"
                >
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Buat Pengumuman Baru</span>
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs text-muted-text font-medium block">Total Pengumuman</span>
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
                <span class="text-xs text-muted-text font-medium block">Arsip</span>
                <span class="text-2xl font-extrabold text-muted-text mt-1 block">{{ $stats['archived'] }}</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.cms.pengumuman.index') }}" class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                    <x-icon name="search" class="w-4 h-4 text-sage" />
                </span>
                <input 
                    type="text" 
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari judul atau isi pengumuman..." 
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

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-forest text-white text-xs sm:text-sm font-semibold hover:bg-forest/90 transition-colors">
                    Cari
                </button>

                @if(request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.cms.pengumuman.index') }}" class="px-3 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs flex items-center gap-1 transition-colors">
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
                            <th class="py-3.5 px-4 sm:px-6">Judul Pengumuman</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status</th>
                            <th class="py-3.5 px-4 sm:px-6">Diterbitkan Oleh</th>
                            <th class="py-3.5 px-4 sm:px-6">Tanggal Berlaku</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @forelse($pengumumanList as $item)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 max-w-md">
                                    <a href="{{ route('admin.cms.pengumuman.edit', $item) }}" class="font-bold text-darkgreen hover:text-forest transition-colors line-clamp-1 block">
                                        {{ $item->judul }}
                                    </a>
                                    <p class="text-xs text-muted-text line-clamp-1 mt-0.5">{{ $item->summary }}</p>
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
                                    {{ $item->pembuat->username ?? 'Admin' }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-xs text-muted-text">
                                    {{ $item->formatted_date }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        @if($item->status === 'published')
                                            <a 
                                                href="{{ url('/pengumuman/' . $item->slug) }}" 
                                                target="_blank"
                                                class="p-2 rounded-lg border border-border-soft hover:bg-soft text-muted-text hover:text-forest transition-colors"
                                                title="Lihat di Portal Publik"
                                            >
                                                <x-icon name="arrow-up-right" class="w-4 h-4" />
                                            </a>
                                        @endif
                                        <a 
                                            href="{{ route('admin.cms.pengumuman.edit', $item) }}" 
                                            class="p-2 rounded-lg border border-border-soft hover:bg-soft text-darkgreen hover:text-forest transition-colors"
                                            title="Edit Pengumuman"
                                        >
                                            <x-icon name="pencil" class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('admin.cms.pengumuman.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="p-2 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer"
                                                title="Hapus Pengumuman"
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
                                    Belum ada data pengumuman yang tersedia. Klik tombol "Buat Pengumuman Baru" untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengumumanList->hasPages())
                <div class="p-4 border-t border-border-soft">
                    {{ $pengumumanList->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.admin>
