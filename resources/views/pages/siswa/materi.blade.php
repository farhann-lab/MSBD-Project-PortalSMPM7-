<x-layouts.siswa 
    title="Materi Pembelajaran" 
    activeMenu="materi-siswa"
    :breadcrumbs="[
        ['label' => 'Materi Pembelajaran', 'url' => route('siswa.materi')],
    ]"
    :kelasSiswa="$kelasNama"
>
    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest text-white text-xs font-bold uppercase tracking-wider">
                        Materi Belajar Kelas {{ $kelasNama }}
                    </span>
                    <span class="text-xs text-muted-text">&bull; Semester {{ $tasmAktif }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Materi &amp; Bahan Belajar Mandiri
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Unduh modul dokumen atau buka tautan materi video yang dibagikan oleh bapak/ibu guru mata pelajaran Anda.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3.5 py-2 rounded-xl bg-soft border border-border-soft text-xs font-bold text-darkgreen">
                    {{ is_countable($materiList) ? count($materiList) : $materiList->total() }} Bahan Ajar Tersedia
                </span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('siswa.materi') }}" class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                    <x-icon name="search" class="w-4 h-4 text-sage" />
                </span>
                <input 
                    type="text" 
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari materi atau topik..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex flex-wrap items-center gap-2">
                <select name="mapel_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($mapelList as $mp)
                        @if($mp)
                            <option value="{{ $mp->id }}" {{ request('mapel_id') == $mp->id ? 'selected' : '' }}>
                                {{ $mp->nama }}
                            </option>
                        @endif
                    @endforeach
                </select>

                <select name="jenis" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Tipe</option>
                    <option value="file" {{ request('jenis') == 'file' ? 'selected' : '' }}>Berkas (Dokumen)</option>
                    <option value="tautan" {{ request('jenis') == 'tautan' ? 'selected' : '' }}>Tautan Eksternal</option>
                </select>

                @if(request()->filled('q') || request()->filled('mapel_id') || request()->filled('jenis'))
                    <a href="{{ route('siswa.materi') }}" class="px-3 py-2 rounded-xl bg-soft hover:bg-border-soft text-muted-text hover:text-darkgreen text-xs font-semibold transition-colors">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>

        <!-- Materi Cards Grid -->
        @if($materiList->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($materiList as $item)
                    <div class="bg-white rounded-3xl border border-border-soft shadow-xs p-6 flex flex-col justify-between hover:border-forest/40 transition-colors">
                        <div>
                            <div class="flex items-start justify-between gap-3 pb-3 border-b border-border-soft">
                                <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold">
                                    {{ $item->mapel->nama ?? 'Mata Pelajaran' }}
                                </span>
                                <span class="text-xs text-muted-text">
                                    {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                </span>
                            </div>

                            <div class="py-3">
                                <h2 class="text-base font-bold text-darkgreen leading-snug">
                                    {{ $item->judul }}
                                </h2>
                                <p class="text-xs text-muted-text mt-1.5 leading-relaxed">
                                    {{ $item->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border-soft flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <p class="font-bold text-darkgreen truncate">
                                    Guru: {{ $item->guru->nama ?? 'Guru Pengampu' }}
                                </p>
                                <span class="text-muted-text text-[11px]">
                                    @if($item->jenis === 'file')
                                        Berkas Dokumen ({{ $item->ukuran_formatted }})
                                    @else
                                        Tautan Eksternal
                                    @endif
                                </span>
                            </div>

                            @if($item->jenis === 'file')
                                <a href="{{ route('materi.download', $item->id) }}" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold transition-colors shrink-0 inline-flex items-center gap-1.5 cursor-pointer">
                                    <x-icon name="arrow-down-tray" class="w-4 h-4" />
                                    <span>Unduh</span>
                                </a>
                            @else
                                <a href="{{ $item->tautan_url }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl bg-soft hover:bg-border-soft/60 text-darkgreen font-bold transition-colors shrink-0 inline-flex items-center gap-1.5">
                                    <x-icon name="arrow-top-right-on-square" class="w-4 h-4 text-emerald-600" />
                                    <span>Buka Tautan</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if(method_exists($materiList, 'links'))
                <div class="mt-4">
                    {{ $materiList->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-3xl border border-border-soft p-12 text-center">
                <div class="w-16 h-16 bg-soft rounded-2xl flex items-center justify-center mx-auto mb-4 text-muted-text">
                    <x-icon name="document-text" class="w-8 h-8 text-sage" />
                </div>
                <h3 class="text-base font-bold text-darkgreen">Belum Ada Materi Pembelajaran</h3>
                <p class="text-xs sm:text-sm text-muted-text mt-1 max-w-md mx-auto">
                    Bapak/Ibu guru belum membagikan modul atau materi ajar untuk rombel kelas Anda pada semester aktif ini.
                </p>
            </div>
        @endif

    </div>
</x-layouts.siswa>
