<x-layouts.admin 
    title="Manajemen Kelas & Rombel" 
    activeMenu="master-kelas"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.kelas')],
        ['label' => 'Manajemen Kelas', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen Kelas, Penempatan & Wali Kelas</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Atur rombongan belajar (m_kelas), penempatan siswa (t_kelas_siswa), dan penetapan wali kelas (t_walikelas).
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="building-office" class="w-4 h-4" />
                    <span>Tambah Rombel Kelas</span>
                </button>
            </div>
        </div>

        <!-- Operational Note Alert (DESIGN.md 11.3) -->
        <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-amber-900 text-xs flex items-start gap-3">
            <x-icon name="info" class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" />
            <div>
                <span class="font-bold">Ketentuan Siklus Akademik:</span>
                <span>Penempatan siswa berlaku per <strong>Tahun Ajaran</strong> penuh, sedangkan penetapan wali kelas dan penugasan guru berlaku per <strong>Semester</strong> (Ganjil/Genap).</span>
            </div>
        </div>

        <!-- 3 Tab Submenu Sesuai DESIGN.md Section 11.3 -->
        <div class="flex border-b border-border-soft gap-4 text-xs sm:text-sm font-semibold">
            <button type="button" class="pb-3 border-b-2 border-forest text-forest font-bold flex items-center gap-2">
                <x-icon name="building-office" class="w-4 h-4" />
                <span>Data Rombel Kelas</span>
            </button>
            <button type="button" class="pb-3 border-b-2 border-transparent text-muted-text hover:text-darkgreen flex items-center gap-2">
                <x-icon name="user-group" class="w-4 h-4" />
                <span>Penempatan Siswa</span>
            </button>
            <button type="button" class="pb-3 border-b-2 border-transparent text-muted-text hover:text-darkgreen flex items-center gap-2">
                <x-icon name="identification" class="w-4 h-4" />
                <span>Penetapan Wali Kelas</span>
            </button>
        </div>

        <!-- Classes Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $classes = [
                    ['kode' => '7A', 'tingkat' => 'Tingkat VII', 'wali' => 'Faridah Hanum, S.Pd.', 'siswa' => 36, 'kapasitas' => 36],
                    ['kode' => '7B', 'tingkat' => 'Tingkat VII', 'wali' => 'Aisyah Rahmawati, S.Pd.', 'siswa' => 35, 'kapasitas' => 36],
                    ['kode' => '7C', 'tingkat' => 'Tingkat VII', 'wali' => 'Amirul Hadi, S.Pd.I.', 'siswa' => 35, 'kapasitas' => 36],
                    ['kode' => '8A', 'tingkat' => 'Tingkat VIII', 'wali' => 'Ahmad Fauzi, S.Pd.', 'siswa' => 36, 'kapasitas' => 36],
                    ['kode' => '8B', 'tingkat' => 'Tingkat VIII', 'wali' => 'Budi Santoso, S.Pd.', 'siswa' => 35, 'kapasitas' => 36],
                    ['kode' => '8C', 'tingkat' => 'Tingkat VIII', 'wali' => 'Diah Puspita Sari, S.Pd.', 'siswa' => 35, 'kapasitas' => 36],
                    ['kode' => '9A', 'tingkat' => 'Tingkat IX', 'wali' => 'Eka Setiawan, S.Pd.', 'siswa' => 36, 'kapasitas' => 36],
                    ['kode' => '9B', 'tingkat' => 'Tingkat IX', 'wali' => 'Hendra Gunawan, S.Pd.', 'siswa' => 36, 'kapasitas' => 36],
                    ['kode' => '9C', 'tingkat' => 'Tingkat IX', 'wali' => 'Irfan Maulana, S.Kom.', 'siswa' => 36, 'kapasitas' => 36],
                    ['kode' => '9D', 'tingkat' => 'Tingkat IX', 'wali' => 'Nurul Hidayah, S.Pd.', 'siswa' => 36, 'kapasitas' => 36],
                ];
            @endphp

            @foreach($classes as $c)
                <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs hover:border-forest/40 transition-all flex flex-col justify-between space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-forest text-white font-extrabold text-base flex items-center justify-center shadow-xs">
                                {{ $c['kode'] }}
                            </div>
                            <div>
                                <span class="text-xs font-bold text-darkgreen block">Kelas {{ $c['kode'] }}</span>
                                <span class="text-[11px] text-muted-text">{{ $c['tingkat'] }}</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Aktif
                        </span>
                    </div>

                    <div class="space-y-2 text-xs pt-2 border-t border-border-soft">
                        <div class="flex items-center justify-between text-muted-text">
                            <span>Wali Kelas:</span>
                            <span class="font-bold text-darkgreen">{{ $c['wali'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-muted-text">
                            <span>Jumlah Siswa:</span>
                            <span class="font-bold text-forest">{{ $c['siswa'] }} / {{ $c['kapasitas'] }} Siswa</span>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-2">
                        <a href="{{ route('admin.master.siswa') }}?kelas={{ $c['kode'] }}" class="flex-1 py-2 text-center rounded-xl bg-soft hover:bg-forest hover:text-white text-xs font-semibold text-darkgreen transition-colors">
                            Lihat Siswa Rombel
                        </a>
                        <button type="button" class="p-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text" title="Pengaturan Kelas">
                            <x-icon name="settings" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts.admin>
