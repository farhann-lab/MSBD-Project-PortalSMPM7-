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

                {{-- Segmented Role Switcher (Admin | Guru & Wali | Siswa / Wali) --}}
                <div class="grid grid-cols-3 p-1 rounded-xl bg-soft border border-border-soft/80 mb-5 text-center text-xs font-semibold"
                     role="tablist"
                     aria-label="Pilih Peran Login">
                    <button type="button"
                            role="tab"
                            aria-selected="true"
                            id="role-tab-admin"
                            class="py-2 rounded-lg bg-white text-darkgreen shadow-2xs transition-all cursor-pointer font-bold role-switcher-btn"
                            data-role="admin">
                        Admin
                    </button>
                    <button type="button"
                            role="tab"
                            aria-selected="false"
                            id="role-tab-guru"
                            class="py-2 rounded-lg text-muted-text hover:text-darkgreen transition-all cursor-pointer role-switcher-btn"
                            data-role="guru">
                        Guru &amp; Wali
                    </button>
                    <button type="button"
                            role="tab"
                            aria-selected="false"
                            id="role-tab-siswa"
                            class="py-2 rounded-lg text-muted-text hover:text-darkgreen transition-all cursor-pointer role-switcher-btn"
                            data-role="siswa">
                        Siswa / Wali
                    </button>
                </div>

                {{-- Form Login Interaktif --}}
                <form id="siakad-login-form" method="POST" action="{{ url('/login') }}" class="space-y-4" onsubmit="handleLoginSubmit(event)">
                    @csrf

                    {{-- Hidden role input --}}
                    <input type="hidden" name="role" id="login-role" value="admin">

                    {{-- Username / NIP / NISN Input --}}
                    <div>
                        <label for="username" class="block text-xs font-bold text-darkgreen mb-1.5">
                            Username / NIP / NISN
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text/70">
                                <x-icon name="user" class="w-4 h-4" />
                            </div>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                required
                                autocomplete="username"
                                placeholder="Masukkan NIP, NISN, atau ID Pengguna"
                                class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border border-border-soft bg-white text-darkgreen placeholder:text-muted-text/60 focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition-colors shadow-2xs"
                            />
                        </div>
                        <p id="username-helper" class="text-[11px] text-muted-text mt-1.5 leading-tight">
                            Gunakan NIP untuk staf pengajar atau NISN untuk peserta didik
                        </p>
                    </div>

                    {{-- Kata Sandi Input --}}
                    <div>
                        <label for="password" class="block text-xs font-bold text-darkgreen mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text/70">
                                <x-icon name="lock" class="w-4 h-4" />
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                class="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm rounded-xl border border-border-soft bg-white text-darkgreen placeholder:text-muted-text/60 focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition-colors shadow-2xs"
                            />
                            {{-- Toggle Password Visibility Button --}}
                            <button
                                type="button"
                                id="toggle-password-btn"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-muted-text hover:text-darkgreen transition-colors cursor-pointer focus:outline-none"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <span id="icon-eye-open"><x-icon name="eye" class="w-4 h-4" /></span>
                                <span id="icon-eye-closed" class="hidden"><x-icon name="eye-slash" class="w-4 h-4" /></span>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between text-xs pt-0.5">
                        <label class="inline-flex items-center gap-2 text-muted-text cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                class="w-4 h-4 rounded-md border-border-soft text-forest focus:ring-forest/30 cursor-pointer"
                            />
                            <span>Ingat Saya di Perangkat Ini</span>
                        </label>

                        <a href="mailto:{{ config('sekolah.kontak.email') }}?subject=Bantuan%20Akses%20SIAKAD"
                           class="font-semibold text-forest hover:text-darkgreen transition-colors">
                            Lupa Akses?
                        </a>
                    </div>

                    {{-- Tombol Submit Utama: Masuk ke SIAKAD --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            id="submit-login-btn"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold text-xs sm:text-sm shadow-md shadow-forest/20 transition-all duration-150 hover:-translate-y-0.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest cursor-pointer"
                        >
                            <span>Masuk ke SIAKAD</span>
                            <x-icon name="login" class="w-4 h-4" />
                        </button>
                    </div>
                </form>

                {{-- Contact Administrator Note --}}
                <div class="text-center text-xs text-muted-text pt-5 mt-5 border-t border-border-soft/70">
                    Kendala akses akun? Hubungi <span class="font-semibold text-darkgreen">Administrator Sekolah</span>
                </div>
            </div>

            {{-- ── 3. Akses Cepat Ekosistem Digital Kampus (CBT, E-Rapor, PPDB) ── --}}
            <div class="mt-6 bg-white/80 backdrop-blur-sm rounded-3xl border border-border-soft/90 p-4 sm:p-5 shadow-2xs">
                <p class="text-[11px] font-bold tracking-wider uppercase text-center text-muted-text/80 mb-3.5">
                    Akses Cepat Ekosistem Digital Kampus
                </p>

                <div class="grid grid-cols-3 gap-2.5 sm:gap-3">
                    {{-- CBT Online --}}
                    <a href="https://cbt.smpm7medan.sch.id"
                       target="_blank"
                       rel="noopener"
                       class="flex flex-col items-center justify-center gap-1.5 p-3 rounded-2xl border border-border-soft/80 bg-soft/50 hover:bg-white hover:border-forest/40 hover:shadow-xs transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-white border border-border-soft flex items-center justify-center text-forest group-hover:bg-forest group-hover:text-white transition-colors shadow-2xs">
                            <x-icon name="computer-desktop" class="w-4 h-4" />
                        </div>
                        <span class="text-[11px] sm:text-xs font-bold text-darkgreen group-hover:text-forest transition-colors">
                            CBT Online
                        </span>
                    </a>

                    {{-- E-Rapor --}}
                    <a href="https://rapor.smpm7medan.sch.id"
                       target="_blank"
                       rel="noopener"
                       class="flex flex-col items-center justify-center gap-1.5 p-3 rounded-2xl border border-border-soft/80 bg-soft/50 hover:bg-white hover:border-forest/40 hover:shadow-xs transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-white border border-border-soft flex items-center justify-center text-forest group-hover:bg-forest group-hover:text-white transition-colors shadow-2xs">
                            <x-icon name="clipboard-check" class="w-4 h-4" />
                        </div>
                        <span class="text-[11px] sm:text-xs font-bold text-darkgreen group-hover:text-forest transition-colors">
                            E-Rapor
                        </span>
                    </a>

                    {{-- PPDB Online --}}
                    <a href="https://ppdb.smpm7medan.sch.id"
                       target="_blank"
                       rel="noopener"
                       class="flex flex-col items-center justify-center gap-1.5 p-3 rounded-2xl border border-border-soft/80 bg-soft/50 hover:bg-white hover:border-forest/40 hover:shadow-xs transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-white border border-border-soft flex items-center justify-center text-forest group-hover:bg-forest group-hover:text-white transition-colors shadow-2xs">
                            <x-icon name="user-plus" class="w-4 h-4" />
                        </div>
                        <span class="text-[11px] sm:text-xs font-bold text-darkgreen group-hover:text-forest transition-colors">
                            PPDB Online
                        </span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── 4. Footer Login Bar ── --}}
        <div class="relative w-full max-w-6xl mx-auto pt-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-muted-text border-t border-border-soft/60 mt-8">
            <div class="flex items-center gap-2">
                <x-icon name="shield-check" class="w-4 h-4 text-sage shrink-0" />
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
            const roleInput = document.getElementById('login-role');
            const usernameInput = document.getElementById('username');
            const usernameHelper = document.getElementById('username-helper');

            // Switch role tab
            roleButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const selectedRole = btn.getAttribute('data-role');
                    roleInput.value = selectedRole;

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
                        usernameHelper.textContent = 'Gunakan NIP untuk staf pengajar / wali kelas';
                    } else {
                        usernameInput.placeholder = 'Masukkan NISN atau ID Siswa';
                        usernameHelper.textContent = 'Gunakan NISN peserta didik untuk login';
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

        function handleLoginSubmit(event) {
            // Mencegah error reload jika backend belum tersambung, menampilkan status interaktif yang aman
            event.preventDefault();
            const btn = document.getElementById('submit-login-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menghubungkan...</span>
            `;
            setTimeout(() => {
                alert('Modul autentikasi SIAKAD sedang dalam proses integrasi database akademik. Silakan hubungi administrator sekolah.');
                btn.innerHTML = originalText;
            }, 600);
        }
    </script>
    @endpush

</x-layouts.public>