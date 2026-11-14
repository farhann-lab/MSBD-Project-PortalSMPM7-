<x-layouts.admin 
    title="Pratinjau Impor Siswa Excel" 
    activeMenu="master-siswa"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.siswa.index')],
        ['label' => 'Data Siswa', 'url' => route('admin.master.siswa.index')],
        ['label' => 'Pratinjau Impor', 'url' => ''],
    ]"
>
    <div class="space-y-6 max-w-6xl">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-forest text-xs font-semibold border border-emerald-200 mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Tahap 3: Verifikasi Staging Guard</span>
                </div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Hasil Validasi File: {{ $impor->nama_file }}</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-0.5">
                    Data dianalisis oleh prosedur basis data <code class="font-mono text-forest">sp_impor_siswa_validasi</code>. Data belum dimasukkan ke tabel utama <code class="font-mono text-forest">m_siswa</code>.
                </p>
            </div>
            
            <div class="flex items-center gap-2.5 shrink-0">
                <form action="{{ route('admin.master.siswa.impor.cancel', $impor) }}" method="POST" onsubmit="return confirm('Batalkan sesi impor ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-rose-600 text-xs sm:text-sm font-semibold transition-colors">
                        Batalkan Impor
                    </button>
                </form>

                @if($validRows->count() > 0)
                    <form action="{{ route('admin.master.siswa.impor.commit', $impor) }}" method="POST" onsubmit="return confirm('Terapkan impor sekarang? {{ $validRows->count() }} baris valid akan dimasukkan ke tabel m_siswa dalam satu transaksi.')">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                            <x-icon name="check-circle" class="w-4 h-4" />
                            <span>Terapkan Impor ({{ $validRows->count() }} Baris Valid)</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- 3 Summary Metrics Card (DESIGN.md 11.2) -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs">
                <span class="text-xs font-semibold text-muted-text uppercase tracking-wider block">Total Baris File</span>
                <span class="text-2xl font-extrabold text-darkgreen mt-1 block">{{ $barisList->count() }}</span>
                <span class="text-[11px] text-muted-text mt-0.5 block">Seluruh data yang terbaca</span>
            </div>

            <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Lolos Valid (Siap Simpan)</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <span class="text-2xl font-extrabold text-emerald-700 mt-1 block">{{ $validRows->count() }}</span>
                <span class="text-[11px] text-emerald-600 mt-0.5 block">NIS unik & field wajib lengkap</span>
            </div>

            <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-800 uppercase tracking-wider block">Duplikat (Dilewati)</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                </div>
                <span class="text-2xl font-extrabold text-amber-700 mt-1 block">{{ $duplikatRows->count() }}</span>
                <span class="text-[11px] text-amber-600 mt-0.5 block">NIS sudah ada di sistem / file</span>
            </div>

            <div class="p-5 rounded-2xl bg-rose-50/60 border border-rose-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-rose-800 uppercase tracking-wider block">Error / Tidak Lengkap</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                </div>
                <span class="text-2xl font-extrabold text-rose-700 mt-1 block">{{ $errorRows->count() }}</span>
                <span class="text-[11px] text-rose-600 mt-0.5 block">NIS atau Nama kosong</span>
            </div>
        </div>

        <!-- Detail Tabel Data Staging -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="p-5 border-b border-border-soft flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-darkgreen">Rincian Baris Data Staging</h3>
                    <p class="text-xs text-muted-text">Tinjau daftar baris yang valid maupun baris bermasalah di bawah ini.</p>
                </div>
                <div class="text-xs text-muted-text">
                    Transaksi ACID: Hanya baris valid yang diproses saat tombol terapkan ditekan.
                </div>
            </div>

            <div class="overflow-x-auto max-h-[500px]">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/80 text-darkgreen font-bold text-xs uppercase tracking-wider sticky top-0 z-10 backdrop-blur-xs">
                        <tr>
                            <th class="py-3 px-4">Baris</th>
                            <th class="py-3 px-4">NIS</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4">JK</th>
                            <th class="py-3 px-4">Kelas & Asal</th>
                            <th class="py-3 px-4 text-center">Status Validasi</th>
                            <th class="py-3 px-4">Keterangan / Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @foreach($barisList as $b)
                            <tr class="hover:bg-soft/40 transition-colors {{ $b->status_validasi === 'error' ? 'bg-rose-50/30' : ($b->status_validasi === 'duplikat' ? 'bg-amber-50/30' : '') }}">
                                <td class="py-3 px-4 font-mono text-xs text-muted-text">#{{ $b->nomor_baris }}</td>
                                <td class="py-3 px-4 font-mono font-bold">{{ $b->nis ?: '-' }}</td>
                                <td class="py-3 px-4 font-bold">{{ $b->nama ?: '-' }}</td>
                                <td class="py-3 px-4">{{ $b->jk }}</td>
                                <td class="py-3 px-4 text-xs text-muted-text">
                                    Kelas {{ $b->diterima_kelas ?: '-' }} &bull; {{ $b->sek_asal ?: '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($b->status_validasi === 'valid')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Valid</span>
                                        </span>
                                    @elseif($b->status_validasi === 'duplikat')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Duplikat</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Error</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    @if($b->pesan_error)
                                        <span class="text-rose-600 font-medium">{{ $b->pesan_error }}</span>
                                    @else
                                        <span class="text-emerald-600">Siap dimasukkan</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Action Confirmation -->
            <div class="p-4 sm:px-6 border-t border-border-soft bg-soft/30 flex items-center justify-between text-xs">
                <span class="text-muted-text">
                    Sesuai PRD SIS-8: Data duplikat tidak akan menimpa data siswa lama secara otomatis.
                </span>
                <span class="font-bold text-darkgreen">
                    Total {{ $validRows->count() }} dari {{ $barisList->count() }} baris siap diimpor.
                </span>
            </div>
        </div>

    </div>
</x-layouts.admin>
