<x-layouts.siswa 
    title="Absensi Saya" 
    activeMenu="absensi-siswa"
    :breadcrumbs="[
        ['label' => 'Siswa', 'url' => route('dashboard.siswa')],
        ['label' => 'Absensi Saya', 'url' => route('siswa.absensi')],
    ]"
    :kelasSiswa="'Kelas ' . $kelasNama"
>
    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest text-white text-xs font-bold uppercase tracking-wider">
                        Rekapitulasi Kehadiran Pribadi (SSW-5, SSW-6)
                    </span>
                    <span class="text-xs text-muted-text">&bull; Semester {{ $selectedTasm }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Catatan Kehadiran &amp; Presensi Saya
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Rekaman kehadiran per sesi pertemuan dari seluruh mata pelajaran yang Anda ikuti di Kelas {{ $kelasNama }}.
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-darkgreen text-xs font-bold flex items-center gap-2 transition-colors cursor-pointer">
                    <x-icon name="printer" class="w-4 h-4 text-forest" />
                    <span>Cetak Lembar Presensi</span>
                </button>
            </div>
        </div>

        <!-- Attendance Metrics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs col-span-2 lg:col-span-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Persentase Hadir</span>
                <div class="text-2xl font-black text-darkgreen mt-1">{{ $persentaseKehadiran }}%</div>
                <span class="text-[11px] text-muted-text mt-0.5 block">Total: {{ $totalPertemuan }} Pertemuan</span>
            </div>

            <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Hadir (H)</span>
                <div class="text-2xl font-black text-emerald-700 mt-1">{{ $totalHadir }}</div>
                <span class="text-[11px] text-emerald-700/80 mt-0.5 block">Mengikuti pelajaran</span>
            </div>

            <div class="p-5 rounded-2xl bg-blue-50/70 border border-blue-200 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-800">Sakit (S)</span>
                <div class="text-2xl font-black text-blue-700 mt-1">{{ $totalSakit }}</div>
                <span class="text-[11px] text-blue-700/80 mt-0.5 block">Surat izin sakit</span>
            </div>

            <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Izin (I)</span>
                <div class="text-2xl font-black text-amber-700 mt-1">{{ $totalIzin }}</div>
                <span class="text-[11px] text-amber-700/80 mt-0.5 block">Izin keperluan</span>
            </div>

            <div class="p-5 rounded-2xl bg-rose-50/70 border border-rose-200 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-800">Alpa (A)</span>
                <div class="text-2xl font-black text-rose-700 mt-1">{{ $totalAlpa }}</div>
                <span class="text-[11px] text-rose-700/80 mt-0.5 block">Tanpa keterangan</span>
            </div>
        </div>

        <!-- Tabel Catatan Presensi Siswa -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="bg-soft/75 px-6 py-4 border-b border-border-soft flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                    <h3 class="font-bold text-darkgreen text-sm sm:text-base">
                        Riwayat Pertemuan &amp; Presensi Harian
                    </h3>
                </div>
                <span class="text-xs text-muted-text font-medium">{{ $riwayatAbsensi->count() }} Pertemuan Tercatat</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-white text-muted-text font-semibold uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3 px-4 sm:px-6 w-16">No</th>
                            <th class="py-3 px-4 sm:px-6 w-32">Tanggal & Sesi</th>
                            <th class="py-3 px-4 sm:px-6">Mata Pelajaran</th>
                            <th class="py-3 px-4 sm:px-6">Guru Pengampu</th>
                            <th class="py-3 px-4 sm:px-6 w-32 text-center">Status</th>
                            <th class="py-3 px-4 sm:px-6">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @forelse($riwayatAbsensi as $idx => $item)
                            @php
                                $pt = $item->pertemuan;
                                $jdw = $pt->jadwal ?? null;
                                $gm = $jdw->guruMapel ?? null;
                                
                                $badgeClass = match($item->status) {
                                    'H' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'S' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'I' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'A' => 'bg-rose-100 text-rose-800 border-rose-200',
                                    default => 'bg-soft text-muted-text',
                                };
                                
                                $statusLabel = match($item->status) {
                                    'H' => 'Hadir',
                                    'S' => 'Sakit',
                                    'I' => 'Izin',
                                    'A' => 'Alpa',
                                    default => '-',
                                };
                            @endphp
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-bold text-forest">
                                    {{ $idx + 1 }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-mono text-xs font-semibold text-darkgreen">
                                        {{ \Carbon\Carbon::parse($pt->tanggal)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[11px] text-muted-text">Pertemuan Ke-{{ $pt->sesi_ke }}</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-darkgreen">
                                        {{ $gm->mapel->nama ?? '-' }}
                                    </div>
                                    <div class="text-[11px] text-muted-text">
                                        {{ $jdw ? substr($jdw->jam_mulai, 0, 5) . ' - ' . substr($jdw->jam_selesai, 0, 5) : '-' }}
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text font-medium">
                                    {{ $gm->guru->nama ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text text-xs">
                                    {{ $item->keterangan ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center text-xs text-muted-text italic">
                                    Belum ada rekaman kehadiran presensi pada semester ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.siswa>
