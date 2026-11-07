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

                {{-- Aturan Password (realtime) --}}
                <div class="mb-5 p-3 rounded-xl bg-soft border border-border-soft text-[11px] text-muted-text space-y-2">
                    <div class="font-semibold text-darkgreen">Ketentuan Password Baru:</div>
                    <ul id="pw-rules" class="space-y-1">
                        <li data-rule="length"><span class="pw-icon">○</span> Minimal 8 karakter</li>
                        <li data-rule="case"><span class="pw-icon">○</span> Huruf besar dan huruf kecil</li>
                        <li data-rule="number"><span class="pw-icon">○</span> Minimal 1 angka</li>
                        <li data-rule="symbol"><span class="pw-icon">○</span> Minimal 1 simbol (contoh: ! @ # $ %)</li>
                        <li data-rule="username"><span class="pw-icon">○</span> Tidak sama dengan Username atau NIS</li>
                        <li data-rule="different"><span class="pw-icon">○</span> Berbeda dari password saat ini</li>
                    </ul>
                    <p>Sesi login di perangkat lain akan otomatis dikeluarkan.</p>
                </div>

                {{-- Form Ubah Password --}}
                <form action="{{ route('password.change.update') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Password Lama --}}
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-darkgreen mb-1.5">
                            Password Saat Ini (Password Lama / Sementara)
                        </label>
                        <div class="relative">
                            <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password saat ini"
                            class="w-full pl-3.5 pr-11 py-2.5 rounded-xl border {{ $errors->has('current_password') ? 'border-rose-400 bg-rose-50/20' : 'border-border-soft bg-white' }} text-sm text-darkgreen focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest"
                        />
                            <button type="button" data-toggle-password="current_password" aria-label="Tampilkan password" aria-pressed="false" class="absolute inset-y-0 right-0 px-3.5 flex items-center text-muted-text hover:text-darkgreen focus:outline-none focus-visible:text-forest"><svg class="eye-on w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.04 12.32a1 1 0 0 1 0-.64C3.42 7.51 7.36 4.5 12 4.5c4.64 0 8.57 3.01 9.96 7.18a1 1 0 0 1 0 .64C20.58 16.49 16.64 19.5 12 19.5c-4.64 0-8.57-3.01-9.96-7.18Z"/><circle cx="12" cy="12" r="3"/></svg><svg class="eye-off w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.22A10.48 10.48 0 0 0 2.04 12c1.39 4.17 5.33 7.18 9.96 7.18 1.99 0 3.85-.55 5.44-1.52M6.23 6.23A10.45 10.45 0 0 1 12 4.82c4.64 0 8.57 3.01 9.96 7.18a10.5 10.5 0 0 1-4.29 5.45M6.23 6.23 3 3m3.23 3.23 3.65 3.65m7.89 7.89L21 21m-3.23-3.23-3.65-3.65m0 0a3 3 0 1 0-4.24-4.24m4.24 4.24L9.88 9.88"/></svg></button>
                        </div>
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label for="password" class="block text-xs font-semibold text-darkgreen mb-1.5">
                            Password Baru
                        </label>
                        <div class="relative">
                            <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full pl-3.5 pr-11 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-border-soft bg-white' }} text-sm text-darkgreen focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest"
                        />
                            <button type="button" data-toggle-password="password" aria-label="Tampilkan password" aria-pressed="false" class="absolute inset-y-0 right-0 px-3.5 flex items-center text-muted-text hover:text-darkgreen focus:outline-none focus-visible:text-forest"><svg class="eye-on w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.04 12.32a1 1 0 0 1 0-.64C3.42 7.51 7.36 4.5 12 4.5c4.64 0 8.57 3.01 9.96 7.18a1 1 0 0 1 0 .64C20.58 16.49 16.64 19.5 12 19.5c-4.64 0-8.57-3.01-9.96-7.18Z"/><circle cx="12" cy="12" r="3"/></svg><svg class="eye-off w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.22A10.48 10.48 0 0 0 2.04 12c1.39 4.17 5.33 7.18 9.96 7.18 1.99 0 3.85-.55 5.44-1.52M6.23 6.23A10.45 10.45 0 0 1 12 4.82c4.64 0 8.57 3.01 9.96 7.18a10.5 10.5 0 0 1-4.29 5.45M6.23 6.23 3 3m3.23 3.23 3.65 3.65m7.89 7.89L21 21m-3.23-3.23-3.65-3.65m0 0a3 3 0 1 0-4.24-4.24m4.24 4.24L9.88 9.88"/></svg></button>
                        </div>

                        {{-- Indikator kekuatan password --}}
                        <div class="mt-2">
                            <div class="flex gap-1">
                                <div class="pw-bar h-1.5 flex-1 rounded-full bg-gray-200"></div>
                                <div class="pw-bar h-1.5 flex-1 rounded-full bg-gray-200"></div>
                                <div class="pw-bar h-1.5 flex-1 rounded-full bg-gray-200"></div>
                                <div class="pw-bar h-1.5 flex-1 rounded-full bg-gray-200"></div>
                            </div>
                            <p id="pw-label" class="mt-1 text-[11px] text-muted-text">Kekuatan password: -</p>
                        </div>
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-darkgreen mb-1.5">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ketik ulang password baru"
                            class="w-full pl-3.5 pr-11 py-2.5 rounded-xl border border-border-soft bg-white text-sm text-darkgreen focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest"
                        />
                            <button type="button" data-toggle-password="password_confirmation" aria-label="Tampilkan password" aria-pressed="false" class="absolute inset-y-0 right-0 px-3.5 flex items-center text-muted-text hover:text-darkgreen focus:outline-none focus-visible:text-forest"><svg class="eye-on w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.04 12.32a1 1 0 0 1 0-.64C3.42 7.51 7.36 4.5 12 4.5c4.64 0 8.57 3.01 9.96 7.18a1 1 0 0 1 0 .64C20.58 16.49 16.64 19.5 12 19.5c-4.64 0-8.57-3.01-9.96-7.18Z"/><circle cx="12" cy="12" r="3"/></svg><svg class="eye-off w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.22A10.48 10.48 0 0 0 2.04 12c1.39 4.17 5.33 7.18 9.96 7.18 1.99 0 3.85-.55 5.44-1.52M6.23 6.23A10.45 10.45 0 0 1 12 4.82c4.64 0 8.57 3.01 9.96 7.18a10.5 10.5 0 0 1-4.29 5.45M6.23 6.23 3 3m3.23 3.23 3.65 3.65m7.89 7.89L21 21m-3.23-3.23-3.65-3.65m0 0a3 3 0 1 0-4.24-4.24m4.24 4.24L9.88 9.88"/></svg></button>
                        </div>
                        <p id="pw-match" class="mt-1 text-[11px] hidden"></p>
                    </div>

                    {{-- Tombol Submit & Logout --}}
                    <div class="pt-3 space-y-2">
                        <button
                            type="submit"
                            id="pw-submit"
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

    <script>
    document.addEventListener('DOMContentLoaded', () => {

        // Toggle tampilkan/sembunyikan password
        document.querySelectorAll('[data-toggle-password]').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.togglePassword);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
                btn.querySelector('.eye-on').classList.toggle('hidden', show);
                btn.querySelector('.eye-off').classList.toggle('hidden', !show);
            });
        });
        const username = @json(strtolower($user->username ?? ''));
        const current  = document.getElementById('current_password');
        const pw       = document.getElementById('password');
        const confirmP = document.getElementById('password_confirmation');
        const submit   = document.getElementById('pw-submit');
        const bars     = document.querySelectorAll('.pw-bar');
        const label    = document.getElementById('pw-label');
        const matchEl  = document.getElementById('pw-match');
        const items    = document.querySelectorAll('#pw-rules li');

        const levels = [
            { text: 'Sangat lemah', bar: 'bg-rose-500',    color: 'text-rose-600' },
            { text: 'Lemah',        bar: 'bg-orange-500',  color: 'text-orange-600' },
            { text: 'Cukup',        bar: 'bg-amber-500',   color: 'text-amber-600' },
            { text: 'Kuat',         bar: 'bg-emerald-500', color: 'text-emerald-600' },
        ];
        const allBarClasses   = ['bg-gray-200', ...levels.map(l => l.bar)];
        const allLabelClasses = ['text-muted-text', ...levels.map(l => l.color)];

        function check() {
            const v = pw.value;
            const rules = {
                length:    v.length >= 8,
                case:      /[a-z]/.test(v) && /[A-Z]/.test(v),
                number:    /\d/.test(v),
                symbol:    /[^A-Za-z0-9]/.test(v),
                username:  v.length > 0 && v.toLowerCase() !== username,
                different: v.length > 0 && v !== current.value,
            };

            items.forEach(li => {
                const ok = rules[li.dataset.rule];
                li.querySelector('.pw-icon').textContent = ok ? '✓' : '○';
                li.classList.toggle('text-emerald-600', ok);
                li.classList.toggle('font-medium', ok);
            });

            // Skor kekuatan: 4 aturan komposisi
            const score = [rules.length, rules.case, rules.number, rules.symbol].filter(Boolean).length;
            const level = v.length === 0 ? null : levels[Math.max(score - 1, 0)];

            bars.forEach((bar, i) => {
                bar.classList.remove(...allBarClasses);
                bar.classList.add(level && i < score ? level.bar : 'bg-gray-200');
            });
            label.classList.remove(...allLabelClasses);
            label.classList.add(level ? level.color : 'text-muted-text');
            label.textContent = 'Kekuatan password: ' + (level ? level.text : '-');

            // Kecocokan konfirmasi
            const cv = confirmP.value;
            if (cv.length === 0) {
                matchEl.classList.add('hidden');
            } else {
                const same = cv === v;
                matchEl.classList.remove('hidden', 'text-emerald-600', 'text-rose-600');
                matchEl.classList.add(same ? 'text-emerald-600' : 'text-rose-600');
                matchEl.textContent = same ? '✓ Konfirmasi cocok' : 'Konfirmasi belum cocok';
            }

            const allOk = Object.values(rules).every(Boolean);
            const matches = v.length > 0 && v === cv;
            submit.disabled = !(allOk && matches);
            submit.classList.toggle('opacity-50', submit.disabled);
            submit.classList.toggle('cursor-not-allowed', submit.disabled);
        }

        [current, pw, confirmP].forEach(el => el.addEventListener('input', check));
        check();
    });
    </script>

</x-layouts.public>