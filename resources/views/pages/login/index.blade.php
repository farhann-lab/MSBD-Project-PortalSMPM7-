<x-layouts.public title="Login SIAKAD" description="Pintu masuk Sistem Informasi Akademik Terpadu (SIAKAD) SMP Muhammadiyah 7 Medan untuk Guru, Siswa, dan Staf.">

    {{-- Latar Belakang Lembut Sesuai DESIGN.md & Gambar Desain --}}
    <div class="relative flex-1 flex flex-col justify-between py-6 sm:py-10 px-4 sm:px-6 min-h-[calc(100vh-5rem)] overflow-hidden">

        {{-- Subtle radial gradient glows (Hijau Mint/Sage lembut) --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-gradient-to-tr from-mint/20 via-sage/10 to-transparent rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

        {{-- ── 1. Top Utility Bar ── --}}
        <div class="relative w-full max-w-6xl mx-auto flex items-center justify-between pb-6">
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-darkgreen hover:text-forest transition-colors rounded-lg px-2.5 py-1.5 hover:bg-white/60 focus-visible:outline-2 focus-visible:outline-forest">
                <x-icon name="arrow-left" class="w-4 h-4 text-forest" />
                <span>Website Sekolah</span>
            </a>

            <div class="flex items-center gap-4 sm:gap-6 text-xs sm:text-sm">
                {{-- Status Server Aktif & Aman --}}
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/90 border border-border-soft shadow-2xs text-darkgreen font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs">Server Aktif &amp; Aman</span>
                </div>

                {{-- Pusat Bantuan --}}
                <a href="mailto:{{ config('sekolah.kontak.email', 'smpm7medan@gmail.com') }}"
                   class="inline-flex items-center gap-1.5 text-muted-text hover:text-forest transition-colors font-medium">
                    <x-icon name="help" class="w-4 h-4 text-muted-text" />
                    <span>Pusat Bantuan</span>
                </a>
            </div>
        </div>

        {{-- ── 2. Card Login Utama (Sesuai Desain Referensi) ── --}}
        <div class="relative w-full max-w-[460px] mx-auto my-auto">
            <div class="bg-white rounded-3xl border border-border-soft/90 shadow-sm p-7 sm:p-9 transition-all">

                {{-- Logo / Icon SIAKAD dengan Badge Centang Hijau --}}
                <div class="flex justify-center mb-5">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-darkgreen flex items-center justify-center p-2.5 shadow-md shadow-darkgreen/15 ring-4 ring-white">
                            <img
                                src="{{ asset('images/school/logo.png') }}"
                                alt="Logo SMP Muhammadiyah 7 Medan"
                                class="w-full h-full object-contain filter brightness-110"
                                width="48"
                                height="48"
                            />
                        </div>
                        {{-- Verified check badge --}}
                        <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-white shadow-xs">
                            <x-icon name="check" class="w-3 h-3 stroke-[3]" />
                        </div>
                    </div>
                </div>

                {{-- Heading & Identitas --}}
                <div class="text-center space-y-1.5 mb-6">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-darkgreen tracking-tight leading-snug">
                        SIAKAD SMP Muhammadiyah 7<br class="hidden sm:inline"> Medan
                    </h1>
                    <p class="text-xs sm:text-sm text-muted-text font-normal">
                        Sistem Informasi Akademik Terpadu
                    </p>

                    {{-- Tahun Ajaran Pill Badge --}}
                    <div class="pt-1.5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-soft border border-border-soft text-olive">
                            <x-icon name="calendar" class="w-3.5 h-3.5 text-sage" />
                            <span>Tahun Ajaran {{ config('sekolah.tahun_ajaran_aktif', '2026/2027 Ganjil') }}</span>
                        </span>
                    </div>
                </div>

                {{-- Notifikasi Status / Pesan Sukses --}}
                @if (session('status'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
                        <x-icon name="check" class="w-4 h-4 text-emerald-600 shrink-0" />
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Notifikasi Error Global --}}
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

                {{-- Segmented Role Switcher (Admin | Guru & Wali | Siswa) --}}
                <div class="grid grid-cols-3 p-1 rounded-xl bg-soft border border-border-soft/80 mb-5 text-center text-xs font-semibold"
                     role="tablist"
                     aria-label="Pilih Jenis Pengguna">
                    <button type="button"
                            class="role-switcher-btn py-2 rounded-lg transition-all text-muted-text hover:text-darkgreen font-medium"
                            data-role="admin"
                            role="tab"
                            aria-selected="false">
                        Admin
                    </button>
                    <button type="button"
                            class="role-switcher-btn py-2 rounded-lg transition-all text-muted-text hover:text-darkgreen font-medium"
                            data-role="guru"
                            role="tab"
                            aria-selected="false">
                        Guru / Wali
                    </button>
                    <button type="button"
                            class="role-switcher-btn py-2 rounded-lg transition-all bg-white text-darkgreen shadow-2xs font-bold"
                            data-role="siswa"
                            role="tab"
                            aria-selected="true">
                        Siswa
                    </button>
                </div>

                {{-- Form Login Utama --}}
                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Username / ID Input --}}
                    <div>
                        <label for="username" class="block text-xs font-semibold text-darkgreen mb-1.5">
                            Username / Nomor Induk (NIS/NIP)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                                <x-icon name="user" class="w-4 h-4 text-sage" />
                            </span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                required
                                autofocus
                                autocomplete="username"
                                value="{{ old('username') }}"
                                placeholder="Masukkan NIS atau Username"
                                class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border {{ $errors->has('username') ? 'border-rose-400 bg-rose-50/20' : 'border-border-soft bg-white' }} text-sm text-darkgreen placeholder:text-muted-text/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest transition-all"
                            />
                        </div>
                        <p id="username-helper" class="mt-1 text-[11px] text-muted-text">
                            Gunakan NIS peserta didik untuk login
                        </p>
                    </div>

                    {{-- Password Input with Visibility Toggle --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-darkgreen">
                                Password
                            </label>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                                <x-icon name="lock" class="w-4 h-4 text-sage" />
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password Anda"
                                class="w-full pl-10 pr-11 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-border-soft bg-white' }} text-sm text-darkgreen placeholder:text-muted-text/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest transition-all"
                            />
                            {{-- Toggle View Password --}}
                            <button
                                type="button"
                                id="toggle-password-btn"
                                aria-label="Lihat password"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-muted-text hover:text-forest transition-colors">
                                <x-icon id="icon-eye-open" name="eye" class="w-4 h-4" />
                                <x-icon id="icon-eye-closed" name="eye-off" class="w-4 h-4 hidden" />
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="w-4 h-4 rounded border-border-soft text-forest focus:ring-forest/30"
                            />
                            <span class="text-xs text-muted-text">Ingat sesi saya</span>
                        </label>

                        <span class="text-xs text-muted-text">
                            Lupa password? <a href="mailto:{{ config('sekolah.kontak.email') }}" class="text-forest hover:underline font-medium">Hubungi Admin</a>
                        </span>
                    </div>

                    {{-- Tombol Masuk / Login CTA --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            id="submit-login-btn"
                            class="w-full py-3 px-4 rounded-xl bg-forest hover:bg-forest/90 text-white font-semibold text-sm tracking-wide shadow-md shadow-darkgreen/15 transition-all duration-200 flex items-center justify-center gap-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest">
                            <span>Masuk ke SIAKAD</span>
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- ── 3. Footer Bar Bawah ── --}}
        <div class="relative w-full max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between pt-8 border-t border-border-soft/60 text-muted-text gap-3">
            <div class="flex items-center gap-2 text-xs">
                <x-icon name="shield" class="w-4 h-4 text-sage shrink-0" />
                <span>&copy; {{ date('Y') }} SMP Muhammadiyah 7 Medan. Hak Cipta Dilindungi.</span>
            </div>

            <div class="flex items-center gap-4 text-xs font-medium">
                <span>Enkripsi 256-bit SSL</span>
                <span class="text-border-soft">&bull;</span>
                <a href="{{ url('/profil') }}" class="hover:text-forest transition-colors">Kebijakan Privasi</a>
                <span class="text-border-soft">&bull;</span>
                <a href="{{ url('/profil') }}" class="hover:text-forest transition-colors">Syarat &amp; Ketentuan</a>
            </div>
        </div>

    </div>

    {{-- Interactive Tab & Password Visibility Toggle Script --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roleButtons = document.querySelectorAll('.role-switcher-btn');
            const usernameInput = document.getElementById('username');
            const usernameHelper = document.getElementById('username-helper');

            // Switch role tab
            roleButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const selectedRole = btn.getAttribute('data-role');

                    roleButtons.forEach(b => {
                        b.setAttribute('aria-selected', 'false');
                        b.classList.remove('bg-white', 'text-darkgreen', 'shadow-2xs', 'font-bold');
                        b.classList.add('text-muted-text');
                    });

                    btn.setAttribute('aria-selected', 'true');
                    btn.classList.add('bg-white', 'text-darkgreen', 'shadow-2xs', 'font-bold');
                    btn.classList.remove('text-muted-text');

                    if (selectedRole === 'admin') {
                        usernameInput.placeholder = 'Masukkan ID Admin atau Username';
                        usernameHelper.textContent = 'Gunakan kredensial akun administrator sekolah';
                    } else if (selectedRole === 'guru') {
                        usernameInput.placeholder = 'Masukkan NIP atau ID Guru';
                        usernameHelper.textContent = 'Gunakan username untuk staf pengajar / wali kelas';
                    } else {
                        usernameInput.placeholder = 'Masukkan NIS peserta didik';
                        usernameHelper.textContent = 'Gunakan NIS peserta didik untuk login';
                    }
                });
            });

            // Toggle password visibility
            const togglePasswordBtn = document.getElementById('toggle-password-btn');
            const passwordInput = document.getElementById('password');
            const iconEyeOpen = document.getElementById('icon-eye-open');
            const iconEyeClosed = document.getElementById('icon-eye-closed');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', () => {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    if (isPassword) {
                        iconEyeOpen.classList.add('hidden');
                        iconEyeClosed.classList.remove('hidden');
                    } else {
                        iconEyeOpen.classList.remove('hidden');
                        iconEyeClosed.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @endpush

</x-layouts.public>
