<x-layouts.admin 
    title="Manajemen Kelas & Rombel" 
    activeMenu="master-kelas"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.kelas.index')],
        ['label' => 'Manajemen Kelas', 'url' => ''],
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

        @if($errors->any())
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
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen Kelas, Penempatan & Wali Kelas</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Atur rombongan belajar (m_kelas), penempatan siswa (t_kelas_siswa), dan penetapan wali kelas (t_walikelas).
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                @if($tab === 'rombel')
                    <button type="button" onclick="openModalTambahKelas()" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                        <x-icon name="building-office" class="w-4 h-4" />
                        <span>Tambah Rombel Kelas</span>
                    </button>
                @elseif($tab === 'walikelas')
                    <button type="button" onclick="openModalSalinWali()" class="px-3.5 py-2.5 rounded-xl bg-soft border border-border-soft hover:bg-forest/10 text-darkgreen text-xs sm:text-sm font-bold flex items-center gap-2 transition-colors">
                        <x-icon name="document-duplicate" class="w-4 h-4 text-forest" />
                        <span>Salin dari Periode Lalu</span>
                    </button>
                    <button type="button" onclick="openModalTetapkanWali()" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                        <x-icon name="identification" class="w-4 h-4" />
                        <span>Tetapkan Wali Kelas</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Operational Note Alert (DESIGN.md 11.3) -->
        <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-amber-900 text-xs flex items-start gap-3">
            <x-icon name="info" class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" />
            <div>
                <span class="font-bold">Ketentuan Siklus Akademik:</span>
                <span>Penempatan siswa berlaku per <strong>Tahun Ajaran (TA)</strong> penuh, sedangkan penetapan wali kelas dan penugasan guru berlaku per <strong>Semester (TASM)</strong>.</span>
            </div>
        </div>

        <!-- 3 Tab Submenu Sesuai DESIGN.md Section 11.3 -->
        <div class="flex border-b border-border-soft gap-6 text-xs sm:text-sm font-semibold">
            <a href="{{ route('admin.master.kelas.index', ['tab' => 'rombel']) }}" 
               class="pb-3 border-b-2 flex items-center gap-2 transition-colors {{ $tab === 'rombel' ? 'border-forest text-forest font-bold' : 'border-transparent text-muted-text hover:text-darkgreen' }}">
                <x-icon name="building-office" class="w-4 h-4" />
                <span>Data Rombel Kelas</span>
            </a>
            <a href="{{ route('admin.master.kelas.index', ['tab' => 'penempatan', 'ta' => $selectedTa]) }}" 
               class="pb-3 border-b-2 flex items-center gap-2 transition-colors {{ $tab === 'penempatan' ? 'border-forest text-forest font-bold' : 'border-transparent text-muted-text hover:text-darkgreen' }}">
                <x-icon name="user-group" class="w-4 h-4" />
                <span>Penempatan Siswa</span>
            </a>
            <a href="{{ route('admin.master.kelas.index', ['tab' => 'walikelas', 'tasm' => $selectedTasm]) }}" 
               class="pb-3 border-b-2 flex items-center gap-2 transition-colors {{ $tab === 'walikelas' ? 'border-forest text-forest font-bold' : 'border-transparent text-muted-text hover:text-darkgreen' }}">
                <x-icon name="identification" class="w-4 h-4" />
                <span>Penetapan Wali Kelas</span>
            </a>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAB 1: DATA ROMBEL KELAS --}}
        {{-- ========================================================================= --}}
        @if($tab === 'rombel')
            <!-- Filter & Search Bar -->
            <div class="p-4 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.master.kelas.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <input type="hidden" name="tab" value="rombel">
                    <div class="relative w-full md:w-64">
                        <input type="text" name="q_kelas" value="{{ $qKelas }}" placeholder="Cari nama rombel..." 
                               class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                        <x-icon name="search" class="w-4 h-4 text-muted-text absolute left-3 top-2.5" />
                    </div>

                    <select name="tingkat" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="">Semua Tingkat</option>
                        <option value="7" {{ $tingkatFilter == '7' ? 'selected' : '' }}>Tingkat 7 (Kelas VII)</option>
                        <option value="8" {{ $tingkatFilter == '8' ? 'selected' : '' }}>Tingkat 8 (Kelas VIII)</option>
                        <option value="9" {{ $tingkatFilter == '9' ? 'selected' : '' }}>Tingkat 9 (Kelas IX)</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-semibold">
                        Filter
                    </button>
                    @if(!empty($qKelas) || !empty($tingkatFilter))
                        <a href="{{ route('admin.master.kelas.index', ['tab' => 'rombel']) }}" class="text-xs text-muted-text hover:text-rose-600 underline">
                            Reset
                        </a>
                    @endif
                </form>

                <div class="text-xs text-muted-text self-end md:self-center">
                    Total: <strong class="text-darkgreen">{{ $kelasList->count() }}</strong> Rombongan Belajar
                </div>
            </div>

            <!-- Classes Grid Cards -->
            @if($kelasList->isEmpty())
                <div class="p-12 text-center rounded-3xl bg-white border border-border-soft shadow-xs space-y-2">
                    <p class="text-muted-text text-sm">Tidak ada rombel kelas yang ditemukan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($kelasList as $c)
                        @php
                            $wali = $c->waliKelas->first();
                        @endphp
                        <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs hover:border-forest/40 transition-all flex flex-col justify-between space-y-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-forest text-white font-extrabold text-base flex items-center justify-center shadow-xs">
                                        {{ $c->tingkat ?? '?' }}
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-darkgreen block">{{ $c->nama }}</span>
                                        <span class="text-xs text-muted-text">Tingkat {{ $c->tingkat ?? '-' }}</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Aktif
                                </span>
                            </div>

                            <div class="space-y-2 text-xs pt-2 border-t border-border-soft">
                                <div class="flex items-center justify-between text-muted-text">
                                    <span>Wali Kelas ({{ $tasmAktif }}):</span>
                                    <span class="font-bold text-darkgreen truncate max-w-[170px]" title="{{ $wali?->guru?->nama ?? 'Belum Ditetapkan' }}">
                                        {{ $wali?->guru?->nama ?? 'Belum Ditetapkan' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-muted-text">
                                    <span>Anggota Rombel ({{ $taAktif }}):</span>
                                    <span class="font-bold text-forest">{{ $c->total_siswa }} Siswa</span>
                                </div>
                            </div>

                            <div class="pt-2 flex items-center gap-2">
                                <a href="{{ route('admin.master.kelas.index', ['tab' => 'penempatan', 'ta' => $taAktif, 'id_kelas' => $c->id]) }}" 
                                   class="flex-1 py-2 text-center rounded-xl bg-soft hover:bg-forest hover:text-white text-xs font-semibold text-darkgreen transition-colors">
                                    Lihat Penempatan
                                </a>
                                <button type="button" 
                                        onclick="openModalEditKelas('{{ $c->id }}', '{{ addslashes($c->nama) }}', '{{ $c->tingkat }}')" 
                                        class="p-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text hover:text-darkgreen" 
                                        title="Edit Kelas">
                                    <x-icon name="pencil" class="w-4 h-4" />
                                </button>
                                <form method="POST" action="{{ route('admin.master.kelas.destroy', $c->id) }}" onsubmit="return confirm('Hapus kelas {{ $c->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl border border-border-soft hover:bg-rose-50 text-muted-text hover:text-rose-600" title="Hapus Kelas">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 2: PENEMPATAN SISWA KE KELAS (PNS-1..6) --}}
        {{-- ========================================================================= --}}
        @if($tab === 'penempatan')
            <!-- Bar Pemilihan TA dan Kelas -->
            <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
                <form method="GET" action="{{ route('admin.master.kelas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <input type="hidden" name="tab" value="penempatan">

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Tahun Ajaran (TA)</label>
                        <select name="ta" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            @foreach($tahunList as $thn)
                                @php $thnTa = substr($thn->tahun, 0, 4); @endphp
                                <option value="{{ $thnTa }}" {{ $selectedTa == $thnTa ? 'selected' : '' }}>
                                    TA {{ $thnTa }}/{{ ((int)$thnTa + 1) }} {{ $thn->aktif === 'Y' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Pilih Rombel Kelas</label>
                        <select name="id_kelas" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                                    Kelas {{ $k->nama }} (Tingkat {{ $k->tingkat ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-darkgreen mb-1.5">Pencarian Siswa</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" name="q_siswa" value="{{ $qSiswa }}" placeholder="Cari nama atau NIS siswa..." 
                                       class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                                <x-icon name="search" class="w-4 h-4 text-muted-text absolute left-3 top-2.5" />
                            </div>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-semibold">
                                Cari
                            </button>
                            @if(!empty($qSiswa))
                                <a href="{{ route('admin.master.kelas.index', ['tab' => 'penempatan', 'ta' => $selectedTa, 'id_kelas' => $selectedKelasId]) }}" class="px-3 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs text-muted-text flex items-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Two Column Layout: Siswa di Kelas Terpilih vs Siswa Belum Ditempatkan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                
                <!-- Kolom Kiri: Anggota Kelas Terpilih -->
                <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-border-soft pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-darkgreen">Anggota Kelas: {{ $selectedKelas?->nama ?? 'Pilih Kelas' }}</h3>
                            <p class="text-[11px] text-muted-text">Tahun Ajaran {{ $selectedTa }}/{{ ((int)$selectedTa + 1) }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-forest/10 text-forest border border-forest/20">
                            {{ $siswaRombel->count() }} Siswa
                        </span>
                    </div>

                    @if($siswaRombel->isEmpty())
                        <div class="p-8 text-center text-muted-text text-xs border border-dashed border-border-soft rounded-2xl">
                            Belum ada siswa yang ditempatkan pada kelas ini di TA {{ $selectedTa }}.
                        </div>
                    @else
                        <div class="overflow-x-auto max-h-[520px]">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-border-soft bg-soft text-darkgreen font-semibold sticky top-0">
                                        <th class="py-2.5 px-3">No</th>
                                        <th class="py-2.5 px-3">NIS</th>
                                        <th class="py-2.5 px-3">Nama Siswa</th>
                                        <th class="py-2.5 px-3">JK</th>
                                        <th class="py-2.5 px-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-soft">
                                    @foreach($siswaRombel as $idx => $ps)
                                        <tr class="hover:bg-soft/40 transition-colors">
                                            <td class="py-2.5 px-3 text-muted-text">{{ $idx + 1 }}</td>
                                            <td class="py-2.5 px-3 font-mono font-medium text-darkgreen">{{ $ps->siswa?->nis ?? '-' }}</td>
                                            <td class="py-2.5 px-3 font-semibold text-darkgreen">{{ $ps->siswa?->nama ?? '-' }}</td>
                                            <td class="py-2.5 px-3">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $ps->siswa?->jk === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                                    {{ $ps->siswa?->jk ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="py-2.5 px-3 text-right space-x-1">
                                                <button type="button" 
                                                        onclick="openModalPindahRombel('{{ $ps->id }}', '{{ addslashes($ps->siswa?->nama) }}', '{{ $selectedKelasId }}')" 
                                                        class="p-1.5 rounded-lg border border-border-soft hover:bg-forest/10 text-forest" title="Pindah Rombel">
                                                    <x-icon name="arrow-right-circle" class="w-4 h-4" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.master.kelas.penempatan.destroy', $ps->id) }}" class="inline" onsubmit="return confirm('Keluarkan siswa {{ $ps->siswa?->nama }} dari kelas?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg border border-border-soft hover:bg-rose-50 text-rose-600" title="Keluarkan Siswa">
                                                        <x-icon name="x-mark" class="w-4 h-4" />
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Kolom Kanan: Siswa Aktif Belum Ditempatkan di TA ini -->
                <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
                    <form method="POST" action="{{ route('admin.master.kelas.penempatan.store') }}" id="formPenempatanMassal">
                        @csrf
                        <input type="hidden" name="id_kelas" value="{{ $selectedKelasId }}">
                        <input type="hidden" name="ta" value="{{ $selectedTa }}">

                        <div class="flex items-center justify-between border-b border-border-soft pb-3">
                            <div>
                                <h3 class="text-sm font-bold text-darkgreen">Siswa Belum Ditempatkan</h3>
                                <p class="text-[11px] text-muted-text">Siswa aktif yang belum memiliki rombel di TA {{ $selectedTa }}</p>
                            </div>
                            <button type="submit" id="btnSubmitPenempatan" class="px-3.5 py-1.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs flex items-center gap-1.5 disabled:opacity-50" disabled>
                                <x-icon name="plus" class="w-3.5 h-3.5" />
                                <span>Tempatkan ke Kelas</span>
                            </button>
                        </div>

                        @if($siswaBelumDitempatkan->isEmpty())
                            <div class="p-8 text-center text-muted-text text-xs border border-dashed border-border-soft rounded-2xl mt-4">
                                Semua siswa aktif telah ditempatkan pada kelas untuk TA {{ $selectedTa }}.
                            </div>
                        @else
                            <div class="overflow-x-auto max-h-[520px] mt-4">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="border-b border-border-soft bg-soft text-darkgreen font-semibold sticky top-0">
                                            <th class="py-2.5 px-3 w-8">
                                                <input type="checkbox" id="checkAllSiswa" onchange="toggleSelectAllSiswa(this)" class="rounded text-forest focus:ring-forest" />
                                            </th>
                                            <th class="py-2.5 px-3">NIS</th>
                                            <th class="py-2.5 px-3">Nama Siswa</th>
                                            <th class="py-2.5 px-3">Diterima Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-border-soft">
                                        @foreach($siswaBelumDitempatkan as $s)
                                            <tr class="hover:bg-soft/40 transition-colors">
                                                <td class="py-2.5 px-3">
                                                    <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" onchange="updateBtnSubmitPenempatan()" class="siswa-checkbox rounded text-forest focus:ring-forest" />
                                                </td>
                                                <td class="py-2.5 px-3 font-mono font-medium text-darkgreen">{{ $s->nis ?? '-' }}</td>
                                                <td class="py-2.5 px-3 font-semibold text-darkgreen">{{ $s->nama }}</td>
                                                <td class="py-2.5 px-3 text-muted-text">{{ $s->diterima_kelas ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="pt-3">
                                {{ $siswaBelumDitempatkan->links() }}
                            </div>
                        @endif
                    </form>
                </div>

            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 3: PENETAPAN WALI KELAS (PWK-1..6) --}}
        {{-- ========================================================================= --}}
        @if($tab === 'walikelas')
            <!-- Bar Pemilihan TASM -->
            <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.master.kelas.index') }}" class="flex items-center gap-3 w-full md:w-auto">
                    <input type="hidden" name="tab" value="walikelas">
                    <label class="text-xs font-bold text-darkgreen shrink-0">Periode Semester (TASM):</label>
                    <select name="tasm" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen font-semibold">
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn->tahun }}" {{ $selectedTasm == $thn->tahun ? 'selected' : '' }}>
                                Periode {{ $thn->tahun }} - Semester {{ substr($thn->tahun, -1) == '1' ? 'Ganjil' : 'Genap' }} {{ $thn->aktif === 'Y' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="text-xs text-muted-text">
                    Terdata: <strong class="text-forest">{{ $waliKelasList->count() }}</strong> dari <strong class="text-darkgreen">{{ $kelasList->count() }}</strong> Rombel telah memiliki Wali Kelas
                </div>
            </div>

            <!-- Tabel Daftar Penetapan Wali Kelas -->
            <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-border-soft pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-darkgreen">Daftar Wali Kelas Periode {{ $selectedTasm }}</h3>
                        <p class="text-xs text-muted-text">Satu kelas hanya boleh 1 wali kelas, dan satu guru hanya boleh memegang 1 kelas.</p>
                    </div>
                </div>

                @if($waliKelasList->isEmpty())
                    <div class="p-12 text-center text-muted-text text-xs border border-dashed border-border-soft rounded-2xl">
                        Belum ada penetapan wali kelas pada periode {{ $selectedTasm }}. Klik tombol "Tetapkan Wali Kelas" atau gunakan opsi "Salin dari Periode Lalu".
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-border-soft bg-soft text-darkgreen font-semibold">
                                    <th class="py-3 px-4">No</th>
                                    <th class="py-3 px-4">Rombel Kelas</th>
                                    <th class="py-3 px-4">Tingkat</th>
                                    <th class="py-3 px-4">Nama Guru Wali Kelas</th>
                                    <th class="py-3 px-4">NIP Guru</th>
                                    <th class="py-3 px-4">Periode (TASM)</th>
                                    <th class="py-3 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-soft">
                                @foreach($waliKelasList as $idx => $wk)
                                    <tr class="hover:bg-soft/40 transition-colors">
                                        <td class="py-3 px-4 text-muted-text">{{ $idx + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-darkgreen">{{ $wk->kelas?->nama ?? '-' }}</td>
                                        <td class="py-3 px-4 text-muted-text">Tingkat {{ $wk->kelas?->tingkat ?? '-' }}</td>
                                        <td class="py-3 px-4 font-bold text-forest">{{ $wk->guru?->nama ?? '-' }}</td>
                                        <td class="py-3 px-4 font-mono text-muted-text">{{ $wk->guru?->nip ?? '-' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                {{ $wk->tasm }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <form method="POST" action="{{ route('admin.master.kelas.walikelas.destroy', $wk->id) }}" class="inline" onsubmit="return confirm('Hapus penetapan wali kelas untuk {{ $wk->kelas?->nama }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg border border-border-soft hover:bg-rose-50 text-rose-600" title="Hapus Penetapan">
                                                    <x-icon name="trash" class="w-4 h-4" />
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif

    </div>

    {{-- ========================================================================= --}}
    {{-- MODALS --}}
    {{-- ========================================================================= --}}

    <!-- Modal Tambah Kelas -->
    <div id="modalTambahKelas" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Tambah Rombongan Belajar</h3>
                <button type="button" onclick="closeModalTambahKelas()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.kelas.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Tingkat Kelas <span class="text-rose-500">*</span></label>
                    <select name="tingkat" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="">Pilih Tingkat</option>
                        <option value="7">7 (Kelas VII)</option>
                        <option value="8">8 (Kelas VIII)</option>
                        <option value="9">9 (Kelas IX)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Nama Rombel <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Contoh: VII-1 (Fullday), VIII-3 (Reguler)" 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                </div>
                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalTambahKelas()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Simpan Rombel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kelas -->
    <div id="modalEditKelas" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Edit Rombongan Belajar</h3>
                <button type="button" onclick="closeModalEditKelas()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" id="formEditKelas" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Tingkat Kelas <span class="text-rose-500">*</span></label>
                    <select name="tingkat" id="editTingkat" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="7">7 (Kelas VII)</option>
                        <option value="8">8 (Kelas VIII)</option>
                        <option value="9">9 (Kelas IX)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Nama Rombel <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" id="editNama" required 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen" />
                </div>
                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalEditKelas()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Perbarui Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tetapkan Wali Kelas -->
    <div id="modalTetapkanWali" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Tetapkan Wali Kelas</h3>
                <button type="button" onclick="closeModalTetapkanWali()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.kelas.walikelas.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="tasm" value="{{ $selectedTasm }}">

                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Periode Semester (TASM)</label>
                    <input type="text" value="{{ $selectedTasm }}" readonly 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft bg-soft text-muted-text font-mono font-bold" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Pilih Rombel Kelas <span class="text-rose-500">*</span></label>
                    <select name="id_kelas" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="">-- Pilih Rombel Kelas --</option>
                        @foreach($kelasTanpaWali as $kw)
                            <option value="{{ $kw->id }}">{{ $kw->nama }} (Tingkat {{ $kw->tingkat }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Pilih Guru Wali Kelas <span class="text-rose-500">*</span></label>
                    <select name="id_guru" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guruTersedia as $gt)
                            <option value="{{ $gt->id }}">{{ $gt->nama }} {{ $gt->nip ? "({$gt->nip})" : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalTetapkanWali()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Tetapkan Wali
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Salin Wali Kelas Antar-Periode -->
    <div id="modalSalinWali" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Salin Wali Kelas Antar-Periode</h3>
                <button type="button" onclick="closeModalSalinWali()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.kelas.walikelas.salin') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Periode Asal <span class="text-rose-500">*</span></label>
                    <select name="tasm_asal" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn->tahun }}" {{ $selectedTasm != $thn->tahun ? 'selected' : '' }}>
                                Periode {{ $thn->tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Periode Tujuan <span class="text-rose-500">*</span></label>
                    <input type="text" name="tasm_tujuan" value="{{ $selectedTasm }}" readonly 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft bg-soft text-muted-text font-mono font-bold" />
                </div>
                <div class="p-3 rounded-xl bg-amber-50 text-[11px] text-amber-800">
                    Sistem akan menyalin relasi wali kelas yang belum terisi di periode tujuan tanpa menimpa data yang sudah ada.
                </div>
                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalSalinWali()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Salin Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pindah Rombel Siswa -->
    <div id="modalPindahRombel" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft space-y-5">
            <div class="flex items-center justify-between border-b border-border-soft pb-3">
                <h3 class="text-base font-bold text-darkgreen">Pindahkan Rombel Siswa</h3>
                <button type="button" onclick="closeModalPindahRombel()" class="text-muted-text hover:text-darkgreen text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.kelas.penempatan.pindah') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="id_kelas_siswa" id="pindahIdKelasSiswa">

                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Nama Siswa</label>
                    <input type="text" id="pindahNamaSiswa" readonly 
                           class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft bg-soft text-muted-text font-bold" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen mb-1.5">Pilih Rombel Kelas Tujuan <span class="text-rose-500">*</span></label>
                    <select name="id_kelas_tujuan" id="pindahKelasTujuan" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-border-soft focus:outline-none focus:border-forest text-darkgreen">
                        @foreach($kelasList as $kt)
                            <option value="{{ $kt->id }}">{{ $kt->nama }} (Tingkat {{ $kt->tingkat }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalPindahRombel()" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-xs font-semibold text-muted-text">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs">
                        Pindahkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Pendukung Modal & Checkbox -->
    <script>
        function openModalTambahKelas() {
            document.getElementById('modalTambahKelas').classList.remove('hidden');
        }
        function closeModalTambahKelas() {
            document.getElementById('modalTambahKelas').classList.add('hidden');
        }

        function openModalEditKelas(id, nama, tingkat) {
            document.getElementById('formEditKelas').action = '{{ url("admin/master/kelas") }}/' + id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editTingkat').value = tingkat;
            document.getElementById('modalEditKelas').classList.remove('hidden');
        }
        function closeModalEditKelas() {
            document.getElementById('modalEditKelas').classList.add('hidden');
        }

        function openModalTetapkanWali() {
            document.getElementById('modalTetapkanWali').classList.remove('hidden');
        }
        function closeModalTetapkanWali() {
            document.getElementById('modalTetapkanWali').classList.add('hidden');
        }

        function openModalSalinWali() {
            document.getElementById('modalSalinWali').classList.remove('hidden');
        }
        function closeModalSalinWali() {
            document.getElementById('modalSalinWali').classList.add('hidden');
        }

        function openModalPindahRombel(idKelasSiswa, namaSiswa, currentKelasId) {
            document.getElementById('pindahIdKelasSiswa').value = idKelasSiswa;
            document.getElementById('pindahNamaSiswa').value = namaSiswa;
            const selectKelas = document.getElementById('pindahKelasTujuan');
            for (let i = 0; i < selectKelas.options.length; i++) {
                if (selectKelas.options[i].value != currentKelasId) {
                    selectKelas.selectedIndex = i;
                    break;
                }
            }
            document.getElementById('modalPindahRombel').classList.remove('hidden');
        }
        function closeModalPindahRombel() {
            document.getElementById('modalPindahRombel').classList.add('hidden');
        }

        function toggleSelectAllSiswa(master) {
            const boxes = document.querySelectorAll('.siswa-checkbox');
            boxes.forEach(cb => cb.checked = master.checked);
            updateBtnSubmitPenempatan();
        }

        function updateBtnSubmitPenempatan() {
            const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
            const btn = document.getElementById('btnSubmitPenempatan');
            if (btn) {
                btn.disabled = (checkedCount === 0);
            }
        }
    </script>
</x-layouts.admin>
