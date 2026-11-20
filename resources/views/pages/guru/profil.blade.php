<x-layouts.guru 
    title="Profil Saya" 
    activeMenu="profil"
    :breadcrumbs="[
        ['label' => 'Guru', 'url' => route('dashboard.guru')],
        ['label' => 'Profil Saya', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-forest text-white flex items-center justify-center font-black text-xl shadow-xs">
                    {{ strtoupper(substr($guru->nama ?? $user->username, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-darkgreen tracking-tight">
                        {{ $guru->nama ?? $user->username }}
                    </h1>
                    <p class="text-xs sm:text-sm text-muted-text mt-0.5">
                        NIP: <strong class="text-darkgreen">{{ $guru->nip ?? '-' }}</strong> &bull; 
                        Username: <strong class="text-darkgreen">{{ $user->username }}</strong>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                    {{ $guru && $guru->stat_data === 'P' ? 'Guru Tetap / PNS' : 'Guru Aktif' }}
                </span>
                @if($isWaliKelas)
                    <span class="px-3.5 py-1.5 rounded-full bg-forest text-white text-xs font-bold">
                        Wali Kelas Aktif
                    </span>
                @endif
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
            
            <!-- Left 2 Cols: Biodata Guru & Beban Mengajar Aktif -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Data Pokok Guru -->
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs">
                    <h2 class="text-base font-bold text-darkgreen mb-4 pb-3 border-b border-border-soft flex items-center gap-2">
                        <x-icon name="user" class="w-4 h-4 text-forest" />
                        <span>Data Master Guru (m_guru)</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Nama Lengkap</span>
                            <span class="font-bold text-darkgreen text-sm">{{ $guru->nama ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Nomor Induk Pegawai (NIP)</span>
                            <span class="font-bold text-darkgreen text-sm">{{ $guru->nip ?? '-' }}</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Jenis Kelamin</span>
                            <span class="font-bold text-darkgreen text-sm">
                                {{ ($guru->jk ?? 'L') === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                            </span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-soft/50 border border-border-soft">
                            <span class="text-muted-text block mb-1">Guru Bimbingan Konseling (BK)</span>
                            <span class="font-bold text-darkgreen text-sm">
                                {{ ($guru->is_bk ?? '0') === '1' ? 'Ya (Guru BK)' : 'Bukan Guru BK' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Penugasan Mengajar Semester Ini -->
                <div class="bg-white rounded-3xl border border-border-soft p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-border-soft">
                        <h2 class="text-base font-bold text-darkgreen flex items-center gap-2">
                            <x-icon name="academic-cap" class="w-4 h-4 text-forest" />
                            <span>Penugasan Mengajar Aktif (Semester {{ $tasmAktif }})</span>
                        </h2>
                        <span class="text-xs text-muted-text font-medium">{{ $penugasanList->count() }} Mata Pelajaran</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs divide-y divide-border-soft">
                            <thead class="bg-soft/50 text-muted-text font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="py-2.5 px-4">No</th>
                                    <th class="py-2.5 px-4">Mata Pelajaran</th>
                                    <th class="py-2.5 px-4">Kelas</th>
                                    <th class="py-2.5 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-soft">
                                @forelse($penugasanList as $idx => $gm)
                                    <tr class="hover:bg-soft/30 transition-colors">
                                        <td class="py-3 px-4 font-bold text-forest">{{ $idx + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-darkgreen">{{ $gm->mapel->nama ?? '-' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2.5 py-0.5 rounded-md bg-soft font-semibold text-darkgreen border border-border-soft">
                                                Kelas {{ $gm->kelas->nama ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                Aktif
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-muted-text italic">
                                            Belum ada data penugasan mengajar pada periode semester ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                        <p class="text-xs text-muted-text mt-0.5">Perbarui kata sandi login secara berkala demi keamanan</p>
                    </div>

                    <form method="POST" action="{{ route('guru.profil.password') }}" class="space-y-3.5 pt-1">
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
                            Password baru tidak boleh sama dengan username login.
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white font-bold text-xs shadow-xs transition-colors">
                            Simpan Password Baru
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
</x-layouts.guru>
