<x-layouts.public title="Ubah Password SIAKAD" description="Perbarui password akun SIAKAD SMP Muhammadiyah 7 Medan.">

    {{-- Layout Fokus Tanpa Sidebar Sesuai DESIGN.md Bab 11 --}}
    <div class="relative flex-1 flex flex-col justify-center py-10 px-4 sm:px-6 min-h-[calc(100vh-5rem)]">

        <div class="relative w-full max-w-[480px] mx-auto my-auto">
            <div class="bg-white rounded-3xl border border-border-soft/90 shadow-sm p-7 sm:p-9">

                {{-- Header Icon & Title --}}
                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center mx-auto mb-3 shadow-2xs">
                        <x-icon name="key" class="w-7 h-7 text-amber-600" />
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-darkgreen tracking-tight">
                        Ubah Password Akun
                    </h1>
                    <p class="text-xs sm:text-sm text-muted-text mt-1">
                        Demi keamanan akun Anda, silakan buat password baru yang kuat.
                    </p>
                </div>

                {{-- Notifikasi Peringatan / Mode Wajib --}}
                @if (session('warning'))
                    <div class="mb-5 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium flex items-start gap-2.5">
                        <x-icon name="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
                        <div>{{ session('warning') }}</div>
                    </div>
                @endif

                {{-- Error List --}}
                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Aturan Password Info Box --}}
                <div class="mb-5 p-3 rounded-xl bg-soft border border-border-soft text-[11px] text-muted-text space-y-1">
                    <div class="font-semibold text-darkgreen">Ketentuan Password Baru:</div>
                    <ul class="list-disc list-inside space-y-0.5 pl-1">
                        <li>Minimal 8 karakter.</li>
                        <li>Tidak boleh sama dengan Username atau NIS Anda.</li>
                        <li>Sesi login di perangkat lain akan otomatis dikeluarkan.</li>
                    </ul>
                </div>

                {{-- Form Ubah Password --}}
                <form action="{{ route('password.change.update') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Password Lama --}}
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-darkgreen mb-1.5">
                            Password Saat Ini (Password Lama / Sementara)
                        </label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password saat ini"
                            class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('current_password') ? 'border-rose-400 bg-rose-50/20' : 'border-border-soft bg-white' }} text-sm text-darkgreen focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest"
                        />
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label for="password" class="block text-xs font-semibold text-darkgreen mb-1.5">
                            Password Baru
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-border-soft bg-white' }} text-sm text-darkgreen focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest"
                        />
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-darkgreen mb-1.5">
                            Konfirmasi Password Baru
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ketik ulang password baru"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-sm text-darkgreen focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest"
                        />
                    </div>

                    {{-- Tombol Submit & Logout --}}
                    <div class="pt-3 space-y-2">
                        <button
                            type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-forest hover:bg-forest/90 text-white font-semibold text-sm tracking-wide shadow-md shadow-darkgreen/15 transition-all flex items-center justify-center gap-2 focus-visible:outline-2 focus-visible:outline-forest">
                            <x-icon name="check" class="w-4 h-4" />
                            <span>Simpan Password Baru</span>
                        </button>
                    </div>
                </form>

                {{-- Opsi Logout jika tidak ingin melanjutkan sekarang --}}
                <div class="mt-4 text-center">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-muted-text hover:text-rose-600 transition-colors font-medium">
                            Keluar dari akun
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>

</x-layouts.public>

