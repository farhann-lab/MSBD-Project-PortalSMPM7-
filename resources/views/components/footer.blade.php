<footer class="bg-forest text-white mt-auto border-t border-teal/20" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer Halaman Publik SMP Muhammadiyah 7 Medan</h2>

    {{-- Main 4-Column Footer Area --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-10 lg:pt-16 lg:pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
            
            {{-- Column 1: School Identity (Width: 4/12 on lg) --}}
            <div class="lg:col-span-4 space-y-4">
                <x-school-logo variant="white" size="md" />

                <p class="text-sm text-mint/90 leading-relaxed max-w-sm">
                    {{ config('sekolah.deskripsi', 'Pusat pendidikan menengah Islam terpadu yang membina akhlak mulia, keunggulan akademik, kemandirian berpikir, dan wawasan lingkungan.') }}
                </p>

                <div class="flex flex-wrap items-center gap-2 pt-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-white/10 text-white border border-white/20">
                        NPSN: {{ config('sekolah.npsn', '10257325') }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-mint/20 text-mint border border-mint/30">
                        Akreditasi {{ config('sekolah.akreditasi', 'A') }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium text-white/70">
                        PCM Medan Perjuangan
                    </span>
                </div>
            </div>

            {{-- Column 2: Navigasi Utama (Width: 2/12 on lg) --}}
            <div class="lg:col-span-2 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-mint/80 font-mono">
                    Navigasi Utama
                </h3>
                <ul class="space-y-2 text-sm" role="list">
                    <li>
                        <a href="{{ url('/') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profil') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Tentang Sekolah
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profil/kepala-sekolah') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Kepala Sekolah
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profil/struktur') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Struktur Organisasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profil/fasilitas') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Sarana & Prasarana
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/guru') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Direktori Guru
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/siswa') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Direktori Siswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/galeri') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Galeri Dokumentasi
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 3: Informasi & Layanan Portal (Width: 3/12 on lg) --}}
            <div class="lg:col-span-3 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-mint/80 font-mono">
                    Informasi & Layanan
                </h3>
                <ul class="space-y-2 text-sm" role="list">
                    <li>
                        <a href="{{ url('/berita') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Warta & Berita Terbaru
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/pengumuman') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Pengumuman Sekolah
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/kesiswaan/prestasi') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Prestasi Siswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/kesiswaan/ekstrakurikuler') }}" class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            Kegiatan Ekstrakurikuler
                        </a>
                    </li>
                    <li class="pt-2 border-t border-white/10">
                        <a href="{{ config('sekolah.portal.cbt.url', '#') }}" 
                           class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 group focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            <span>Portal CBT Online</span>
                            <x-icon name="external-link" class="w-3.5 h-3.5 text-mint/70 group-hover:text-mint" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ config('sekolah.portal.rapor.url', '#') }}" 
                           class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 group focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            <span>Portal Rapor Siswa</span>
                            <x-icon name="external-link" class="w-3.5 h-3.5 text-mint/70 group-hover:text-mint" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ config('sekolah.portal.ppdb.url', '#') }}" 
                           class="text-white/80 hover:text-white transition-colors inline-flex items-center gap-1.5 group focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            <span>Pendaftaran PPDB Online</span>
                            <x-icon name="external-link" class="w-3.5 h-3.5 text-mint/70 group-hover:text-mint" />
                        </a>
                    </li>
                    <li class="pt-1">
                        <a href="{{ url('/login') }}" 
                           class="text-mint font-semibold hover:text-white transition-colors inline-flex items-center gap-1.5 group focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            <span>Pintu Masuk Login SIAKAD</span>
                            <x-icon name="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 4: Kontak Resmi (Width: 3/12 on lg) --}}
            <div class="lg:col-span-3 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-mint/80 font-mono">
                    Kontak & Lokasi
                </h3>

                <ul class="space-y-3 text-sm" role="list">
                    <li class="flex items-start gap-2.5">
                        <x-icon name="map-pin" class="w-5 h-5 text-mint shrink-0 mt-0.5" />
                        <span class="text-white/80 leading-snug">
                            {{ config('sekolah.kontak.alamat_lengkap', 'Jl. Pelita II No. 3-5, Sidorame Barat II, Kec. Medan Perjuangan, Kota Medan, Sumatera Utara 20236') }}
                        </span>
                    </li>

                    <li class="flex items-center gap-2.5">
                        <x-icon name="phone" class="w-5 h-5 text-mint shrink-0" />
                        <a href="tel:{{ config('sekolah.kontak.telepon_raw', '0616621557') }}" 
                           class="text-white/80 hover:text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded">
                            {{ config('sekolah.kontak.telepon', '(061) 6621557') }}
                        </a>
                    </li>

                    <li class="flex items-center gap-2.5">
                        <x-icon name="envelope" class="w-5 h-5 text-mint shrink-0" />
                        <a href="mailto:{{ config('sekolah.kontak.email', 'smpm7medan@gmail.com') }}" 
                           class="text-white/80 hover:text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint rounded break-all">
                            {{ config('sekolah.kontak.email', 'smpm7medan@gmail.com') }}
                        </a>
                    </li>

                    <li class="flex items-start gap-2.5 pt-1 border-t border-white/10">
                        <x-icon name="clock" class="w-5 h-5 text-mint shrink-0 mt-0.5" />
                        <div class="text-xs text-white/70">
                            <span class="block font-medium text-white/90">Waktu Layanan:</span>
                            {{ config('sekolah.kontak.jam_layanan', 'Senin – Sabtu, 07.30 – 14.30 WIB') }}
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar: Copyright & Back-to-Top --}}
        <div class="border-t border-white/15 mt-10 lg:mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/70">
            <p class="text-center sm:text-left">
                &copy; {{ date('Y') }} {{ config('sekolah.nama', 'SMP Muhammadiyah 7 Medan') }}. Hak Cipta Dilindungi.
            </p>

            <div class="flex items-center gap-4">
                <span class="text-white/50 hidden sm:inline">&bull;</span>
                <span class="text-white/70">SIAKAD v1.0 (Fitur Publik)</span>
                <button type="button" 
                        id="back-to-top-btn"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition-colors cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-mint"
                        aria-label="Kembali ke atas halaman">
                    <span>Ke Atas</span>
                    <x-icon name="chevron-down" class="w-3.5 h-3.5 rotate-180" />
                </button>
            </div>
        </div>
    </div>
</footer>
