@php
    $isEdit = isset($guru);
    $title = $isEdit ? 'Edit Data Guru' : 'Tambah Guru Baru';
@endphp

<x-layouts.admin 
    :title="$title" 
    activeMenu="master-guru"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.guru.index')],
        ['label' => 'Guru & Tendik', 'url' => route('admin.master.guru.index')],
        ['label' => $title, 'url' => ''],
    ]"
>
    <div class="max-w-4xl space-y-6">

        <!-- Header -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">{{ $title }}</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    {{ $isEdit ? 'Perbarui data guru dan sinkronisasi status akun pada sistem SIAKAD.' : 'Tambahkan tenaga pengajar baru ke basis data RKM dan siapkan akun login SIAKAD.' }}
                </p>
            </div>
            <a href="{{ route('admin.master.guru.index') }}" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs sm:text-sm font-semibold flex items-center gap-1.5 transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 text-sage" />
                <span>Kembali</span>
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <x-icon name="x-circle" class="w-4 h-4 text-rose-600" />
                    <span>Terjadi kesalahan validasi:</span>
                </div>
                <ul class="list-disc list-inside pl-2 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <form 
            action="{{ $isEdit ? route('admin.master.guru.update', $guru) : route('admin.master.guru.store') }}" 
            method="POST" 
            class="p-6 sm:p-8 rounded-3xl bg-white border border-border-soft shadow-xs space-y-6"
        >
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="space-y-4">
                <h3 class="text-xs font-bold text-forest uppercase tracking-wider border-b border-border-soft pb-2">
                    1. Identitas Guru & Tendik (Tabel m_guru)
                </h3>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                        Nama Lengkap Beserta Gelar <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        value="{{ old('nama', $guru->nama ?? '') }}" 
                        required 
                        maxlength="50"
                        placeholder="Contoh: Drs. H. Ahmad Dahlan, M.Pd" 
                        class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest @error('nama') border-rose-400 @enderror"
                    >
                    <span class="text-[11px] text-muted-text block mt-1">Maksimal 50 karakter (sesuai panjang kolom m_guru).</span>
                </div>

                <!-- NIP -->
                <div>
                    <label for="nip" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                        Nomor Induk Pegawai (NIP) / NBM
                    </label>
                    <input 
                        type="text" 
                        id="nip" 
                        name="nip" 
                        value="{{ old('nip', $guru->nip ?? '') }}" 
                        maxlength="20"
                        placeholder="Contoh: 19800512 200501 1 003 atau NBM" 
                        class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest @error('nip') border-rose-400 @enderror"
                    >
                    <span class="text-[11px] text-muted-text block mt-1">Opsional, unik jika diisi.</span>
                </div>

                <!-- Jenis Kelamin & Peran BK -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs sm:text-sm font-medium text-darkgreen">
                                <input 
                                    type="radio" 
                                    name="jk" 
                                    value="L" 
                                    {{ old('jk', $guru->jk ?? 'L') === 'L' ? 'checked' : '' }}
                                    class="text-forest focus:ring-forest"
                                >
                                <span>Laki-laki</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs sm:text-sm font-medium text-darkgreen">
                                <input 
                                    type="radio" 
                                    name="jk" 
                                    value="P" 
                                    {{ old('jk', $guru->jk ?? '') === 'P' ? 'checked' : '' }}
                                    class="text-forest focus:ring-forest"
                                >
                                <span>Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">
                            Kategori Guru <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs sm:text-sm font-medium text-darkgreen">
                                <input 
                                    type="radio" 
                                    name="is_bk" 
                                    value="2" 
                                    {{ old('is_bk', $guru->is_bk ?? '2') === '2' ? 'checked' : '' }}
                                    class="text-forest focus:ring-forest"
                                >
                                <span>Guru Mata Pelajaran</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs sm:text-sm font-medium text-darkgreen">
                                <input 
                                    type="radio" 
                                    name="is_bk" 
                                    value="1" 
                                    {{ old('is_bk', $guru->is_bk ?? '') === '1' ? 'checked' : '' }}
                                    class="text-forest focus:ring-forest"
                                >
                                <span>Guru Bimbingan Konseling (BK)</span>
                            </label>
                        </div>
                    </div>
                </div>

                @if ($isEdit)
                    <!-- Status Keaktifan (Khusus Edit) -->
                    <div class="pt-2">
                        <label for="stat_data" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Status Keaktifan <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="stat_data" 
                            name="stat_data" 
                            class="w-full sm:w-72 px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest font-medium text-darkgreen"
                        >
                            <option value="A" {{ old('stat_data', $guru->stat_data) === 'A' ? 'selected' : '' }}>Aktif Mengajar (A)</option>
                            <option value="P" {{ old('stat_data', $guru->stat_data) === 'P' ? 'selected' : '' }}>Pensiun / Cuti (P)</option>
                            <option value="M" {{ old('stat_data', $guru->stat_data) === 'M' ? 'selected' : '' }}>Mutasi / Keluar (M)</option>
                        </select>
                        <span class="text-[11px] text-muted-text block mt-1">Mengubah status menjadi selain 'A' otomatis menonaktifkan akun login guru.</span>
                    </div>
                @endif
            </div>

            @if (!$isEdit)
                <!-- Section Buat Akun Login (Khusus Create) -->
                <div class="space-y-4 pt-4 border-t border-border-soft">
                    <h3 class="text-xs font-bold text-forest uppercase tracking-wider border-b border-border-soft pb-2 flex items-center justify-between">
                        <span>2. Akun Akses Portal SIAKAD (Opsional)</span>
                        <label class="inline-flex items-center gap-2 cursor-pointer font-normal normal-case text-xs text-darkgreen">
                            <input 
                                type="checkbox" 
                                id="checkBuatAkun" 
                                name="buat_akun" 
                                value="1"
                                {{ old('buat_akun') ? 'checked' : '' }}
                                onchange="toggleAccountSection(this.checked)"
                                class="rounded-md text-forest focus:ring-forest"
                            >
                            <span>Buat akun login sekarang</span>
                        </label>
                    </h3>

                    <div id="accountFields" class="{{ old('buat_akun') ? '' : 'hidden' }} space-y-4 p-4 rounded-2xl bg-soft/50 border border-border-soft">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="username" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                    Username Login <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username" 
                                    value="{{ old('username') }}" 
                                    placeholder="Contoh: ahmad.dahlan atau nip" 
                                    class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-white border border-border-soft focus:outline-hidden focus:ring-2 focus:ring-forest @error('username') border-rose-400 @enderror"
                                >
                            </div>

                            <div>
                                <label for="password" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                    Password Awal <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Minimal 6 karakter..." 
                                    class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-white border border-border-soft focus:outline-hidden focus:ring-2 focus:ring-forest @error('password') border-rose-400 @enderror"
                                >
                            </div>
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Akun akan dibuat dengan hak akses `guru`. Pengguna otomatis diwajibkan mengganti kata sandi ini saat pertama kali login.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <div class="pt-4 border-t border-border-soft flex items-center justify-end gap-3">
                <a href="{{ route('admin.master.guru.index') }}" class="px-5 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs sm:text-sm font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs transition-colors flex items-center gap-2">
                    <x-icon name="check" class="w-4 h-4" />
                    <span>{{ $isEdit ? 'Simpan Perubahan' : 'Tambahkan Guru' }}</span>
                </button>
            </div>
        </form>

    </div>

    @if (!$isEdit)
        <script>
            function toggleAccountSection(checked) {
                const fields = document.getElementById('accountFields');
                const userInput = document.getElementById('username');
                const passInput = document.getElementById('password');

                if (checked) {
                    fields.classList.remove('hidden');
                    // Buat saran username dari nama jika masih kosong
                    if (!userInput.value) {
                        const namaVal = document.getElementById('nama').value.trim();
                        if (namaVal) {
                            const clean = namaVal.toLowerCase().replace(/[^a-z0-9]/g, '');
                            userInput.value = clean.substring(0, 15);
                        }
                    }
                } else {
                    fields.classList.add('hidden');
                }
            }
        </script>
    @endif
</x-layouts.admin>
