<x-layouts.guru 
    title="Materi Pembelajaran" 
    activeMenu="materi-pembelajaran"
    :breadcrumbs="[
        ['label' => 'Akademik & Pembelajaran', 'url' => ''],
        ['label' => 'Materi Pembelajaran', 'url' => route('guru.materi')],
    ]"
    :isWaliKelas="true"
    kelasPerwalian="VIII-B"
>
    @php
        // Daftar materi pembelajaran yang dikelola oleh guru (Budi Santoso, S.Pd. - Mapel IPA Terpadu)
        // Sesuai DESIGN.md 10.2 & 11: judul, mapel, kelas tujuan, tanggal, tipe lampiran (file/tautan eksternal), unduhan/dilihat
        $materiList = [
            [
                'id' => 1,
                'judul' => 'Sistem Gerak pada Manusia & Tumbuhan',
                'mapel' => 'IPA Terpadu',
                'kelas' => 'Kelas VIII (VIII-A, VIII-B)',
                'tanggal' => '03 Nov 2026',
                'tipe' => 'file',
                'format' => 'PDF (2.4 MB)',
                'unduhan' => 64,
                'keterangan' => 'Modul ajar lengkap materi struktur tulang, sendi, otot, serta mekanisme gerak higroskopis & esionom pada tumbuhan.',
            ],
            [
                'id' => 2,
                'judul' => 'Video Eksperimen: Pembuktian Fotosintesis (Percobaan Sachs)',
                'mapel' => 'IPA Terpadu',
                'kelas' => 'Kelas VIII (VIII-A, VIII-B)',
                'tanggal' => '01 Nov 2026',
                'tipe' => 'link',
                'format' => 'Tautan Video (YouTube)',
                'unduhan' => 58,
                'keterangan' => 'Video demonstrasi praktikum uji amilum pada daun tertutup dan terbuka untuk tugas mandiri siswa di rumah.',
            ],
            [
                'id' => 3,
                'judul' => 'Klasifikasi Makhluk Hidup & Kunci Determinasi Sederhana',
                'mapel' => 'IPA Terpadu',
                'kelas' => 'Kelas VII (VII-A, VII-B)',
                'tanggal' => '28 Okt 2026',
                'tipe' => 'file',
                'format' => 'PDF (3.1 MB)',
                'unduhan' => 61,
                'keterangan' => 'Bahan tayang presentasi serta lembar kerja pengamatan 5 kingdom makhluk hidup di lingkungan sekolah.',
            ],
            [
                'id' => 4,
                'judul' => 'Besaran, Satuan, dan Pengukuran Sains Laboratorium',
                'mapel' => 'IPA Terpadu',
                'kelas' => 'Kelas VII (VII-A, VII-B)',
                'tanggal' => '20 Okt 2026',
                'tipe' => 'file',
                'format' => 'DOCX (1.8 MB)',
                'unduhan' => 59,
                'keterangan' => 'Panduan penggunaan alat ukur mikrometer sekrup dan jangka sorong sebelum praktikum laboratorium.',
            ],
            [
                'id' => 5,
                'judul' => 'Simulasi Interaktif Phet: Hukum Newton & Gaya Gravitasi',
                'mapel' => 'IPA Terpadu',
                'kelas' => 'Kelas VIII (VIII-A, VIII-B)',
                'tanggal' => '15 Okt 2026',
                'tipe' => 'link',
                'format' => 'Tautan Simulasi Web',
                'unduhan' => 52,
                'keterangan' => 'Eksperimen virtual hukum I, II, dan III Newton dengan simulasi komputer interaktif PhET Colorado.',
            ],
        ];
    @endphp

    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold uppercase tracking-wider">
                        Akademik &amp; Pembelajaran
                    </span>
                    <span class="text-xs text-muted-text">&bull; Mata Pelajaran IPA Terpadu</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Materi &amp; Bahan Ajar Pembelajaran
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Kelola modul, bahan ajar digital, presentasi, dan tautan tugas untuk kelas yang Anda ampu (VII-A, VII-B, VIII-A, VIII-B).
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors cursor-pointer">
                    <x-icon name="plus" class="w-4 h-4 text-emerald-300" />
                    <span>Unggah Materi Baru</span>
                </button>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Total Materi Dikelola</p>
                <p class="text-2xl font-black text-darkgreen mt-1">5 <span class="text-xs font-semibold text-muted-text">Materi</span></p>
                <p class="text-[11px] text-forest font-semibold mt-1">Tersedia untuk 128 siswa</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Dokumen File</p>
                <p class="text-2xl font-black text-blue-700 mt-1">3 <span class="text-xs font-semibold text-muted-text">File</span></p>
                <p class="text-[11px] text-muted-text mt-1">PDF &amp; Microsoft Word</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Tautan Eksternal</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">2 <span class="text-xs font-semibold text-muted-text">Link</span></p>
                <p class="text-[11px] text-muted-text mt-1">Video YouTube &amp; Simulasi PhET</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-border-soft shadow-xs">
                <p class="text-xs font-medium text-muted-text uppercase tracking-wider">Total Unduhan / Akses</p>
                <p class="text-2xl font-black text-forest mt-1">294 <span class="text-xs font-semibold text-muted-text">Kali</span></p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">Rata-rata 58 akses/materi</p>
            </div>

        </div>

        <!-- Filter & Search Toolbar -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                    <x-icon name="search" class="w-4 h-4 text-sage" />
                </span>
                <input 
                    type="text" 
                    placeholder="Cari judul materi pembelajaran..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2">
                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Kelas Tujuan</option>
                    <option value="vii">Kelas VII (VII-A, VII-B)</option>
                    <option value="viii">Kelas VIII (VIII-A, VIII-B)</option>
                </select>

                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Tipe Lampiran</option>
                    <option value="file">File Dokumen</option>
                    <option value="link">Tautan Eksternal</option>
                </select>
            </div>
        </div>

        <!-- Materi Cards List -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="p-5 border-b border-border-soft flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-forest"></span>
                    <h2 class="text-sm font-bold text-darkgreen">
                        Koleksi Materi Guru (Ilmu Pengetahuan Alam)
                    </h2>
                </div>
                <span class="text-xs text-muted-text">
                    Hanya siswa kelas tujuan yang dapat mengunduh materi ini sesuai PRD
                </span>
            </div>

            <div class="divide-y divide-border-soft/60">
                @foreach($materiList as $item)
                    <div class="p-5 sm:p-6 hover:bg-soft/30 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <div class="flex items-start gap-4 min-w-0">
                            <!-- Type Icon -->
                            <div class="w-12 h-12 rounded-2xl {{ $item['tipe'] === 'file' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }} flex items-center justify-center shrink-0">
                                @if($item['tipe'] === 'file')
                                    <x-icon name="document-text" class="w-6 h-6" />
                                @else
                                    <x-icon name="arrow-top-right-on-square" class="w-6 h-6" />
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold">
                                        {{ $item['kelas'] }}
                                    </span>
                                    <span class="text-xs text-muted-text">
                                        &bull; {{ $item['mapel'] }}
                                    </span>
                                    <span class="text-xs text-muted-text">
                                        &bull; Diunggah {{ $item['tanggal'] }}
                                    </span>
                                </div>

                                <h3 class="text-sm sm:text-base font-bold text-darkgreen">
                                    {{ $item['judul'] }}
                                </h3>

                                <p class="text-xs text-muted-text mt-1 leading-relaxed">
                                    {{ $item['keterangan'] }}
                                </p>

                                <div class="flex items-center gap-3 mt-2 text-[11px] text-muted-text">
                                    <span class="font-medium text-darkgreen flex items-center gap-1">
                                        <x-icon name="paper-clip" class="w-3.5 h-3.5 text-forest" />
                                        {{ $item['format'] }}
                                    </span>
                                    <span>&bull;</span>
                                    <span class="text-emerald-700 font-semibold">{{ $item['unduhan'] }} Siswa telah mengakses</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons (Aksi edit/hapus hanya untuk guru pemilik materi sesuai PRD & DESIGN.md) -->
                        <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                            @if($item['tipe'] === 'file')
                                <button type="button" class="px-3.5 py-2 rounded-xl bg-soft hover:bg-border-soft/60 text-darkgreen text-xs font-bold transition-colors inline-flex items-center gap-1.5 cursor-pointer">
                                    <x-icon name="arrow-down-tray" class="w-4 h-4 text-forest" />
                                    <span>Unduh File</span>
                                </button>
                            @else
                                <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="px-3.5 py-2 rounded-xl bg-soft hover:bg-border-soft/60 text-darkgreen text-xs font-bold transition-colors inline-flex items-center gap-1.5">
                                    <x-icon name="arrow-top-right-on-square" class="w-4 h-4 text-emerald-600" />
                                    <span>Buka Tautan</span>
                                </a>
                            @endif

                            <button type="button" class="p-2 rounded-xl hover:bg-soft text-muted-text hover:text-darkgreen transition-colors cursor-pointer" title="Edit Materi">
                                <x-icon name="pencil" class="w-4 h-4" />
                            </button>

                            <button type="button" class="p-2 rounded-xl hover:bg-red-50 text-muted-text hover:text-red-600 transition-colors cursor-pointer" title="Hapus Materi">
                                <x-icon name="trash" class="w-4 h-4" />
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="p-5 border-t border-border-soft flex items-center justify-between text-xs text-muted-text">
                <span>Menampilkan 5 materi ajar aktif yang Anda kelola</span>
                <span>Tersinkronisasi otomatis dengan portal siswa</span>
            </div>

        </div>

    </div>
</x-layouts.guru>
