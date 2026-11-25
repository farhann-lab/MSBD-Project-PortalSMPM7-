<x-layouts.admin 
    title="Pemantauan Absensi Siswa" 
    activeMenu="akademik-absensi"
    :breadcrumbs="[
        ['label' => 'Akademik', 'url' => route('admin.akademik.absensi')],
        ['label' => 'Pemantauan Absensi', 'url' => ''],
    ]"
>
    <div class="space-y-6">
<!-- diaz jelek -->
        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Monitoring & Pemantauan Presensi Siswa (PAS-1 s.d. PAS-5)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Pantau kepatuhan input absensi guru mata pelajaran, deteksi sesi pertemuan tanpa absensi, dan rekapitulasi kehadiran rombel.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <div class="px-4 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-center gap-2">
                    <x-icon name="shield-check" class="w-4 h-4 text-amber-700" />
                    <span>Mode Pemantauan (Read-Only)</span>
                </div>
            </div>
        </div>

        <!-- Notification Banner Policy (PAS-5) -->
        <div class="p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-900 text-xs flex items-start gap-3">
            <x-icon name="information-circle" class="w-5 h-5 text-sky-700 shrink-0 mt-0.5" />
            <div>
                <span class="font-bold block text-sm">Ketentuan Akses Admin (PRD PAS-5)</span>
                <p class="text-sky-800 mt-0.5 leading-relaxed">
                    Admin hanya berhak membaca dan memantau rekaman absensi siswa. Pengisian, koreksi data, atau tindak lanjut sesi yang belum diabsen sepenuhnya dilakukan oleh guru pengampu terkait demi menjaga integritas data dan log audit.
                </p>
            </div>
        </div>

        <!-- Attendance Summary Metrics (PAS-4) -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs col-span-2 lg:col-span-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Tingkat Kehadiran</span>
                <div class="text-2xl font-black text-darkgreen mt-1">{{ $persentaseHadir }}%</div>
                <span class="text-[11px] text-muted-text mt-0.5 block">Total: {{ number_format($totalCatatan) }} Siswa</span>
            </div>

            <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Hadir (H)</span>
                <div class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($totalHadir) }}</div>
                <span class="text-[11px] text-emerald-700/80 mt-0.5 block">Status kehadiran tercatat</span>
            </div>

            <div class="p-5 rounded-2xl bg-blue-50/70 border border-blue-200 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-800">Sakit (S)</span>
                <div class="text-2xl font-black text-blue-700 mt-1">{{ number_format($totalSakit) }}</div>
                <span class="text-[11px] text-blue-700/80 mt-0.5 block">Keterangan izin sakit</span>
            </div>

            <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Izin (I)</span>
                <div class="text-2xl font-black text-amber-700 mt-1">{{ number_format($totalIzin) }}</div>
                <span class="text-[11px] text-amber-700/80 mt-0.5 block">Izin keperluan keluarga</span>
            </div>

            <div class="p-5 rounded-2xl bg-rose-50/70 border border-rose-200 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-800">Alpa (A)</span>
                <div class="text-2xl font-black text-rose-700 mt-1">{{ number_format($totalAlpa) }}</div>
                <span class="text-[11px] text-rose-700/80 mt-0.5 block">Tanpa keterangan</span>
            </div>
        </div>

        <!-- Filter Controls Form (PAS-1) -->
        <form method="GET" action="{{ route('admin.akademik.absensi') }}" class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-border-soft">
                <span class="text-xs font-bold uppercase tracking-wider text-darkgreen flex items-center gap-2">
                    <x-icon name="funnel" class="w-4 h-4 text-forest" />
                    Filter Pemantauan Presensi
                </span>
                <a href="{{ route('admin.akademik.absensi') }}" class="text-xs text-forest hover:underline font-semibold">
                    Reset Filter
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <!-- Filter Periode -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Periode</label>
                    <select name="tasm" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl border border-border-soft bg-white text-xs font-medium text-darkgreen focus:ring-2 focus:ring-mint focus:outline-hidden">
                        @foreach ($tahunList as $th)
                            <option value="{{ $th->tahun }}" {{ $selectedTasm == $th->tahun ? 'selected' : '' }}>
                                {{ $th->nama_tahun ?? $th->tahun }} ({{ $th->tahun }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kelas -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Kelas / Rombel</label>
                    <select name="id_kelas" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl border border-border-soft bg-white text-xs font-medium text-darkgreen focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="">Semua Kelas</option>
                        @foreach ($semuaKelas as $kls)
                            <option value="{{ $kls->id }}" {{ $selectedKelasId == $kls->id ? 'selected' : '' }}>
                                Kelas {{ $kls->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Guru -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Guru Pengampu</label>
                    <select name="id_guru" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl border border-border-soft bg-white text-xs font-medium text-darkgreen focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="">Semua Guru</option>
                        @foreach ($semuaGuru as $guru)
                            <option value="{{ $guru->id }}" {{ $selectedGuruId == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Mapel -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Mata Pelajaran</label>
                    <select name="id_mapel" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl border border-border-soft bg-white text-xs font-medium text-darkgreen focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="">Semua Mapel</option>
                        @foreach ($semuaMapel as $mapel)
                            <option value="{{ $mapel->id }}" {{ $selectedMapelId == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rentang Tanggal Mulai -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" onchange="this.form.submit()" class="w-full px-3 py-1.5 rounded-xl border border-border-soft bg-white text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                </div>

                <!-- Rentang Tanggal Akhir -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" onchange="this.form.submit()" class="w-full px-3 py-1.5 rounded-xl border border-border-soft bg-white text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                </div>
            </div>
        </form>

        <!-- Sesi Pertemuan Yang Belum Diisi Absensi (PAS-3 / v_pertemuan_tanpa_absensi) -->
        <div class="bg-white rounded-3xl border border-rose-200/80 shadow-xs overflow-hidden">
            <div class="bg-rose-50/70 px-6 py-4 border-b border-rose-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                    <h3 class="font-bold text-rose-950 text-sm sm:text-base">
                        Peringatan: Pertemuan Belum Terabsen ({{ $pertemuanTanpaAbsensi->total() }} Sesi)
                    </h3>
                </div>
                <span class="text-xs text-rose-800 font-medium">Perlu perhatian & tindak lanjut guru pengampu</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-white text-muted-text font-semibold uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3 px-4 sm:px-6 w-16">No</th>
                            <th class="py-3 px-4 sm:px-6 w-32">Tanggal</th>
                            <th class="py-3 px-4 sm:px-6 w-24">Sesi Ke</th>
                            <th class="py-3 px-4 sm:px-6">Kelas & Mapel</th>
                            <th class="py-3 px-4 sm:px-6">Guru Pengampu</th>
                            <th class="py-3 px-4 sm:px-6 text-right">Status Input</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @forelse($pertemuanTanpaAbsensi as $idx => $pt)
                            @php
                                $jdw = $pt->jadwal;
                                $gm = $jdw->guruMapel ?? null;
                            @endphp
                            <tr class="hover:bg-rose-50/30 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-bold text-rose-700">
                                    {{ $pertemuanTanpaAbsensi->firstItem() + $idx }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-xs font-semibold text-darkgreen">
                                    {{ \Carbon\Carbon::parse($pt->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-bold text-darkgreen">
                                    Ke-{{ $pt->sesi_ke }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-darkgreen">
                                        Kelas {{ $gm->kelas->nama ?? '-' }} — {{ $gm->mapel->nama ?? '-' }}
                                    </div>
                                    <div class="text-[11px] text-muted-text">
                                        {{ $jdw ? $jdw->hari . ' (' . substr($jdw->jam_mulai, 0, 5) . ' - ' . substr($jdw->jam_selesai, 0, 5) . ')' : '-' }}
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text font-medium">
                                    {{ $gm->guru->nama ?? 'Guru Tidak Diketahui' }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                        Belum Diabsen
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 px-4 text-center text-xs text-muted-text italic">
                                    Luar biasa! Tidak ada sesi pertemuan yang terlewat tanpa absensi pada kriteria ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pertemuanTanpaAbsensi->hasPages())
                <div class="p-4 border-t border-border-soft bg-soft/30">
                    {{ $pertemuanTanpaAbsensi->links() }}
                </div>
            @endif
        </div>

        <!-- Daftar Riwayat Pertemuan & Rekap Absensi (PAS-2) -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="bg-soft/75 px-6 py-4 border-b border-border-soft flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                    <h3 class="font-bold text-darkgreen text-sm sm:text-base">
                        Rekapitulasi Presensi per Pertemuan ({{ $riwayatPertemuan->total() }} Sesi Terjadwal)
                    </h3>
                </div>
                <span class="text-xs text-muted-text font-medium">Data kehadiran siswa tersinkronisasi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-white text-muted-text font-semibold uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3 px-4 sm:px-6 w-16">No</th>
                            <th class="py-3 px-4 sm:px-6 w-32">Tanggal & Sesi</th>
                            <th class="py-3 px-4 sm:px-6">Kelas & Mapel</th>
                            <th class="py-3 px-4 sm:px-6">Guru Pengampu</th>
                            <th class="py-3 px-4 sm:px-6 w-48 text-center">Komposisi Kehadiran</th>
                            <th class="py-3 px-4 sm:px-6 text-right w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @forelse($riwayatPertemuan as $idx => $pt)
                            @php
                                $jdw = $pt->jadwal;
                                $gm = $jdw->guruMapel ?? null;
                                $absensi = $pt->absensiSiswa;
                                $h = $absensi->where('status', 'H')->count();
                                $s = $absensi->where('status', 'S')->count();
                                $i = $absensi->where('status', 'I')->count();
                                $a = $absensi->where('status', 'A')->count();
                                $total = $absensi->count();
                            @endphp
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-bold text-forest">
                                    {{ $riwayatPertemuan->firstItem() + $idx }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-mono text-xs font-semibold text-darkgreen">
                                        {{ \Carbon\Carbon::parse($pt->tanggal)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[11px] text-muted-text">Pertemuan Ke-{{ $pt->sesi_ke }}</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-darkgreen">
                                        Kelas {{ $gm->kelas->nama ?? '-' }}
                                    </div>
                                    <div class="text-[11px] text-muted-text font-medium">
                                        {{ $gm->mapel->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-medium text-darkgreen">{{ $gm->guru->nama ?? '-' }}</div>
                                    <div class="text-[11px] text-muted-text">Dicatat pada: {{ $pt->created_at ? $pt->created_at->format('H:i') : '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($total > 0)
                                        <div class="inline-flex items-center gap-1.5 text-xs font-semibold">
                                            <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800" title="Hadir">H: {{ $h }}</span>
                                            <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800" title="Sakit">S: {{ $s }}</span>
                                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800" title="Izin">I: {{ $i }}</span>
                                            <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800" title="Alpa">A: {{ $a }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-muted-text italic">Belum ada siswa terabsen</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    @if($total > 0)
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                            Selesai ({{ $total }})
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                            Draft Sesi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center text-xs text-muted-text italic">
                                    Belum ada data rekaman pertemuan untuk kriteria filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($riwayatPertemuan->hasPages())
                <div class="p-4 border-t border-border-soft bg-soft/30">
                    {{ $riwayatPertemuan->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.admin>
