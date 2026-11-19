<x-layouts.guru 
    title="Dashboard Guru" 
    activeMenu="dashboard"
    :isWaliKelas="$isWaliKelas"
    :kelasPerwalian="$kelasPerwalian ?? ''"
>
    <!-- Welcome Banner Section (DESIGN.md Section 10.2: Sapaan dan identitas guru) -->
    <div class="mb-8 rounded-3xl bg-linear-to-r from-darkgreen to-forest p-6 sm:p-8 text-white relative overflow-hidden shadow-sm">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-200 text-xs font-semibold uppercase tracking-wider mb-3 border border-white/15">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Tahun Ajaran {{ $taAktif }} &bull; Semester {{ $tasmAktif }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">
                Selamat Datang, {{ $guru->nama ?? auth()->user()->username }}! 👋
            </h1>
            <p class="text-white/80 text-xs sm:text-sm leading-relaxed">
                Portal Guru SIAKAD SMP Muhammadiyah 7 Medan. Anda mengampu <strong class="text-white">{{ $rombelCount }} Rombel</strong> 
                @if($isWaliKelas)
                    serta bertugas aktif sebagai <strong class="text-emerald-300">Wali Kelas {{ $kelasPerwalian }}</strong>.
                @else
                    pada semester berjalan ini.
                @endif
            </p>
        </div>
    </div>

    <!-- Quick Stats Cards (DESIGN.md 10.2: Kelas & Mapel diampu, Jadwal hari ini, Absensi, Materi) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        
        <!-- Stat 1: Rombel Diampu -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Kelas Diampu</p>
                <p class="text-2xl font-black text-darkgreen mt-1">{{ $rombelCount }} <span class="text-xs font-semibold text-muted-text">Rombel</span></p>
                <p class="text-[11px] text-forest font-semibold mt-1 truncate max-w-[170px]" title="{{ $rombelNames->join(', ') }}">
                    {{ $rombelNames->count() > 0 ? $rombelNames->take(3)->join(', ') . ($rombelNames->count() > 3 ? '...' : '') : 'Belum ada kelas' }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-forest/10 text-forest flex items-center justify-center shrink-0">
                <x-icon name="building-office" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 2: Jadwal Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Jadwal Hari Ini</p>
                <p class="text-2xl font-black text-darkgreen mt-1">{{ $jadwalHariIni->count() }} <span class="text-xs font-semibold text-muted-text">Pertemuan</span></p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">Hari {{ $namaHariIni }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <x-icon name="calendar" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 3: Absensi Perlu Diisi -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Absensi Perlu Diisi</p>
                <p class="text-2xl font-black {{ $countTanpaAbsensi > 0 ? 'text-amber-600' : 'text-darkgreen' }} mt-1">
                    {{ $countTanpaAbsensi }} <span class="text-xs font-semibold text-muted-text">Sesi</span>
                </p>
                <p class="text-[11px] {{ $countTanpaAbsensi > 0 ? 'text-amber-700' : 'text-forest' }} font-semibold mt-1">
                    {{ $countTanpaAbsensi > 0 ? 'Pertemuan tanpa absensi' : 'Semua sesi terisi tertib' }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl {{ $countTanpaAbsensi > 0 ? 'bg-amber-50 text-amber-600' : 'bg-forest/10 text-forest' }} flex items-center justify-center shrink-0">
                <x-icon name="clipboard-document-check" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 4: Materi Pembelajaran -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Materi Dikelola</p>
                <p class="text-2xl font-black text-darkgreen mt-1">{{ $countMateri }} <span class="text-xs font-semibold text-muted-text">Bahan</span></p>
                <p class="text-[11px] text-forest font-semibold mt-1">Modul &amp; Tautan Belajar</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-soft text-forest flex items-center justify-center shrink-0">
                <x-icon name="book-open" class="w-6 h-6" />
            </div>
        </div>

    </div>

    <!-- Main Workspace Two-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Left 2 Cols: Jadwal Mengajar Hari Ini & Materi Terakhir -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card: Jadwal Mengajar Hari Ini -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                            <x-icon name="clock" class="w-4 h-4" />
                        </span>
                        <h2 class="text-base font-bold text-darkgreen">Jadwal Mengajar Hari Ini ({{ $namaHariIni }})</h2>
                    </div>
                    <span class="text-xs font-semibold text-forest">{{ $jadwalHariIni->count() }} Sesi Terjadwal</span>
                </div>

                @if($jadwalHariIni->count() > 0)
                    <div class="divide-y divide-border-soft/60 mt-2">
                        @foreach($jadwalHariIni as $jdw)
                            @php
                                $pertemuanSudah = $pertemuanHariIniMap->has($jdw->id);
                            @endphp
                            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen font-bold text-xs shrink-0">
                                        {{ substr($jdw->jam_mulai, 0, 5) }} - {{ substr($jdw->jam_selesai, 0, 5) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-bold text-darkgreen">
                                            {{ $jdw->guruMapel->mapel->nama ?? 'Mata Pelajaran' }} &mdash; Kelas {{ $jdw->guruMapel->kelas->nama ?? '-' }}
                                        </p>
                                        <p class="text-xs text-muted-text">
                                            Kode: {{ $jdw->guruMapel->mapel->kd_singkat ?? '-' }} &bull; Semester {{ $jdw->tasm }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($pertemuanSudah)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[11px] font-bold">
                                            Sudah Dicatat
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200/60 text-[11px] font-bold">
                                            Belum Ada Sesi
                                        </span>
                                    @endif
                                    <a href="{{ route('guru.absensi') }}" class="px-3 py-1.5 rounded-lg bg-forest text-white text-xs font-semibold hover:bg-forest/90 transition-colors">
                                        Absensi
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center">
                        <div class="w-12 h-12 rounded-xl bg-soft flex items-center justify-center mx-auto mb-2 text-muted-text">
                            <x-icon name="calendar" class="w-6 h-6 text-sage" />
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-darkgreen">Tidak Ada Jadwal Mengajar Hari Ini</p>
                        <p class="text-xs text-muted-text mt-1">Anda tidak memiliki jadwal tatap muka di hari {{ $namaHariIni }}.</p>
                    </div>
                @endif
            </div>

            <!-- Card: Materi Pembelajaran Aktif -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                            <x-icon name="book-open" class="w-4 h-4" />
                        </span>
                        <h2 class="text-base font-bold text-darkgreen">Materi Pembelajaran Terakhir</h2>
                    </div>
                    <a href="{{ route('guru.materi') }}" class="text-xs font-semibold text-forest hover:underline">
                        + Kelola Materi
                    </a>
                </div>

                @if($materiTerakhir->count() > 0)
                    <div class="mt-4 space-y-3">
                        @foreach($materiTerakhir as $mtr)
                            <div class="p-3 rounded-xl bg-soft/50 border border-border-soft flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-9 h-9 rounded-lg {{ $mtr->jenis === 'file' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }} flex items-center justify-center shrink-0">
                                        @if($mtr->jenis === 'file')
                                            <x-icon name="document-text" class="w-4 h-4" />
                                        @else
                                            <x-icon name="arrow-top-right-on-square" class="w-4 h-4" />
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-darkgreen truncate">{{ $mtr->judul }}</p>
                                        <p class="text-[11px] text-muted-text">
                                            {{ $mtr->mapel->nama ?? 'Mapel' }} &bull; 
                                            {{ $mtr->jenis === 'file' ? $mtr->ukuran_formatted : 'Tautan Eksternal' }} &bull; 
                                            {{ $mtr->created_at ? $mtr->created_at->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold {{ $mtr->status === 'aktif' ? 'text-forest' : 'text-muted-text' }} shrink-0">
                                    {{ ucfirst($mtr->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-xs text-muted-text">
                        Belum ada materi pembelajaran yang diunggah. Silakan klik tombol di atas untuk menambah materi baru.
                    </div>
                @endif
            </div>

        </div>

        <!-- Right 1 Col: Panel Khusus Wali Kelas (DESIGN.md 10.3) & Tautan Cepat RKM -->
        <div class="space-y-6">
            
            @if($isWaliKelas && $perwalianData)
                <!-- Card Khusus: Perwalian Kelas (DESIGN.md 10.3, WKL-1..8) -->
                <div class="bg-white rounded-2xl border border-emerald-300/80 p-5 sm:p-6 shadow-xs relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-forest/5 rounded-bl-full pointer-events-none"></div>
                    <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                            <h2 class="text-sm font-bold text-darkgreen">Perwalian Kelas {{ $perwalianData['kelas_nama'] }}</h2>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-forest/10 text-forest text-[11px] font-bold">Wali Aktif</span>
                    </div>

                    <div class="py-4 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-muted-text">Total Peserta Didik:</span>
                            <span class="font-bold text-darkgreen">
                                {{ $perwalianData['total_siswa'] }} Siswa ({{ $perwalianData['total_laki'] }} L / {{ $perwalianData['total_perempuan'] }} P)
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-muted-text">Tingkat Kehadiran:</span>
                            <span class="font-bold {{ $perwalianData['persen_kehadiran'] >= 85 ? 'text-emerald-700' : 'text-amber-600' }}">
                                {{ $perwalianData['persen_kehadiran'] }}%
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-muted-text">Rekap Status Kehadiran:</span>
                            <span class="font-medium text-darkgreen">
                                H: {{ $perwalianData['rekap']->total_hadir ?? 0 }} | 
                                S: {{ $perwalianData['rekap']->total_sakit ?? 0 }} | 
                                I: {{ $perwalianData['rekap']->total_izin ?? 0 }} | 
                                A: {{ $perwalianData['rekap']->total_alpa ?? 0 }}
                            </span>
                        </div>
                    </div>

                    @if($perwalianData['siswa_alpa_tinggi']->count() > 0)
                        <div class="pt-3 border-t border-border-soft">
                            <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider mb-2">
                                Siswa Perlu Perhatian (Alpa Tertinggi)
                            </p>
                            <div class="space-y-1.5">
                                @foreach($perwalianData['siswa_alpa_tinggi'] as $alpa)
                                    <div class="flex items-center justify-between text-xs p-1.5 rounded-lg bg-amber-50/60 border border-amber-200/50">
                                        <span class="font-semibold text-darkgreen truncate max-w-[150px]">{{ $alpa->nama }}</span>
                                        <span class="text-amber-800 font-bold text-[11px]">{{ $alpa->jml_alpa }}x Alpa</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-border-soft flex flex-col gap-2 mt-3">
                        <a href="{{ route('guru.perwalian.siswa') }}" class="w-full py-2 rounded-xl bg-forest/10 hover:bg-forest/20 text-forest text-xs font-bold transition-colors text-center">
                            Lihat Daftar Siswa {{ $perwalianData['kelas_nama'] }}
                        </a>
                        <a href="{{ route('guru.perwalian.absensi') }}" class="w-full py-2 rounded-xl bg-soft hover:bg-border-soft/60 text-darkgreen text-xs font-semibold transition-colors text-center">
                            Pemantauan Absensi Kelas
                        </a>
                    </div>
                </div>
            @endif

            <!-- Card: Tautan Cepat RKM (DESIGN.md 10.2 & PRD RKM-1, RKM-2) -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                        <x-icon name="arrow-top-right-on-square" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-darkgreen">Aplikasi Nilai RKM</h3>
                        <p class="text-[11px] text-muted-text">Integrasi Pengolahan Raport</p>
                    </div>
                </div>
                <p class="text-xs text-muted-text leading-relaxed mb-4">
                    Sistem Rencana Kerja &amp; Manajemen Nilai Raport Kurikulum Merdeka terhubung untuk penginputan nilai formatif &amp; sumatif.
                </p>
                <a 
                    href="http://localhost:8080/rkm" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold transition-all shadow-xs"
                >
                    <span>Masuk ke Aplikasi RKM</span>
                    <x-icon name="arrow-top-right-on-square" class="w-4 h-4" />
                </a>
            </div>

        </div>

    </div>
</x-layouts.guru>
