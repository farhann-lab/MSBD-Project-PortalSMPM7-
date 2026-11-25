<x-layouts.siswa 
    title="Profil Siswa" 
    activeMenu="profil-siswa"
    :breadcrumbs="[
        ['label' => 'Siswa', 'url' => route('dashboard.siswa')],
        ['label' => 'Profil Siswa', 'url' => ''],
    ]"
    :kelasSiswa="$identitasSiswa->nama_kelas ?? $kelasPenempatan?->kelas?->nama ?? '-'"
>
    @if(config('siakad_rbac.enabled'))
    <section class="space-y-4">
        <div class="p-6 bg-white border border-border-soft rounded-2xl shadow-xs">
            <h1 class="text-xl font-bold text-darkgreen">Profil Siswa Saya</h1>
            <p class="text-xs text-muted-text mt-2">Data ini diambil berdasarkan akun siswa yang sedang login.</p>
            @if($identitasSiswa)
                <dl class="mt-4 grid gap-3 sm:grid-cols-2 text-sm">
                    <div><dt class="text-muted-text">Nama</dt><dd class="font-semibold">{{ $identitasSiswa->nama }}</dd></div>
                    <div><dt class="text-muted-text">Kelas</dt><dd class="font-semibold">{{ $identitasSiswa->nama_kelas }}</dd></div>
                    <div><dt class="text-muted-text">ID Siswa</dt><dd class="font-semibold">{{ $identitasSiswa->id }}</dd></div>
                </dl>
            @else
                <p class="mt-4 text-sm text-muted-text">Penempatan siswa periode aktif belum tersedia.</p>
            @endif
        </div>
        <a class="inline-block text-sm text-forest underline" href="{{ route('password.change') }}">Ubah password saya</a>
    </section>
    @else
    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-forest text-white flex items-center justify-center font-black text-xl shadow-xs">
                    {{ strtoupper(substr($siswa->nama ?? $user->username, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                        {{ $siswa->nama ?? $user->username }}
                    </h1>
                    <p class="text-xs sm:text-sm text-muted-text mt-0.5">
                        NIS: <strong class="text-darkgreen">{{ $siswa->nis ?? '-' }}</strong> &bull; 
                        NISN: <strong class="text-darkgreen">{{ $siswa->nisn ?? '-' }}</strong> &bull; 
                        {{ $kelasPenempatan ? 'Kelas ' . ($kelasPenempatan->kelas->nama ?? '-') : 'Belum Ditentukan' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                    Siswa Aktif
                </span>
                <span class="px-3.5 py-1.5 rounded-full bg-forest/10 text-forest text-xs font-bold">
                    Tahun Ajaran {{ $taAktif }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5">
                <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <p class="font-bold mb-1">Gagal memperbarui data:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left 2 Cols: Biodata & Data Akademik Siswa (DIR-8: Tanpa NIK/Alamat/Ortu) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Data Pokok Siswa -->
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs">
                    <h2 class="text-base font-bold text-darkgreen mb-4 pb-3 border-b border-border-soft flex items-center gap-2">
                        <x-icon name="user" class="w-4 h-4 text-forest" />
                        <span>Biodata &amp; Informasi Akademik (m_siswa)</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Nama Lengkap</span>
                            <span class="font-bold text-darkgreen text-sm">{{ $siswa->nama ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Nomor Induk Siswa (NIS)</span>
                            <span class="font-bold text-darkgreen text-sm">{{ $siswa->nis ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">NISN Nasional</span>
                            <span class="font-bold text-darkgreen text-sm">{{ $siswa->nisn ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Jenis Kelamin</span>
                            <span class="font-bold text-darkgreen text-sm">
                                {{ ($siswa->jk ?? 'L') === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                            </span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Rombel / Kelas Aktif</span>
                            <span class="font-bold text-forest text-sm">
                                {{ $kelasPenempatan ? 'Kelas ' . ($kelasPenempatan->kelas->nama ?? '-') . ' (Tingkat ' . ($kelasPenempatan->kelas->tingkat ?? '-') . ')' : 'Belum Ada Penempatan' }}
                            </span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Status Keaktifan</span>
                            <span class="font-bold text-emerald-700 text-sm">
                                {{ ($siswa->stat_data ?? 'A') === 'A' ? 'Aktif Terdaftar' : 'Non-Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Banner Perlindungan Data Pribadi (DIR-8) -->
                <div class="p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-900 text-xs flex items-start gap-3">
                    <x-icon name="shield-check" class="w-5 h-5 text-sky-700 shrink-0 mt-0.5" />
                    <div>
                        <span class="font-bold block text-sm">Perlindungan Data Pribadi Siswa (DIR-8)</span>
                        <p class="text-sky-800 mt-0.5 leading-relaxed">
                            Sesuai kebijakan privasi sistem, data pribadi sensitif seperti NIK, nomor Kartu Keluarga, alamat domisili lengkap, tanggal lahir, dan informasi orang tua/wali dilindungi secara ketat dan tidak diekspos ke publik maupun siswa lainnya.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Keamanan & Ganti Password Mandiri (AUT-6) -->
            <div class="space-y-6">
                
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs space-y-4">
                    <div>
                        <h3 class="text-base font-bold text-darkgreen flex items-center gap-2">
                            <x-icon name="key" class="w-4 h-4 text-forest" />
                            <span>Ganti Password Akun</span>
                        </h3>
                        <p class="text-xs text-muted-text mt-0.5">Ubah password akun portal belajar Anda</p>
                    </div>

                    <form method="POST" action="{{ route('siswa.profil.password') }}" class="space-y-3.5 pt-1">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" required class="w-full px-3.5 py-2 rounded-xl border border-border-soft text-xs focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Password Baru (min. 8 karakter)</label>
                            <input type="password" name="password" required class="w-full px-3.5 py-2 rounded-xl border border-border-soft text-xs focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-darkgreen mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" required class="w-full px-3.5 py-2 rounded-xl border border-border-soft text-xs focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>

                        <div class="p-3 rounded-xl bg-soft text-[11px] text-muted-text leading-relaxed">
                            Password baru tidak boleh sama dengan username atau NIS Anda.
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold text-xs shadow-xs transition-colors">
                            Simpan Password Baru
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
    @endif
</x-layouts.siswa>
