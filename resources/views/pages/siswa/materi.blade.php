<x-layouts.siswa 
    title="Materi Pembelajaran" 
    activeMenu="materi-siswa"
    :breadcrumbs="[
        ['label' => 'Materi Pembelajaran', 'url' => route('siswa.materi')],
    ]"
    kelasSiswa="VIII-B"
>
    @php
        // Daftar materi ajar yang dapat diakses oleh siswa kelas VIII-B sesuai PRD & DESIGN.md 11
        $materiSiswa = [
            [
                'id' => 1,
                'judul' => 'Sistem Gerak pada Manusia & Tumbuhan',
                'mapel' => 'IPA Terpadu',
                'guru' => 'Budi Santoso, S.Pd.',
                'tanggal' => '03 Nov 2026',
                'tipe' => 'file',
                'format' => 'PDF (2.4 MB)',
                'keterangan' => 'Modul ajar lengkap materi struktur tulang, sendi, otot, serta mekanisme gerak higroskopis & esionom pada tumbuhan.',
            ],
            [
                'id' => 2,
                'judul' => 'Video Eksperimen: Pembuktian Fotosintesis (Percobaan Sachs)',
                'mapel' => 'IPA Terpadu',
                'guru' => 'Budi Santoso, S.Pd.',
                'tanggal' => '01 Nov 2026',
                'tipe' => 'link',
                'format' => 'Tautan Video (YouTube)',
                'keterangan' => 'Video demonstrasi praktikum uji amilum pada daun tertutup dan terbuka untuk tugas mandiri siswa di rumah.',
            ],
            [
                'id' => 3,
                'judul' => 'Teks Eksplanasi: Fenomena Alam dan Sosial Budaya',
                'mapel' => 'Bahasa Indonesia',
                'guru' => 'Faridah Hanum, S.Pd.',
                'tanggal' => '05 Nov 2026',
                'tipe' => 'file',
                'format' => 'DOCX (1.1 MB)',
                'keterangan' => 'Materi kaidah kebahasaan konjungsi kausalitas dan kronologis dalam penulisan teks eksplanasi ilmiah.',
            ],
            [
                'id' => 4,
                'judul' => 'Persamaan Garis Lurus (Gradien & Grafik Kartesius)',
                'mapel' => 'Matematika',
                'guru' => 'Nadia Fitri, S.Pd.',
                'tanggal' => '02 Nov 2026',
                'tipe' => 'file',
                'format' => 'PDF (3.8 MB)',
                'keterangan' => 'Latihan soal mandiri dan pembahasan mencari kemiringan garis (m) melalui dua titik koordinat.',
            ],
            [
                'id' => 5,
                'judul' => 'Adab Pergaulan Remaja Islami & Menghindari Ghibah',
                'mapel' => 'Pendidikan Agama Islam',
                'guru' => 'Amirul Hadi, S.Pd.I.',
                'tanggal' => '29 Okt 2026',
                'tipe' => 'link',
                'format' => 'Artikel / Tautan Web',
                'keterangan' => 'Bahan bacaan suplemen pembiasaan adab dan akhlak mulia dalam interaksi sesama pelajar Muhammadiyah.',
            ],
            [
                'id' => 6,
                'judul' => 'Simple Past Tense vs Present Perfect Tense in Narrative',
                'mapel' => 'Bahasa Inggris',
                'guru' => 'Muhammad Iqbal, S.Pd.',
                'tanggal' => '27 Okt 2026',
                'tipe' => 'file',
                'format' => 'PPTX (4.5 MB)',
                'keterangan' => 'Slide presentasi contoh cerita rakyat Malin Kundang dengan analisis rumus tenses lampau.',
            ],
        ];
    @endphp

    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest text-white text-xs font-bold uppercase tracking-wider">
                        Materi Belajar Kelas VIII-B
                    </span>
                    <span class="text-xs text-muted-text">&bull; Semester Ganjil 2026/2027</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Materi &amp; Bahan Belajar Mandiri
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Unduh modul dokumen atau buka tautan materi video yang dibagikan oleh bapak/ibu guru mata pelajaran Anda.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3.5 py-2 rounded-xl bg-soft border border-border-soft text-xs font-bold text-darkgreen">
                    6 Bahan Ajar Tersedia
                </span>
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
                    placeholder="Cari materi atau mata pelajaran..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2">
                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Mata Pelajaran</option>
                    <option value="ipa">IPA Terpadu</option>
                    <option value="indo">Bahasa Indonesia</option>
                    <option value="mtk">Matematika</option>
                    <option value="pai">Pendidikan Agama Islam</option>
                    <option value="inggris">Bahasa Inggris</option>
                </select>
            </div>
        </div>

        <!-- Materi Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($materiSiswa as $item)
                <div class="bg-white rounded-3xl border border-border-soft shadow-xs p-6 flex flex-col justify-between hover:border-forest/40 transition-colors">
                    <div>
                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-border-soft">
                            <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest text-xs font-bold">
                                {{ $item['mapel'] }}
                            </span>
                            <span class="text-xs text-muted-text">
                                {{ $item['tanggal'] }}
                            </span>
                        </div>

                        <div class="py-3">
                            <h2 class="text-base font-bold text-darkgreen leading-snug">
                                {{ $item['judul'] }}
                            </h2>
                            <p class="text-xs text-muted-text mt-1.5 leading-relaxed">
                                {{ $item['keterangan'] }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-border-soft flex items-center justify-between gap-3 text-xs">
                        <div class="min-w-0">
                            <p class="font-bold text-darkgreen truncate">Guru: {{ $item['guru'] }}</p>
                            <span class="text-muted-text text-[11px]">{{ $item['format'] }}</span>
                        </div>

                        @if($item['tipe'] === 'file')
                            <button type="button" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold transition-colors shrink-0 inline-flex items-center gap-1.5 cursor-pointer">
                                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                                <span>Unduh</span>
                            </button>
                        @else
                            <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl bg-soft hover:bg-border-soft/60 text-darkgreen font-bold transition-colors shrink-0 inline-flex items-center gap-1.5">
                                <x-icon name="arrow-top-right-on-square" class="w-4 h-4 text-emerald-600" />
                                <span>Buka Video</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts.siswa>
