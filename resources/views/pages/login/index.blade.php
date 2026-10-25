<x-layouts.public title="Login SIAKAD" description="Pintu masuk sistem informasi akademik SMP Muhammadiyah 7 Medan untuk guru, siswa, dan staf.">

    {{-- Centered Login Info Page --}}
    <div class="flex-1 flex items-center justify-center py-16 sm:py-24 px-4">
        <div class="w-full max-w-lg space-y-6">

            {{-- Card Utama --}}
            <div class="bg-white rounded-2xl border border-border-soft shadow-xs p-8 sm:p-10 text-center space-y-6">
                {{-- School Identity Icon --}}
                <div class="flex justify-center">
                    <div class="w-20 h-20 rounded-2xl bg-forest/10 flex items-center justify-center">
                        <x-icon name="building-office" class="w-10 h-10 text-forest" />
                    </div>
                </div>

                {{-- Heading --}}
                <div class="space-y-2">
                    <x-badge variant="forest" size="md">Sistem Informasi Akademik</x-badge>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-darkgreen tracking-tight">
                        Login SIAKAD
                    </h1>
                    <p class="text-sm sm:text-base text-muted-text leading-relaxed">
                        {{ config('sekolah.nama') }}
                    </p>
                </div>

                {{-- Info Box --}}
                <div class="bg-soft rounded-xl border border-border-soft p-5 text-left space-y-2">
                    <div class="flex items-center gap-2 mb-3">
                        <x-icon name="sparkles" class="w-4 h-4 text-forest shrink-0" />
                        <span class="text-sm font-semibold text-darkgreen">Informasi Akses SIAKAD</span>
                    </div>
                    <p class="text-sm text-muted-text leading-relaxed">
                        Halaman autentikasi SIAKAD berada pada modul sistem akademik yang terpisah dari portal publik ini.
                        Gunakan akun yang telah diberikan oleh pihak sekolah untuk mengakses dashboard akademik Anda.
                    </p>
                    <div class="mt-3 bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <p class="text-xs text-amber-800 font-medium">
                            ⚠️ URL Login SIAKAD belum dikonfigurasi. Hubungi administrator sekolah untuk informasi akses.
                        </p>
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <x-button href="mailto:{{ config('sekolah.kontak.email') }}" variant="primary" size="md" icon="envelope">
                        Hubungi via Email
                    </x-button>
                    <x-button href="{{ url('/') }}" variant="outline" size="md" icon="arrow-left">
                        Kembali ke Beranda
                    </x-button>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="tel:{{ config('sekolah.kontak.telepon_raw') }}"
                   class="flex items-center gap-3 bg-white rounded-xl border border-border-soft px-4 py-3.5 hover:border-forest/40 hover:shadow-xs transition-all group focus-visible:outline-2 focus-visible:outline-forest">
                    <div class="w-9 h-9 rounded-lg bg-forest/10 flex items-center justify-center shrink-0">
                        <x-icon name="phone" class="w-4 h-4 text-forest" />
                    </div>
                    <div>
                        <p class="text-xs text-muted-text">Hubungi Sekolah</p>
                        <p class="text-sm font-semibold text-darkgreen group-hover:text-forest transition-colors">
                            {{ config('sekolah.kontak.telepon') }}
                        </p>
                    </div>
                </a>

                <div class="flex items-center gap-3 bg-white rounded-xl border border-border-soft px-4 py-3.5">
                    <div class="w-9 h-9 rounded-lg bg-forest/10 flex items-center justify-center shrink-0">
                        <x-icon name="clock" class="w-4 h-4 text-forest" />
                    </div>
                    <div>
                        <p class="text-xs text-muted-text">Jam Layanan</p>
                        <p class="text-sm font-semibold text-darkgreen">
                            Senin – Sabtu, 07.30 – 14.30
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.public>
