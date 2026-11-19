<x-layouts.guru 
    title="Input Absensi Siswa" 
    activeMenu="absensi-siswa"
    :breadcrumbs="[
        ['label' => 'Guru', 'url' => route('dashboard.guru')],
        ['label' => 'Input Absensi Siswa', 'url' => route('guru.absensi')],
    ]"
    :isWaliKelas="$isWaliKelas"
    :kelasPerwalian="$kelasPerwalian"
>
    <div class="space-y-6" x-data="{ modalBukaSesi: false }">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold uppercase tracking-wider">
                        Presensi Pembelajaran (ABS-1 s.d. ABS-8)
                    </span>
                    <span class="text-xs text-muted-text">&bull; Semester {{ $tasmAktif }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Input &amp; Kelola Absensi Peserta Didik
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Catat kehadiran siswa rombel per sesi pertemuan untuk mata pelajaran dan kelas yang Anda ampu.
                </p>
            </div>

            @if($selectedJadwal)
                <button 
                    type="button" 
                    @click="modalBukaSesi = true"
                    class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors shrink-0">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Buka Sesi Pertemuan Baru</span>
                </button>
            @endif
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5">
                <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2.5">
                <x-icon name="exclamation-circle" class="w-5 h-5 text-rose-600 shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <p class="font-bold mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter Pilih Jadwal Mengajar (ABS-1) -->
        <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('guru.absensi') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Pilih Jadwal Mengajar</label>
                    <select name="jadwal_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        @forelse($jadwalList as $jdw)
                            @php $gm = $jdw->guruMapel; @endphp
                            <option value="{{ $jdw->id }}" {{ $selectedJadwalId == $jdw->id ? 'selected' : '' }}>
                                Kelas {{ $gm->kelas->nama ?? '-' }} &bull; {{ $gm->mapel->nama ?? '-' }} ({{ $jdw->hari }}, {{ substr($jdw->jam_mulai, 0, 5) }}-{{ substr($jdw->jam_selesai, 0, 5) }})
                            </option>
                        @empty
                            <option value="">Belum ada jadwal mengajar aktif</option>
                        @endforelse
                    </select>
                </div>

                @if($daftarPertemuan->isNotEmpty())
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1">Pilih Sesi Pertemuan</label>
                        <select name="pertemuan_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                            <option value="">-- Pilih Sesi Pertemuan --</option>
                            @foreach($daftarPertemuan as $pt)
                                <option value="{{ $pt->id }}" {{ $selectedPertemuan && $selectedPertemuan->id == $pt->id ? 'selected' : '' }}>
                                    Pertemuan Ke-{{ $pt->sesi_ke }} ({{ \Carbon\Carbon::parse($pt->tanggal)->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </form>

            @if($selectedJadwal)
                <div class="text-xs text-muted-text flex items-center gap-3 self-end md:self-auto">
                    <span>Total Sesi: <strong class="text-darkgreen">{{ $daftarPertemuan->count() }}</strong></span>
                    <span>Total Siswa: <strong class="text-darkgreen">{{ $siswaRombel->count() }}</strong></span>
                </div>
            @endif
        </div>

        @if(!$selectedJadwal)
            <div class="p-8 text-center bg-white rounded-3xl border border-border-soft text-muted-text text-sm">
                Anda belum memiliki jadwal mengajar pada semester aktif. Silakan hubungi admin akademik untuk pengaturan penugasan dan jadwal pelajaran.
            </div>
        @else
            <!-- Main Content Area: Daftar Sesi & Form Pengisian Presensi -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left 1 Col: Daftar Riwayat Sesi Pertemuan (ABS-7) -->
                <div class="space-y-4">
                    <div class="bg-white rounded-3xl border border-border-soft p-5 shadow-xs space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-border-soft">
                            <h3 class="text-sm font-bold text-darkgreen">Riwayat Sesi Pertemuan</h3>
                            <span class="text-xs text-muted-text font-medium">{{ $daftarPertemuan->count() }} Pertemuan</span>
                        </div>

                        <div class="space-y-2 max-h-[500px] overflow-y-auto pr-1">
                            @forelse($daftarPertemuan as $pt)
                                @php
                                    $isCurrent = $selectedPertemuan && $selectedPertemuan->id == $pt->id;
                                    $countAbsensi = $pt->absensiSiswa->count();
                                @endphp
                                <a href="{{ route('guru.absensi', ['jadwal_id' => $selectedJadwal->id, 'pertemuan_id' => $pt->id]) }}" 
                                   class="block p-3.5 rounded-2xl border transition-all {{ $isCurrent ? 'border-forest bg-forest/5 shadow-xs' : 'border-border-soft hover:bg-soft/50' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-darkgreen">Pertemuan Ke-{{ $pt->sesi_ke }}</span>
                                        <span class="text-[11px] font-mono text-muted-text">{{ \Carbon\Carbon::parse($pt->tanggal)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-2 text-[11px]">
                                        <span class="text-muted-text truncate max-w-[150px]">{{ $pt->catatan ?: 'Pertemuan rutin' }}</span>
                                        @if($countAbsensi > 0)
                                            <span class="px-2 py-0.5 rounded-full font-semibold bg-emerald-100 text-emerald-800">
                                                Terisi ({{ $countAbsensi }})
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full font-semibold bg-rose-100 text-rose-700">
                                                Belum Diabsen
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="py-6 text-center text-xs text-muted-text italic">
                                    Belum ada sesi pertemuan yang dibuka. Klik tombol "Buka Sesi Pertemuan Baru" di atas.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right 2 Cols: Form Input / Update Presensi Rombel (ABS-4 s.d. ABS-6) -->
                <div class="lg:col-span-2 space-y-6">
                    @if(!$selectedPertemuan)
                        <div class="p-8 text-center bg-white rounded-3xl border border-border-soft space-y-3">
                            <x-icon name="clipboard-document-check" class="w-12 h-12 text-forest/40 mx-auto" />
                            <h3 class="text-base font-bold text-darkgreen">Pilih Sesi Pertemuan</h3>
                            <p class="text-xs text-muted-text max-w-md mx-auto">
                                Silakan pilih salah satu sesi pertemuan dari daftar di sebelah kiri untuk mengisi atau memperbarui data presensi siswa, atau buka sesi pertemuan baru.
                            </p>
                        </div>
                    @else
                        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                            <div class="bg-soft/75 px-6 py-4 border-b border-border-soft flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-darkgreen text-sm sm:text-base">
                                        Form Presensi: Pertemuan Ke-{{ $selectedPertemuan->sesi_ke }}
                                    </h3>
                                    <p class="text-xs text-muted-text">
                                        Tanggal: {{ \Carbon\Carbon::parse($selectedPertemuan->tanggal)->translatedFormat('l, d F Y') }} &bull; Rombel Kelas {{ $selectedJadwal->guruMapel->kelas->nama ?? '-' }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button 
                                        type="button" 
                                        onclick="setAllRadio('H')" 
                                        class="px-3 py-1.5 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-900 text-xs font-bold transition-colors">
                                        Set Semua Hadir (H)
                                    </button>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('guru.absensi.simpan', $selectedPertemuan->id) }}">
                                @csrf
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs divide-y divide-border-soft">
                                        <thead class="bg-white text-muted-text font-semibold uppercase text-[11px] tracking-wider">
                                            <tr>
                                                <th class="py-3 px-4 w-12">No</th>
                                                <th class="py-3 px-4">Nama Peserta Didik</th>
                                                <th class="py-3 px-4 w-56 text-center">Status Kehadiran</th>
                                                <th class="py-3 px-4 w-48">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-border-soft">
                                            @forelse($siswaRombel as $idx => $s)
                                                @php
                                                    $existing = $absensiMap[$s->id] ?? null;
                                                    $statusCurrent = $existing ? $existing->status : 'H';
                                                    $ketCurrent = $existing ? $existing->keterangan : '';
                                                @endphp
                                                <tr class="hover:bg-soft/30 transition-colors">
                                                    <td class="py-3 px-4 font-bold text-forest">
                                                        {{ $idx + 1 }}
                                                    </td>
                                                    <td class="py-3 px-4">
                                                        <div class="font-bold text-darkgreen">{{ $s->nama }}</div>
                                                        <div class="text-[11px] text-muted-text">NIS: {{ $s->nis }} &bull; {{ $s->jk === 'L' ? 'Laki-Laki' : 'Perempuan' }}</div>
                                                    </td>
                                                    <td class="py-3 px-4">
                                                        <div class="flex items-center justify-center gap-1.5">
                                                            <label class="cursor-pointer">
                                                                <input type="radio" name="absensi[{{ $s->id }}][status]" value="H" {{ $statusCurrent === 'H' ? 'checked' : '' }} class="sr-only peer status-radio-H">
                                                                <span class="px-2.5 py-1 rounded-lg border border-border-soft text-xs font-bold text-muted-text peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition-all inline-block">
                                                                    H
                                                                </span>
                                                            </label>

                                                            <label class="cursor-pointer">
                                                                <input type="radio" name="absensi[{{ $s->id }}][status]" value="S" {{ $statusCurrent === 'S' ? 'checked' : '' }} class="sr-only peer status-radio-S">
                                                                <span class="px-2.5 py-1 rounded-lg border border-border-soft text-xs font-bold text-muted-text peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all inline-block">
                                                                    S
                                                                </span>
                                                            </label>

                                                            <label class="cursor-pointer">
                                                                <input type="radio" name="absensi[{{ $s->id }}][status]" value="I" {{ $statusCurrent === 'I' ? 'checked' : '' }} class="sr-only peer status-radio-I">
                                                                <span class="px-2.5 py-1 rounded-lg border border-border-soft text-xs font-bold text-muted-text peer-checked:bg-amber-600 peer-checked:text-white peer-checked:border-amber-600 transition-all inline-block">
                                                                    I
                                                                </span>
                                                            </label>

                                                            <label class="cursor-pointer">
                                                                <input type="radio" name="absensi[{{ $s->id }}][status]" value="A" {{ $statusCurrent === 'A' ? 'checked' : '' }} class="sr-only peer status-radio-A">
                                                                <span class="px-2.5 py-1 rounded-lg border border-border-soft text-xs font-bold text-muted-text peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600 transition-all inline-block">
                                                                    A
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-4">
                                                        <input type="text" name="absensi[{{ $s->id }}][keterangan]" value="{{ $ketCurrent }}" placeholder="Catatan (opsional)" class="w-full px-2.5 py-1 rounded-lg border border-border-soft text-xs focus:ring-1 focus:ring-mint focus:outline-hidden">
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-6 text-center text-xs text-muted-text italic">
                                                        Belum ada siswa yang ditempatkan di rombel kelas ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if($siswaRombel->isNotEmpty())
                                    <div class="p-4 border-t border-border-soft bg-soft/30 flex items-center justify-between">
                                        <span class="text-xs text-muted-text">
                                            Status: <strong>H</strong> = Hadir, <strong>S</strong> = Sakit, <strong>I</strong> = Izin, <strong>A</strong> = Alpa
                                        </span>
                                        <button type="submit" class="px-5 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold text-xs shadow-xs transition-colors">
                                            Simpan Rekaman Presensi
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    @endif
                </div>

            </div>
        @endif

        <!-- Modal Buka Sesi Pertemuan Baru -->
        <div 
            x-show="modalBukaSesi" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                @click.away="modalBukaSesi = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div>
                        <h3 class="text-base font-bold text-darkgreen">Buka Sesi Pertemuan Baru</h3>
                        <p class="text-xs text-muted-text">Membuka sesi tatap muka untuk input absensi</p>
                    </div>
                    <button @click="modalBukaSesi = false" class="text-muted-text hover:text-darkgreen">
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                </div>

                <form method="POST" action="{{ route('guru.absensi.buka-sesi') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="jadwal_id" value="{{ $selectedJadwalId }}">

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Mata Pelajaran & Kelas</label>
                        <input type="text" readonly disabled value="{{ $selectedJadwal ? ($selectedJadwal->guruMapel->mapel->nama ?? '-') . ' (Kelas ' . ($selectedJadwal->guruMapel->kelas->nama ?? '-') . ')' : '-' }}" class="w-full px-3 py-2 rounded-xl border border-border-soft bg-soft text-xs text-darkgreen font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Tanggal Pertemuan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Topik / Catatan Pertemuan</label>
                        <textarea name="catatan" rows="2" placeholder="Contoh: Bab 3 Aljabar Linear / Ulangan Harian" class="w-full px-3 py-2 rounded-xl border border-border-soft text-xs focus:ring-2 focus:ring-mint focus:outline-hidden"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-border-soft">
                        <button type="button" @click="modalBukaSesi = false" class="px-4 py-2 rounded-xl border border-border-soft text-xs font-semibold text-muted-text hover:bg-soft transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold text-xs shadow-xs transition-colors">
                            Buka Sesi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function setAllRadio(val) {
            const radios = document.querySelectorAll('.status-radio-' + val);
            radios.forEach(r => r.checked = true);
        }
    </script>
    @endpush
</x-layouts.guru>
