<x-layouts.guru 
    title="Catatan Siswa / BK" 
    activeMenu="wali-kelas-catatan"
    :breadcrumbs="[
        ['label' => 'Perwalian (Kelas VIII-B)', 'url' => ''],
        ['label' => 'Catatan Siswa / BK', 'url' => route('guru.perwalian.catatan')],
    ]"
    :isWaliKelas="true"
    kelasPerwalian="VIII-B"
>
    @php
        // Log catatan pembinaan, adab, prestasi & koordinasi BK
        $catatanList = [
            [
                'id' => 1,
                'tanggal' => '07 Nov 2026',
                'nama' => 'Bagas Wicaksono',
                'nis' => '2405',
                'kategori' => 'Disiplin / BK',
                'tipe' => 'warning',
                'judul' => 'Koordinasi Ketidakhadiran 3 Hari Berturut-turut',
                'keterangan' => 'Siswa tidak hadir tanpa kabar sejak Rabu. Wali kelas telah menghubungi orang tua via telepon dan meneruskan memo pemanggilan wali ke guru BK (Ibu Indah Lestari, S.Pd.).',
                'tindak_lanjut' => 'Pemanggilan orang tua pada hari Selasa, 10 Nov 2026 pukul 09.00 WIB.',
            ],
            [
                'id' => 2,
                'tanggal' => '04 Nov 2026',
                'nama' => 'Ahmad Rizky Pratama',
                'nis' => '2401',
                'kategori' => 'Prestasi',
                'tipe' => 'success',
                'judul' => 'Juara 1 Olimpiade Matematika Tingkat Kota Medan',
                'keterangan' => 'Mengharumkan nama sekolah dan kelas VIII-B dengan meraih medali emas pada ajang kompetisi olimpiade sains dan matematika pelajar tingkat kota.',
                'tindak_lanjut' => 'Pemberian apresiasi pada apel bendera Senin pagi.',
            ],
            [
                'id' => 3,
                'tanggal' => '28 Okt 2026',
                'nama' => 'Annisa Rahmadani',
                'nis' => '2404',
                'kategori' => 'Kesehatan',
                'tipe' => 'info',
                'judul' => 'Izin Sakit Demam Berdarah (Rawat Inap)',
                'keterangan' => 'Orang tua menyerahkan surat keterangan rawat dari RS Muhammadiyah. Diberikan dispensasi tugas mandiri sampai pulih.',
                'tindak_lanjut' => 'Wali kelas memantau kondisi perkembangan kesehatan siswa.',
            ],
            [
                'id' => 4,
                'tanggal' => '20 Okt 2026',
                'nama' => 'Aisyah Putri Azzahra',
                'nis' => '2402',
                'kategori' => 'Karakter & Adab',
                'tipe' => 'success',
                'judul' => 'Kerapian dan Teladan Sholat Berjamaah',
                'keterangan' => 'Menjadi pelopor kedisiplinan sholat dzuhur berjamaah dan ketertiban administrasi piket kelas VIII-B.',
                'tindak_lanjut' => 'Direkomendasikan untuk penghargaan siswa teladan bulanan.',
            ],
        ];
    @endphp

    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-forest text-white text-xs font-bold uppercase tracking-wider">
                        Bimbingan &amp; Konseling (BK)
                    </span>
                    <span class="text-xs text-muted-text">&bull; Kelas Perwalian VIII-B</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                    Catatan Pembinaan Siswa &amp; Rekomendasi BK
                </h1>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Rekam jejak pembinaan karakter, prestasi, kedisiplinan, dan koordinasi dengan guru Bimbingan Konseling.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold flex items-center gap-2 transition-colors shadow-xs cursor-pointer">
                    <x-icon name="plus" class="w-4 h-4 text-emerald-300" />
                    <span>Tambah Catatan Pembinaan</span>
                </button>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                    <x-icon name="search" class="w-4 h-4 text-sage" />
                </span>
                <input 
                    type="text" 
                    placeholder="Cari nama siswa atau topik catatan..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                >
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2">
                <select class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen">
                    <option value="">Semua Kategori</option>
                    <option value="disiplin">Disiplin / BK</option>
                    <option value="prestasi">Prestasi</option>
                    <option value="karakter">Karakter &amp; Adab</option>
                    <option value="kesehatan">Kesehatan</option>
                </select>
            </div>
        </div>

        <!-- Catatan Feed / Cards List -->
        <div class="space-y-4">
            @foreach($catatanList as $item)
                <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col gap-4 hover:border-forest/40 transition-colors">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-border-soft">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl {{ $item['tipe'] === 'warning' ? 'bg-amber-100 text-amber-800' : ($item['tipe'] === 'success' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }} flex items-center justify-center font-bold text-xs shrink-0">
                                @if($item['tipe'] === 'warning')
                                    <x-icon name="exclamation-circle" class="w-5 h-5 text-amber-700" />
                                @elseif($item['tipe'] === 'success')
                                    <x-icon name="trophy" class="w-5 h-5 text-emerald-700" />
                                @else
                                    <x-icon name="clipboard-check" class="w-5 h-5 text-blue-700" />
                                @endif
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-darkgreen">{{ $item['judul'] }}</h2>
                                <p class="text-xs text-muted-text">
                                    Siswa: <strong class="text-darkgreen">{{ $item['nama'] }}</strong> (NIS: {{ $item['nis'] }}) &bull; Tanggal: {{ $item['tanggal'] }}
                                </p>
                            </div>
                        </div>

                        <span class="self-start sm:self-auto px-3 py-1 rounded-full text-xs font-bold {{ $item['tipe'] === 'warning' ? 'bg-amber-50 text-amber-800 border border-amber-200' : ($item['tipe'] === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-blue-50 text-blue-800 border border-blue-200') }}">
                            {{ $item['kategori'] }}
                        </span>
                    </div>

                    <div class="space-y-2 text-xs sm:text-sm leading-relaxed text-darkgreen">
                        <p class="text-muted-text">{{ $item['keterangan'] }}</p>
                        
                        <div class="p-3.5 rounded-2xl bg-soft/60 border border-border-soft flex items-start gap-2.5">
                            <x-icon name="arrow-right" class="w-4 h-4 text-forest shrink-0 mt-0.5" />
                            <div class="text-xs">
                                <span class="font-bold text-darkgreen">Rencana Tindak Lanjut: </span>
                                <span class="text-muted-text">{{ $item['tindak_lanjut'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-soft flex items-center justify-between text-xs">
                        <span class="text-muted-text">Dicatat oleh: Budi Santoso, S.Pd. (Wali Kelas)</span>
                        <div class="flex items-center gap-2">
                            <button type="button" class="px-3 py-1 rounded-lg hover:bg-soft text-forest font-semibold transition-colors">
                                Edit Catatan
                            </button>
                            <button type="button" class="px-3 py-1 rounded-lg hover:bg-soft text-muted-text font-semibold transition-colors">
                                Cetak
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</x-layouts.guru>
