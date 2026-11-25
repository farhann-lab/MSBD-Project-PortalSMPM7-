<x-layouts.admin 
    title="Kenaikan Kelas & Kelulusan" 
    activeMenu="akademik-kenaikan"
    :breadcrumbs="[
        ['label' => 'Akademik', 'url' => route('admin.akademik.kenaikan')],
        ['label' => 'Kenaikan Kelas', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Flash Message Alerts -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2">
                    <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 shrink-0" />
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2">
                    <x-icon name="exclamation-circle" class="w-5 h-5 text-rose-600 shrink-0" />
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold">&times;</button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <x-icon name="exclamation-circle" class="w-4 h-4 text-rose-600" />
                    <span>Terjadi kesalahan validasi:</span>
                </div>
                <ul class="list-disc list-inside text-rose-700 pl-4 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Kenaikan Kelas & Penempatan Tahun Ajaran Baru (KNK)</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Pratinjau keputusan kenaikan kelas dari <code class="bg-soft px-1.5 py-0.5 rounded text-darkgreen font-mono text-xs">t_naikkelas</code> RKM (Read-Only) dan eksekusi penempatan ke Tahun Ajaran Baru via Stored Procedure / Transaksi Database.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-forest/10 text-forest text-xs font-semibold">
                    <x-icon name="academic-cap" class="w-4 h-4" />
                    <span>Target TA: {{ $taTujuan }}/{{ $taTujuan + 1 }}</span>
                </span>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Rombel Asal Terpilih</span>
                <div class="text-lg sm:text-xl font-bold text-darkgreen mt-1 truncate">
                    {{ $selectedKelasAsal ? $selectedKelasAsal->nama : 'Semua Rombel' }}
                </div>
                <span class="text-xs text-muted-text mt-0.5 block">Periode Evaluasi: TASM {{ $selectedTasm }}</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted-text">Total Siswa Terdaftar</span>
                <div class="text-xl font-bold text-darkgreen mt-1">{{ $totalSiswa }} Siswa</div>
                <span class="text-xs text-emerald-600 mt-0.5 block font-medium">T.A {{ $taAsal }}/{{ $taAsal + 1 }}</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Memenuhi Syarat Naik</span>
                <div class="text-xl font-bold text-emerald-600 mt-1">{{ $totalNaik }} Siswa</div>
                <span class="text-xs text-emerald-700/80 mt-0.5 block">{{ $totalSiap }} Siap Ditempatkan, {{ $totalSudah }} Sudah</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-700">Lulus / Perlu Tinjauan</span>
                <div class="text-xl font-bold text-amber-600 mt-1">
                    {{ $totalLulus }} Lulus / {{ $totalEvaluasi }} Tinjau
                </div>
                <span class="text-xs text-amber-700/80 mt-0.5 block">Kelas 9 Lulus & Status Belum Terisi</span>
            </div>
        </div>

        <!-- Filter & Konfigurasi Migrasi -->
        <form method="GET" action="{{ route('admin.akademik.kenaikan') }}" class="p-6 rounded-3xl bg-soft/60 border border-border-soft shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-darkgreen uppercase tracking-wider flex items-center gap-2">
                    <x-icon name="adjustments-horizontal" class="w-4 h-4 text-forest" />
                    <span>Filter & Konfigurasi Rombel Tujuan</span>
                </h3>
                <span class="text-xs text-muted-text">Pilih rombel untuk mempersempit pratinjau</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- 1. Periode Evaluasi -->
                <div>
                    <label class="block text-xs font-semibold text-darkgreen mb-1.5">1. Periode Asal (Semester Genap)</label>
                    <select name="tasm" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        @foreach($tasmGenapList as $tasm)
                            @php
                                $th = (int) substr($tasm, 0, 4);
                            @endphp
                            <option value="{{ $tasm }}" {{ $selectedTasm === $tasm ? 'selected' : '' }}>
                                T.A {{ $th }}/{{ $th + 1 }} Genap ({{ $tasm }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Rombel Asal -->
                <div>
                    <label class="block text-xs font-semibold text-darkgreen mb-1.5">2. Pilih Rombel Asal</label>
                    <select name="kelas_asal_id" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="">-- Tampilkan Semua Rombel --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $selectedKelasAsalId == $k->id ? 'selected' : '' }}>
                                [Tingkat {{ $k->tingkat }}] {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Rombel Tujuan Massal -->
                <div>
                    <label class="block text-xs font-semibold text-darkgreen mb-1.5">3. Alokasi Rombel Tujuan Massal</label>
                    <select id="select-rombel-massal" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="">-- Terapkan Massal ke Siswa Terpilih --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">
                                [Tingkat {{ $k->tingkat }}] {{ $k->nama }} (TA {{ $taTujuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Filter Status -->
                <div>
                    <label class="block text-xs font-semibold text-darkgreen mb-1.5">4. Filter Status Siswa</label>
                    <select name="filter_status" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        <option value="semua" {{ $filterStatus === 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="naik" {{ $filterStatus === 'naik' ? 'selected' : '' }}>Hanya Naik Kelas</option>
                        <option value="lulus" {{ $filterStatus === 'lulus' ? 'selected' : '' }}>Hanya Kelas 9 Lulus</option>
                        <option value="evaluasi" {{ $filterStatus === 'evaluasi' ? 'selected' : '' }}>Tinggal / Belum Ditetapkan</option>
                    </select>
                </div>
            </div>
        </form>

        <!-- Form Eksekusi Kenaikan (HANYA DIEKSEKUSI SETELAH KONFIRMASI ADMIN) -->
        <form id="formEksekusiKenaikan" method="POST" action="{{ route('admin.akademik.kenaikan.eksekusi') }}">
            @csrf
            <input type="hidden" name="tasm_asal" value="{{ $selectedTasm }}">
            <input type="hidden" name="ta_tujuan" value="{{ $taTujuan }}">
            <input type="hidden" name="kelas_asal_id" value="{{ $selectedKelasAsalId }}">

            <!-- Students Selection Table -->
            <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
                <div class="p-5 border-b border-border-soft flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="check-all" class="rounded-md border-border-soft text-forest focus:ring-mint w-4 h-4 cursor-pointer">
                        <label for="check-all" class="text-xs font-bold text-darkgreen cursor-pointer">
                            Pilih Seluruh Siswa Siap Ditempatkan (<span id="eligible-count">{{ $totalSiap }}</span> Siswa)
                        </label>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-muted-text">
                        <x-icon name="information-circle" class="w-4 h-4 text-forest shrink-0" />
                        <span>Siswa kelas 9 & siswa tanpa status naik otomatis dikecualikan dari pemilihan penempatan.</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                        <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-4 sm:px-6 w-12 text-center">Pilih</th>
                                <th class="py-3.5 px-4 sm:px-6">NIS & Nama Siswa</th>
                                <th class="py-3.5 px-4 sm:px-6">Rombel Asal</th>
                                <th class="py-3.5 px-4 sm:px-6 text-center">Keputusan RKM</th>
                                <th class="py-3.5 px-4 sm:px-6">Rombel Tujuan (Dapat Disesuaikan)</th>
                                <th class="py-3.5 px-4 sm:px-6 text-center">Status Penempatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-soft">
                            @forelse($students as $s)
                                <tr class="hover:bg-soft/40 transition-colors {{ $s->status_type === 'evaluasi' ? 'bg-amber-50/20' : ($s->status_type === 'lulus' ? 'bg-sky-50/20' : '') }}">
                                    <!-- 1. Checkbox -->
                                    <td class="py-3.5 px-4 sm:px-6 text-center">
                                        @if($s->is_eligible && !$s->is_placed)
                                            <input type="checkbox" 
                                                   name="siswa_ids[]" 
                                                   value="{{ $s->id_siswa }}" 
                                                   class="student-checkbox rounded-md border-border-soft text-forest focus:ring-mint w-4 h-4 cursor-pointer"
                                                   checked>
                                        @else
                                            <input type="checkbox" 
                                                   class="rounded-md border-border-soft text-gray-300 w-4 h-4 cursor-not-allowed bg-gray-100" 
                                                   disabled>
                                        @endif
                                    </td>

                                    <!-- 2. Siswa Info -->
                                    <td class="py-3.5 px-4 sm:px-6">
                                        <div class="font-bold text-darkgreen">{{ $s->nama }}</div>
                                        <div class="text-[11px] font-mono text-muted-text">
                                            NIS: {{ $s->nis ?? '-' }} @if($s->nisn) | NISN: {{ $s->nisn }} @endif
                                        </div>
                                    </td>

                                    <!-- 3. Rombel Asal -->
                                    <td class="py-3.5 px-4 sm:px-6">
                                        <span class="inline-flex items-center gap-1 font-semibold text-darkgreen">
                                            {{ $s->kelas_asal_nama }}
                                        </span>
                                        <span class="text-[11px] text-muted-text block">Tingkat {{ $s->tingkat_asal }}</span>
                                    </td>

                                    <!-- 4. Keputusan RKM (Read-Only) -->
                                    <td class="py-3.5 px-4 sm:px-6 text-center">
                                        @if($s->status_type === 'lulus')
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-sky-100 text-sky-800 border border-sky-200 inline-flex items-center gap-1">
                                                <x-icon name="academic-cap" class="w-3.5 h-3.5" />
                                                <span>{{ $s->status_label }}</span>
                                            </span>
                                        @elseif($s->status_type === 'naik')
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                                <x-icon name="arrow-trending-up" class="w-3.5 h-3.5" />
                                                <span>{{ $s->status_label }}</span>
                                            </span>
                                        @elseif($s->status_type === 'tinggal')
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800 border border-rose-200 inline-flex items-center gap-1">
                                                <x-icon name="arrow-path" class="w-3.5 h-3.5" />
                                                <span>{{ $s->status_label }}</span>
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200 inline-flex items-center gap-1" title="Keputusan kenaikan belum diisi oleh wali kelas di RKM">
                                                <x-icon name="exclamation-triangle" class="w-3.5 h-3.5" />
                                                <span>{{ $s->status_label }}</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 5. Rombel Tujuan (Dapat Disesuaikan Sebelum Konfirmasi) -->
                                    <td class="py-3.5 px-4 sm:px-6">
                                        @if($s->is_eligible)
                                            @if($s->is_placed)
                                                <div class="text-xs font-semibold text-darkgreen">
                                                    {{ $s->target_kelas_nama }}
                                                </div>
                                                <span class="text-[10px] text-muted-text">Telah terdaftar pada TA {{ $taTujuan }}</span>
                                            @else
                                                <select name="kelas_tujuan[{{ $s->id_siswa }}]" class="target-select w-full max-w-xs px-2.5 py-1.5 rounded-lg border border-border-soft bg-white text-xs text-darkgreen font-medium focus:ring-1 focus:ring-mint focus:outline-hidden">
                                                    @foreach($kelasList as $k)
                                                        @if($k->tingkat > $s->tingkat_asal)
                                                            <option value="{{ $k->id }}" {{ $s->target_kelas_id == $k->id ? 'selected' : '' }}>
                                                                {{ $k->nama }} (Tingkat {{ $k->tingkat }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            @endif
                                        @elseif($s->status_type === 'lulus')
                                            <span class="text-xs text-muted-text italic">Tidak Ditempatkan (Alumni)</span>
                                        @else
                                            <span class="text-xs text-muted-text italic">-</span>
                                        @endif
                                    </td>

                                    <!-- 6. Status Penempatan -->
                                    <td class="py-3.5 px-4 sm:px-6 text-center">
                                        @if($s->is_placed)
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200 inline-flex items-center gap-1">
                                                <x-icon name="check" class="w-3.5 h-3.5 text-emerald-600" />
                                                <span>{{ $s->placement_label }}</span>
                                            </span>
                                        @elseif($s->is_eligible)
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                                <x-icon name="clock" class="w-3.5 h-3.5" />
                                                <span>{{ $s->placement_label }}</span>
                                            </span>
                                        @else
                                            <span class="text-xs text-muted-text font-medium">{{ $s->placement_label }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-muted-text text-xs sm:text-sm">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <x-icon name="inbox" class="w-8 h-8 text-muted-text/50" />
                                            <span>Tidak ada siswa yang ditemukan untuk kriteria rombel atau status terpilih.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Konfirmasi & Eksekusi Transaksi -->
                <div class="p-6 bg-soft/50 border-t border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-3 max-w-xl">
                        <input type="checkbox" name="konfirmasi" value="1" id="konfirmasi-checkbox" class="mt-0.5 rounded-md border-border-soft text-forest focus:ring-mint w-4 h-4 cursor-pointer shrink-0" required>
                        <label for="konfirmasi-checkbox" class="text-xs text-darkgreen cursor-pointer font-medium leading-relaxed">
                            <strong class="font-bold">Konfirmasi Eksekusi Kenaikan:</strong> Saya menyatakan bahwa pemetaan rombel di atas telah diverifikasi dan siap dieksekusi ke Tahun Ajaran {{ $taTujuan }}/{{ $taTujuan + 1 }}. Tindakan ini berjalan dalam 1 transaksi ACID penuh dan tidak menghapus riwayat tahun ajaran sebelumnya.
                        </label>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <button type="submit" 
                                id="btn-eksekusi"
                                class="px-5 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <x-icon name="arrow-right-circle" class="w-4 h-4" />
                            <span>Eksekusi Kenaikan Terpilih</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <!-- Script Interaksi UI -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check-all');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const selectMassal = document.getElementById('select-rombel-massal');
            const form = document.getElementById('formEksekusiKenaikan');
            const btnEksekusi = document.getElementById('btn-eksekusi');
            const konfirmasiCheckbox = document.getElementById('konfirmasi-checkbox');

            // 1. Check All Checkboxes
            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    studentCheckboxes.forEach(cb => {
                        cb.checked = checkAll.checked;
                    });
                });
            }

            // 2. Terapkan Rombel Massal ke Siswa Terpilih
            if (selectMassal) {
                selectMassal.addEventListener('change', function () {
                    const targetId = this.value;
                    if (!targetId) return;

                    let changedCount = 0;
                    studentCheckboxes.forEach(cb => {
                        if (cb.checked) {
                            const row = cb.closest('tr');
                            const targetSelect = row.querySelector('.target-select');
                            if (targetSelect) {
                                // Cek apakah option targetId ada
                                const optionExists = Array.from(targetSelect.options).some(opt => opt.value === targetId);
                                if (optionExists) {
                                    targetSelect.value = targetId;
                                    changedCount++;
                                }
                            }
                        }
                    });

                    if (changedCount > 0) {
                        alert(`Berhasil memperbarui rombel tujuan untuk ${changedCount} siswa terpilih.`);
                    }
                    this.value = '';
                });
            }

            // 3. Konfirmasi sebelum submit
            if (form) {
                form.addEventListener('submit', function (e) {
                    const checkedCount = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
                    if (checkedCount === 0) {
                        e.preventDefault();
                        alert('Pilih setidaknya satu siswa untuk ditempatkan.');
                        return;
                    }

                    if (!konfirmasiCheckbox.checked) {
                        e.preventDefault();
                        alert('Silakan centang kotak konfirmasi sebelum melakukan eksekusi kenaikan.');
                        konfirmasiCheckbox.focus();
                        return;
                    }

                    const confirmAction = confirm(`Apakah Anda yakin ingin menempatkan ${checkedCount} siswa ke rombel tujuan pada Tahun Ajaran Baru?\n\nOperasi ini akan dicatat ke dalam basis data secara permanen.`);
                    if (!confirmAction) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</x-layouts.admin>
