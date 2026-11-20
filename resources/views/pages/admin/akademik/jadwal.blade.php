<x-layouts.admin 
    title="Jadwal Pelajaran" 
    activeMenu="akademik-jadwal"
    :breadcrumbs="[
        ['label' => 'Akademik', 'url' => route('admin.akademik.jadwal')],
        ['label' => 'Jadwal Pelajaran', 'url' => ''],
    ]"
>
    <div class="space-y-6" x-data="jadwalApp()">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen & Plotting Jadwal Pelajaran (JDW-1 s.d. JDW-9)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Kelola alokasi jadwal mengajar guru per rombel dengan proteksi otomatis pencegahan bentrok ruang kelas dan guru.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <!-- Tombol Salin Jadwal -->
                <button 
                    type="button" 
                    @click="bukaModalSalin()"
                    class="px-4 py-2.5 rounded-xl bg-white border border-border-soft hover:bg-soft text-darkgreen text-xs sm:text-sm font-semibold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="document-duplicate" class="w-4 h-4 text-forest" />
                    <span>Salin dari Periode Lain</span>
                </button>
                
                <!-- Tombol Plotting Jadwal Baru -->
                <button 
                    type="button" 
                    @click="bukaModalTambah()"
                    class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>Tambah Jadwal Baru</span>
                </button>
            </div>
        </div>

        <!-- Academic Banner Notification -->
        <div class="p-5 rounded-3xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-900 text-xs flex items-start gap-3">
            <x-icon name="shield-check" class="w-5 h-5 text-emerald-700 mt-0.5 shrink-0" />
            <div class="space-y-1">
                <span class="font-bold text-sm block">Validasi & Pencegahan Bentrok Ketat (Trigger & App Level)</span>
                <p class="text-emerald-800 leading-relaxed">
                    Sistem secara otomatis mencegah bentrok guru (mengajar di lebih dari satu kelas pada jam yang sama) maupun bentrok ruang rombel pada semester aktif.
                </p>
            </div>
        </div>

        <!-- Alert Error / Success -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                <x-icon name="exclamation-circle" class="w-5 h-5 text-rose-600 shrink-0" />
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <p class="font-bold mb-1">Terdapat kesalahan pengisian data:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter Controls Form -->
        <form method="GET" action="{{ route('admin.akademik.jadwal') }}" class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Periode / Semester</label>
                    <select name="tasm" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        @foreach ($tahunList as $th)
                            <option value="{{ $th->tahun }}" {{ $selectedTasm == $th->tahun ? 'selected' : '' }}>
                                {{ $th->nama_tahun ?? $th->tahun }} ({{ $th->tahun }}) {{ $th->is_aktif ? '★ Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Pilih Rombel / Kelas</label>
                    <select name="id_kelas" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        @forelse ($semuaKelas as $kls)
                            <option value="{{ $kls->id }}" {{ $selectedKelasId == $kls->id ? 'selected' : '' }}>
                                Kelas {{ $kls->nama }} (Tingkat {{ $kls->tingkat }})
                            </option>
                        @empty
                            <option value="">Belum ada kelas terdaftar</option>
                        @endforelse
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-muted-text mb-1.5">Filter Hari</label>
                    <select x-model="filterHari" class="px-3.5 py-2 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="all">Semua Hari (Senin - Sabtu)</option>
                        @foreach ($hariList as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 self-end md:self-auto text-xs text-muted-text">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-forest inline-block"></span>
                    <span>Total Sesi: <strong class="text-darkgreen">{{ $jadwalList->count() }}</strong></span>
                </span>
            </div>
        </form>

        <!-- Informasi Kelas Terpilih -->
        <div class="flex items-center justify-between px-2">
            <div>
                <h3 class="text-base font-bold text-darkgreen">
                    Jadwal Rombel: <span class="text-forest">{{ $selectedKelas ? $selectedKelas->nama : 'Pilih Kelas' }}</span>
                </h3>
                <p class="text-xs text-muted-text">Periode Semester {{ $selectedTasm }}</p>
            </div>
            <div class="text-xs text-muted-text">
                Menampilkan plotting mata pelajaran mingguan
            </div>
        </div>

        <!-- Schedule Cards by Day (Grid Mingguan) -->
        <div class="space-y-6">
            @foreach($hariList as $hari)
                <div 
                    x-show="filterHari === 'all' || filterHari === '{{ $hari }}'"
                    class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden transition-all duration-200">
                    
                    <div class="bg-soft/75 px-6 py-3.5 border-b border-border-soft flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                            <h3 class="font-bold text-darkgreen text-sm sm:text-base">Hari {{ $hari }}</h3>
                        </div>
                        <span class="text-xs text-muted-text font-medium">
                            {{ count($jadwalGrid[$hari]) }} Sesi Terjadwal
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                            <thead class="bg-white text-muted-text font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="py-3 px-4 sm:px-6 w-16">No</th>
                                    <th class="py-3 px-4 sm:px-6 w-44">Waktu Pelajaran</th>
                                    <th class="py-3 px-4 sm:px-6">Mata Pelajaran</th>
                                    <th class="py-3 px-4 sm:px-6">Guru Pengampu</th>
                                    <th class="py-3 px-4 sm:px-6 w-28 text-center">Status</th>
                                    <th class="py-3 px-4 sm:px-6 text-right w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-soft">
                                @forelse($jadwalGrid[$hari] as $idx => $item)
                                    @php
                                        $gm = $item->guruMapel;
                                        $mapel = $gm->mapel ?? null;
                                        $guru = $gm->guru ?? null;
                                    @endphp
                                    <tr class="hover:bg-soft/40 transition-colors">
                                        <td class="py-3.5 px-4 sm:px-6 font-bold text-forest">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 font-mono text-xs font-semibold text-darkgreen">
                                            <span class="px-2.5 py-1 bg-soft rounded-lg border border-border-soft inline-block">
                                                {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6">
                                            <div class="font-bold text-darkgreen">{{ $mapel->nama ?? 'Mapel Tidak Terdaftar' }}</div>
                                            <div class="text-[11px] text-muted-text">Kode: {{ $mapel->kd_singkat ?? '-' }}</div>
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6">
                                            <div class="font-medium text-darkgreen">{{ $guru->nama ?? 'Guru Tidak Ditemukan' }}</div>
                                            <div class="text-[11px] text-muted-text">NIP/NBM: {{ $guru->nip ?? ($guru->nbm ?? '-') }}</div>
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                                Aktif
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <!-- Edit button -->
                                                <button 
                                                    type="button" 
                                                    @click="bukaModalEdit({{ json_encode([
                                                        'id' => $item->id,
                                                        'id_guru_mapel' => $item->id_guru_mapel,
                                                        'hari' => $item->hari,
                                                        'jam_mulai' => substr($item->jam_mulai, 0, 5),
                                                        'jam_selesai' => substr($item->jam_selesai, 0, 5),
                                                        'tasm' => $item->tasm,
                                                    ]) }})"
                                                    class="p-1.5 rounded-lg hover:bg-soft text-muted-text hover:text-darkgreen transition-colors" 
                                                    title="Edit Jadwal">
                                                    <x-icon name="pencil-square" class="w-4 h-4" />
                                                </button>

                                                <!-- Delete button -->
                                                <form 
                                                    method="POST" 
                                                    action="{{ route('admin.akademik.jadwal.destroy', $item->id) }}" 
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus / menonaktifkan jadwal ini? Jika sudah ada catatan absensi, jadwal akan diarsipkan secara aman.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-50 text-muted-text hover:text-rose-600 transition-colors" title="Hapus Jadwal">
                                                        <x-icon name="trash" class="w-4 h-4" />
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 px-4 text-center text-xs text-muted-text italic">
                                            Tidak ada jadwal pelajaran pada hari {{ $hari }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ================= MODAL TAMBAH JADWAL ================= -->
        <div 
            x-show="modalTambahTerbuka" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                @click.away="modalTambahTerbuka = false" 
                class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl border border-border-soft space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div>
                        <h3 class="text-lg font-bold text-darkgreen">Plotting Jadwal Baru</h3>
                        <p class="text-xs text-muted-text">Tambahkan slot jadwal pelajaran untuk kelas terpilih</p>
                    </div>
                    <button @click="modalTambahTerbuka = false" class="text-muted-text hover:text-darkgreen">
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.akademik.jadwal.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tasm" value="{{ $selectedTasm }}">

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Kelas / Rombel</label>
                        <input type="text" readonly disabled value="Kelas {{ $selectedKelas ? $selectedKelas->nama : '-' }} (Tingkat {{ $selectedKelas ? $selectedKelas->tingkat : '-' }})" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-soft/50 text-xs text-darkgreen font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Mata Pelajaran & Guru Pengampu <span class="text-rose-500">*</span></label>
                        <select name="id_guru_mapel" required class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                            <option value="">-- Pilih Mata Pelajaran & Guru --</option>
                            @forelse($penugasanTersedia as $gm)
                                <option value="{{ $gm->id }}">
                                    {{ $gm->mapel->nama ?? 'Mapel' }} — {{ $gm->guru->nama ?? 'Guru' }}
                                </option>
                            @empty
                                <option value="" disabled>Belum ada penugasan guru mapel di kelas ini. Atur di menu Master Penugasan terlebih dahulu.</option>
                            @endforelse
                        </select>
                        <span class="text-[11px] text-muted-text mt-1 block">Hanya menampilkan guru yang ditugaskan di kelas ini pada semester terkait (PRD JDW-2).</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Hari <span class="text-rose-500">*</span></label>
                            <select name="hari" required class="w-full px-3 py-2.5 rounded-xl border border-border-soft bg-white text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                                @foreach($hariList as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_mulai" required value="07:30" class="w-full px-3 py-2.5 rounded-xl border border-border-soft bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_selesai" required value="08:50" class="w-full px-3 py-2.5 rounded-xl border border-border-soft bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>
                    </div>

                    <div class="p-3 bg-soft rounded-xl text-xs text-muted-text">
                        <span class="font-semibold text-darkgreen">Pencegahan Bentrok Otomatis:</span> Jam selesai harus lebih akhir dari jam mulai. Sistem akan menolak jika guru atau kelas sudah memiliki jadwal pada rentang jam ini.
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-soft">
                        <button type="button" @click="modalTambahTerbuka = false" class="px-4 py-2.5 rounded-xl border border-border-soft text-xs font-semibold text-muted-text hover:bg-soft transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL EDIT JADWAL ================= -->
        <div 
            x-show="modalEditTerbuka" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                @click.away="modalEditTerbuka = false" 
                class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl border border-border-soft space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div>
                        <h3 class="text-lg font-bold text-darkgreen">Edit Jadwal Pelajaran</h3>
                        <p class="text-xs text-muted-text">Sesuaikan waktu, hari, atau guru pengampu</p>
                    </div>
                    <button @click="modalEditTerbuka = false" class="text-muted-text hover:text-darkgreen">
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                </div>

                <form method="POST" :action="editFormUrl" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tasm" :value="editData.tasm">

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Mata Pelajaran & Guru Pengampu <span class="text-rose-500">*</span></label>
                        <select name="id_guru_mapel" x-model="editData.id_guru_mapel" required class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                            @foreach($penugasanTersedia as $gm)
                                <option value="{{ $gm->id }}">
                                    {{ $gm->mapel->nama ?? 'Mapel' }} — {{ $gm->guru->nama ?? 'Guru' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Hari <span class="text-rose-500">*</span></label>
                            <select name="hari" x-model="editData.hari" required class="w-full px-3 py-2.5 rounded-xl border border-border-soft bg-white text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                                @foreach($hariList as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_mulai" x-model="editData.jam_mulai" required class="w-full px-3 py-2.5 rounded-xl border border-border-soft bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_selesai" x-model="editData.jam_selesai" required class="w-full px-3 py-2.5 rounded-xl border border-border-soft bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-soft">
                        <button type="button" @click="modalEditTerbuka = false" class="px-4 py-2.5 rounded-xl border border-border-soft text-xs font-semibold text-muted-text hover:bg-soft transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                            Perbarui Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL SALIN JADWAL ================= -->
        <div 
            x-show="modalSalinTerbuka" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                @click.away="modalSalinTerbuka = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-border-soft">
                    <div>
                        <h3 class="text-lg font-bold text-darkgreen">Salin Jadwal Antar-Periode</h3>
                        <p class="text-xs text-muted-text">Salin seluruh jadwal aktif dari periode semester sebelumnya</p>
                    </div>
                    <button @click="modalSalinTerbuka = false" class="text-muted-text hover:text-darkgreen">
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.akademik.jadwal.salin') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">
                            Periode Semester Asal <span class="text-rose-500">*</span>
                        </label>

                        <select name="tasm_asal" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">

                            <option value="">-- Pilih Semester Asal --</option>

                            @foreach ($tahunList as $th)
                                @if($th->tahun != $selectedTasm)
                                    <option value="{{ $th->tahun }}"
                                        {{ old('tasm_asal') == $th->tahun ? 'selected' : '' }}>
                                        {{ $th->nama_tahun ?? $th->tahun }} ({{ $th->tahun }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1">Periode Semester Tujuan</label>
                        <input type="text" readonly disabled value="{{ $selectedTasm }}" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-soft/50 text-xs text-darkgreen font-semibold">
                        <input type="hidden" name="tasm_tujuan" value="{{ $selectedTasm }}">
                    </div>

                    <div class="p-3 bg-amber-50 rounded-xl text-xs text-amber-900 border border-amber-200">
                        <p class="font-semibold mb-1">Ketentuan Salin Jadwal (JDW-7, JDW-8):</p>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-amber-800">
                            <li>Jadwal di semester tujuan yang bentrok akan dilewati.</li>
                            <li>Jadwal hanya disalin jika penugasan guru mapel di semester tujuan sudah dibuat.</li>
                            <li>Riwayat jadwal semester asal tidak akan berubah.</li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-soft">
                        <button type="button" @click="modalSalinTerbuka = false" class="px-4 py-2.5 rounded-xl border border-border-soft text-xs font-semibold text-muted-text hover:bg-soft transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                            Jalankan Salin Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function jadwalApp() {
            return {
                filterHari: 'all',
                modalTambahTerbuka: false,
                modalEditTerbuka: false,
                modalSalinTerbuka: @json(
                    session()->has('error') ||
                    ($errors->any() && old('tasm_asal') !== null)
                ),
                editData: {
                    id: '',
                    id_guru_mapel: '',
                    hari: 'Senin',
                    jam_mulai: '',
                    jam_selesai: '',
                    tasm: '',
                },
                get editFormUrl() {
                    return '{{ url("admin/akademik/jadwal") }}/' + this.editData.id;
                },
                bukaModalTambah() {
                    this.modalTambahTerbuka = true;
                },
                bukaModalEdit(data) {
                    this.editData = { ...data };
                    this.modalEditTerbuka = true;
                },
                bukaModalSalin() {
                    this.modalSalinTerbuka = true;
                }
            }
        }
    </script>
    @endpush
</x-layouts.admin>
