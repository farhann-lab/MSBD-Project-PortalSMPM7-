<x-layouts.admin 
    title="Data Ekstrakurikuler" 
    activeMenu="master-ekskul"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.ekskul')],
        ['label' => 'Ekstrakurikuler', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Pengelolaan Ekstrakurikuler (m_ekstra & siakad_ekstra_publik)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Nama kegiatan terhubung dengan rapor RKM, sedangkan jadwal dan deskripsi publik dikelola mandiri via SIAKAD.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="trophy" class="w-4 h-4" />
                    <span>Tambah Ekstrakurikuler</span>
                </button>
            </div>
        </div>

        <!-- Master Data Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($extracurriculars as $ekskul)
                <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs hover:border-forest/40 transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="w-12 h-12 rounded-2xl bg-forest text-white flex items-center justify-center font-bold shadow-xs">
                                <x-icon name="trophy" class="w-6 h-6 text-mint" />
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                {{ $ekskul['kategori'] ?? 'Kesiswaan' }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-bold text-darkgreen">{{ $ekskul['name'] }}</h3>
                            <p class="text-xs text-muted-text line-clamp-2 mt-1 leading-relaxed">
                                {{ $ekskul['description'] }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2 pt-3 border-t border-border-soft text-xs">
                        <div class="flex items-center gap-2 text-darkgreen font-medium">
                            <x-icon name="clock" class="w-4 h-4 text-sage shrink-0" />
                            <span>{{ $ekskul['jadwal'] ?? 'Jadwal diumumkan pembina' }}</span>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-2">
                        <button type="button" class="flex-1 py-2 text-center rounded-xl bg-soft hover:bg-forest hover:text-white text-xs font-semibold text-darkgreen transition-colors">
                            Kelola Informasi Publik
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts.admin>
