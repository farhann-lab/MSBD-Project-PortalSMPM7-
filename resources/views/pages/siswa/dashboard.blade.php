<x-layouts.siswa 
    title="Dashboard Siswa" 
    activeMenu="dashboard"
    :kelasSiswa="$kelasNama"
>
    <!-- Welcome Banner Siswa (DESIGN.md 10.4: Identitas, kelas, dan tahun ajaran aktif) -->
    <div class="mb-8 rounded-3xl bg-linear-to-r from-darkgreen to-forest p-6 sm:p-8 text-white relative overflow-hidden shadow-sm">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative z-10 max-w-3xl">   
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-200 text-xs font-semibold uppercase tracking-wider mb-3 border border-white/15">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Tahun Ajaran {{ $taAktif }} &bull; Semester {{ $tasmAktif }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">
                Assalamu'alaikum, {{ $siswa->nama ?? auth()->user()->username }}! 👋
            </h1>
            <p class="text-white/80 text-xs sm:text-sm leading-relaxed">
                Selamat datang di portal akademik Anda. Anda terdaftar aktif di <strong class="text-white">Kelas {{ $kelasNama }}</strong> 
                @if($waliKelas && $waliKelas !== '-')
                    di bawah bimbingan Wali Kelas <strong class="text-emerald-300">{{ $waliKelas }}</strong>.
                @else
                    untuk semester berjalan.
                @endif
            </p>
        </div>
    </div>

    <!-- Quick Stats Cards (DESIGN.md 10.4: Ringkasan absensi pribadi, jadwal, materi) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        
        <!-- Stat 1: Kehadiran Pribadi -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Kehadiran Saya</p>
                <p class="text-2xl font-black {{ $persentaseKehadiran >= 85 ? 'text-emerald-700' : 'text-amber-600' }} mt-1">
                    {{ $persentaseKehadiran }}%
                </p>
                <p class="text-[11px] text-muted-text font-semibold mt-1">
                    {{ $totalHadir }} Hadir / {{ $totalPertemuan }} Pertemuan
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl {{ $persentaseKehadiran >= 85 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center shrink-0">
                <x-icon name="clipboard-document-check" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 2: Jadwal Pelajaran Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Jadwal Hari Ini</p>
                <p class="text-2xl font-black text-darkgreen mt-1">
                    {{ $jadwalHariIni->count() }} <span class="text-xs font-semibold text-muted-text">Mapel</span>
                </p>
                <p class="text-[11px] text-forest font-semibold mt-1">Hari {{ $namaHariIni }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-forest/10 text-forest flex items-center justify-center shrink-0">
                <x-icon name="calendar" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 3: Materi Tersedia -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Materi Pelajaran</p>
                <p class="text-2xl font-black text-darkgreen mt-1">
                    {{ $countMateri }} <span class="text-xs font-semibold text-muted-text">Bahan Ajar</span>
                </p>
                <p class="text-[11px] text-forest font-semibold mt-1">Kelas {{ $kelasNama }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                <x-icon name="book-open" class="w-6 h-6" />
            </div>
        </div>

        <!-- Stat 4: Rekap Absensi (S/I/A) -->
        <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Catatan Absensi</p>
                <p class="text-base font-black text-darkgreen mt-2">
                    <span class="text-emerald-700">H:{{ $totalHadir }}</span> &bull; 
                    <span class="text-blue-700">S:{{ $totalSakit }}</span> &bull; 
                    <span class="text-amber-700">I:{{ $totalIzin }}</span> &bull; 
                    <span class="text-rose-700">A:{{ $totalAlpa }}</span>
                </p>
                <p class="text-[11px] text-muted-text font-semibold mt-1">Semester {{ $tasmAktif }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-soft text-darkgreen flex items-center justify-center shrink-0">
                <x-icon name="user" class="w-6 h-6 text-forest" />
            </div>
        </div>

    </div>

    <!-- Main Workspace Two-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Left 2 Cols: Jadwal Hari Ini & Jadwal Mingguan Siswa -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card: Jadwal Pelajaran Hari Ini -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-border-soft">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                            <x-icon name="clock" class="w-4 h-4" />
                        </span>
                        <h2 class="text-base font-bold text-darkgreen">Jadwal Pelajaran Hari Ini ({{ $namaHariIni }})</h2>
                    </div>
                    <span class="text-xs font-semibold text-forest">{{ $jadwalHariIni->count() }} Sesi Terjadwal</span>
                </div>

                @if($jadwalHariIni->count() > 0)
                    <div class="divide-y divide-border-soft/60 mt-2">
                        @foreach($jadwalHariIni as $jdw)
                            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <span class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen font-bold text-xs shrink-0">
                                        {{ substr($jdw->jam_mulai, 0, 5) }} - {{ substr($jdw->jam_selesai, 0, 5) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-bold text-darkgreen">
                                            {{ $jdw->guruMapel->mapel->nama ?? 'Mata Pelajaran' }}
                                        </p>
                                        <p class="text-xs text-muted-text">
                                            Guru: {{ $jdw->guruMapel->guru->nama ?? '-' }} &bull; Kode: {{ $jdw->guruMapel->mapel->kd_singkat ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-forest/10 text-forest text-[11px] font-bold self-start sm:self-center">
                                    Tatap Muka
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center">
                        <div class="w-12 h-12 rounded-xl bg-soft flex items-center justify-center mx-auto mb-2 text-muted-text">
                            <x-icon name="calendar" class="w-6 h-6 text-sage" />
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-darkgreen">Tidak Ada Jadwal Pelajaran Hari Ini</p>
                        <p class="text-xs text-muted-text mt-1">Selamat beristirahat atau belajar mandiri di hari {{ $namaHariIni }}.</p>
                    </div>
                @endif
            </div>

            <!-- Card: Jadwal Mingguan Lengkap Kelas (Mobile Tab / Accordion Friendly) -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs" x-data="{ tabHari: '{{ $namaHariIni }}' }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-border-soft gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-800 flex items-center justify-center">
                            <x-icon name="calendar" class="w-4 h-4" />
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-darkgreen">Jadwal Pelajaran Mingguan</h2>
                            <p class="text-xs text-muted-text">Rombel Kelas {{ $kelasNama }}</p>
                        </div>
                    </div>

                    <!-- Filter Hari (Pill Switcher) -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            <button 
                                type="button" 
                                @click="tabHari = '{{ $hari }}'"
                                :class="tabHari === '{{ $hari }}' ? 'bg-forest text-white shadow-xs' : 'bg-soft text-muted-text hover:text-darkgreen'"
                                class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all shrink-0 cursor-pointer"
                            >
                                {{ substr($hari, 0, 3) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                        <div x-show="tabHari === '{{ $hari }}'" class="space-y-3">
                            @php
                                $jadwalHariGroup = $jadwalMingguan->get($hari, collect());
                            @endphp

                            @if($jadwalHariGroup->count() > 0)
                                @foreach($jadwalHariGroup as $jdw)
                                    <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="px-2.5 py-1 rounded-lg bg-white border border-border-soft text-darkgreen font-bold text-xs shrink-0">
                                                {{ substr($jdw->jam_mulai, 0, 5) }} - {{ substr($jdw->jam_selesai, 0, 5) }}
                                            </span>
                                            <div class="min-w-0">
                                                <p class="text-xs sm:text-sm font-bold text-darkgreen truncate">
                                                    {{ $jdw->guruMapel->mapel->nama ?? 'Mata Pelajaran' }}
                                                </p>
                                                <p class="text-[11px] text-muted-text truncate">
                                                    Guru: {{ $jdw->guruMapel->guru->nama ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold text-muted-text shrink-0 hidden sm:inline">
                                            Ruang Kelas
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-6 text-center text-xs text-muted-text">
                                    Tidak ada mata pelajaran yang terjadwal untuk hari {{ $hari }}.
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Repositori Materi Terkini & Akses Presensi Siswa -->
        <div class="space-y-6">
            
            <!-- Card: Materi Pembelajaran Terbaru -->
            <div class="bg-white rounded-2xl border border-border-soft p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                            <x-icon name="book-open" class="w-4 h-4" />
                        </span>
                        <h2 class="text-sm font-bold text-darkgreen">Materi Belajar Terbaru</h2>
                    </div>
                    <a href="{{ route('siswa.materi') }}" class="text-xs font-bold text-forest hover:underline">
                        Lihat Semua
                    </a>
                </div>

                @if($materiList->count() > 0)
                    <div class="mt-4 space-y-3">
                        @foreach($materiList as $mtr)
                            <div class="p-3 rounded-xl bg-soft/50 border border-border-soft flex items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-8 h-8 rounded-lg {{ $mtr->jenis === 'file' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }} flex items-center justify-center shrink-0">
                                        @if($mtr->jenis === 'file')
                                            <x-icon name="document-text" class="w-4 h-4" />
                                        @else
                                            <x-icon name="arrow-top-right-on-square" class="w-4 h-4" />
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-darkgreen truncate">{{ $mtr->judul }}</p>
                                        <p class="text-[10px] text-muted-text truncate">
                                            {{ $mtr->mapel->nama ?? 'Mapel' }} &bull; {{ $mtr->guru->nama ?? 'Guru' }}
                                        </p>
                                    </div>
                                </div>

                                @if($mtr->jenis === 'file')
                                    <a href="{{ route('materi.download', $mtr->id) }}" class="px-2.5 py-1 rounded-lg bg-forest text-white text-[11px] font-bold shrink-0 hover:bg-forest/90 transition-colors">
                                        Unduh
                                    </a>
                                @else
                                    <a href="{{ $mtr->tautan_url }}" target="_blank" rel="noopener noreferrer" class="px-2.5 py-1 rounded-lg bg-soft text-darkgreen text-[11px] font-bold shrink-0 hover:bg-border-soft/60 transition-colors">
                                        Buka
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-xs text-muted-text">
                        Belum ada materi pembelajaran yang dibagikan untuk kelas Anda saat ini.
                    </div>
                @endif
            </div>

            <!-- Card: Transparansi Rekap Absensi Pribadi (DIR-8: Read-Only) -->
            <div class="bg-white rounded-2xl border border-emerald-300/80 p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-forest/5 rounded-bl-full pointer-events-none"></div>
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                        <h2 class="text-sm font-bold text-darkgreen">Rekapitulasi Kehadiran</h2>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-forest/10 text-forest text-[11px] font-bold">Pribadi</span>
                </div>

                <div class="py-4 space-y-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-muted-text">Persentase Kehadiran:</span>
                        <span class="font-bold {{ $persentaseKehadiran >= 85 ? 'text-emerald-700' : 'text-amber-600' }}">
                            {{ $persentaseKehadiran }}%
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted-text">Hadir Tepat Waktu:</span>
                        <span class="font-semibold text-emerald-700">{{ $totalHadir }} Pertemuan</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted-text">Sakit / Izin Resmi:</span>
                        <span class="font-semibold text-blue-700">{{ $totalSakit }} Sakit / {{ $totalIzin }} Izin</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted-text">Tanpa Keterangan (Alpa):</span>
                        <span class="font-bold {{ $totalAlpa > 0 ? 'text-rose-700' : 'text-muted-text' }}">
                            {{ $totalAlpa }} Pertemuan
                        </span>
                    </div>
                </div>

                <div class="pt-3 border-t border-border-soft">
                    <a href="{{ route('siswa.absensi') }}" class="w-full py-2 rounded-xl bg-forest/10 hover:bg-forest/20 text-forest text-xs font-bold transition-colors block text-center">
                        Buka Riwayat Absensi Lengkap
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-layouts.siswa>
