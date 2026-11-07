<x-layouts.admin 
    title="Manajemen Akun Admin" 
    activeMenu="sistem-manajemen-admin"
    :breadcrumbs="[
        ['label' => 'Akun & Sistem', 'url' => ''],
        ['label' => 'Manajemen Admin', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-forest/10 text-forest text-xs font-bold tracking-tight">
                    <x-icon name="shield-check" class="w-3.5 h-3.5" />
                    <span>Hak Istimewa Admin Utama</span>
                </div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen Akun Administrator</h2>
                <p class="text-xs sm:text-sm text-muted-text">
                    Kelola akun pengelola sistem SIAKAD SMP Muhammadiyah 7 Medan. Buat akun admin baru, reset password, dan pantau status keaktifan.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button 
                    type="button" 
                    onclick="document.getElementById('modal-tambah-admin').classList.remove('hidden')"
                    class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors cursor-pointer"
                >
                    <x-icon name="user-plus" class="w-4 h-4" />
                    <span>Tambah Admin Baru</span>
                </button>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm space-y-1 shadow-xs">
                <div class="font-bold flex items-center gap-2">
                    <x-icon name="exclamation-circle" class="w-4 h-4 text-red-600" />
                    <span>Terjadi kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside pl-2 space-y-0.5 text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Summary Stat Badges -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs text-muted-text font-medium block">Total Admin</span>
                    <span class="text-2xl font-extrabold text-darkgreen mt-1 block">{{ $admins->count() }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-forest/10 text-forest flex items-center justify-center">
                    <x-icon name="users" class="w-5 h-5" />
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs text-muted-text font-medium block">Admin Utama</span>
                    <span class="text-2xl font-extrabold text-forest mt-1 block">{{ $admins->where('is_admin_utama', true)->count() }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-mint/30 text-forest flex items-center justify-center">
                    <x-icon name="shield-check" class="w-5 h-5" />
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs text-muted-text font-medium block">Admin Aktif</span>
                    <span class="text-2xl font-extrabold text-emerald-700 mt-1 block">{{ $admins->where('is_active', true)->count() }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <x-icon name="check-circle" class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Master Admin Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="p-5 border-b border-border-soft flex items-center justify-between">
                <h3 class="text-sm font-bold text-darkgreen flex items-center gap-2">
                    <x-icon name="user-group" class="w-4 h-4 text-forest" />
                    <span>Daftar Pengguna Administrator</span>
                </h3>
                <span class="text-xs text-muted-text">Terurut berdasarkan hak istimewa & abjad</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">No</th>
                            <th class="py-3.5 px-4 sm:px-6">Username</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Tingkat Hak</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status Keaktifan</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Wajib Ganti Sandi</th>
                            <th class="py-3.5 px-4 sm:px-6">Dibuat Pada</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @forelse($admins as $index => $item)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shadow-inner {{ $item->is_admin_utama ? 'bg-forest text-white' : 'bg-soft text-darkgreen border border-border-soft' }}">
                                            {{ strtoupper(substr($item->username, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-darkgreen block">{{ $item->username }}</span>
                                            <span class="text-[11px] text-muted-text">ID Akun: #{{ $item->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($item->is_admin_utama)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-forest text-white shadow-xs">
                                            <x-icon name="shield-check" class="w-3.5 h-3.5" />
                                            <span>Admin Utama</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                            <span>Admin Biasa</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            <span>Nonaktif</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    @if($item->wajib_ganti_password)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            <span>Ya</span>
                                        </span>
                                    @else
                                        <span class="text-muted-text text-xs">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-xs text-muted-text">
                                    {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        {{-- Tombol Reset Password --}}
                                        <button 
                                            type="button" 
                                            onclick="openResetModal('{{ $item->id }}', '{{ $item->username }}')"
                                            class="px-2.5 py-1.5 rounded-lg border border-border-soft hover:bg-soft text-darkgreen text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer"
                                            title="Reset Password Akun"
                                        >
                                            <x-icon name="key" class="w-3.5 h-3.5 text-muted-text" />
                                            <span class="hidden sm:inline">Reset Sandi</span>
                                        </button>

                                        {{-- Tombol Toggle Status (Khusus Admin Biasa) --}}
                                        @if(!$item->is_admin_utama)
                                            <form action="{{ route('admin.manajemen-admin.toggle-status', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button 
                                                    type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin mengubah status keaktifan akun {{ $item->username }}?')"
                                                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer {{ $item->is_active ? 'border border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100' : 'border border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}"
                                                    title="{{ $item->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                                >
                                                    <x-icon name="{{ $item->is_active ? 'x-mark' : 'check-circle' }}" class="w-3.5 h-3.5" />
                                                    <span>{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold text-muted-text bg-gray-50 border border-gray-200 cursor-not-allowed" title="Admin Utama Terlindungi">
                                                Terkunci
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-muted-text text-sm">
                                    Belum ada data admin tercatat di sistem.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Tambah Admin Baru -->
    <div 
        id="modal-tambah-admin" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-darkgreen/60 backdrop-blur-xs hidden"
        aria-modal="true"
        role="dialog"
    >
        <div class="bg-white rounded-3xl border border-border-soft shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95">
            <div class="px-6 py-5 border-b border-border-soft flex items-center justify-between bg-soft/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-forest/10 text-forest flex items-center justify-center">
                        <x-icon name="user-plus" class="w-4 h-4" />
                    </div>
                    <h3 class="font-bold text-darkgreen text-sm">Tambah Administrator Baru</h3>
                </div>
                <button 
                    type="button" 
                    onclick="document.getElementById('modal-tambah-admin').classList.add('hidden')"
                    class="p-1 rounded-lg text-muted-text hover:text-darkgreen hover:bg-soft transition-colors"
                >
                    <x-icon name="x-mark" class="w-4 h-4" />
                </button>
            </div>

            <form action="{{ route('admin.manajemen-admin.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1.5">
                        Username Admin <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        value="{{ old('username') }}"
                        required
                        placeholder="contoh: admin_akademik"
                        class="w-full px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                    >
                    <p class="text-[11px] text-muted-text mt-1">Hanya huruf, angka, strip, atau garis bawah (min 3 karakter).</p>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1.5">
                        Password Awal <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        placeholder="Minimal 8 karakter"
                        class="w-full px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                    >
                    <p class="text-[11px] text-muted-text mt-1">Akun baru akan otomatis diwajibkan ganti password saat login pertama kali.</p>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5">
                    <button 
                        type="button" 
                        onclick="document.getElementById('modal-tambah-admin').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs font-bold transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors cursor-pointer"
                    >
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reset Password Admin -->
    <div 
        id="modal-reset-password" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-darkgreen/60 backdrop-blur-xs hidden"
        aria-modal="true"
        role="dialog"
    >
        <div class="bg-white rounded-3xl border border-border-soft shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95">
            <div class="px-6 py-5 border-b border-border-soft flex items-center justify-between bg-soft/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                        <x-icon name="key" class="w-4 h-4" />
                    </div>
                    <h3 class="font-bold text-darkgreen text-sm">Reset Password Akun Admin</h3>
                </div>
                <button 
                    type="button" 
                    onclick="document.getElementById('modal-reset-password').classList.add('hidden')"
                    class="p-1 rounded-lg text-muted-text hover:text-darkgreen hover:bg-soft transition-colors"
                >
                    <x-icon name="x-mark" class="w-4 h-4" />
                </button>
            </div>

            <form id="form-reset-password" method="POST" action="" class="p-6 space-y-4">
                @csrf
                <div>
                    <span class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">Target Pengguna:</span>
                    <span id="reset-target-username" class="font-bold text-forest text-sm block"></span>
                </div>

                <div>
                    <label for="password_baru" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1.5">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password_baru" 
                        id="password_baru" 
                        required
                        placeholder="Minimal 8 karakter baru"
                        class="w-full px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen"
                    >
                    <p class="text-[11px] text-muted-text mt-1">Setelah direset, user akan otomatis berstatus wajib mengganti password saat login.</p>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5">
                    <button 
                        type="button" 
                        onclick="document.getElementById('modal-reset-password').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs font-bold transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors cursor-pointer"
                    >
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openResetModal(adminId, username) {
            const form = document.getElementById('form-reset-password');
            form.action = `/admin/manajemen-admin/${adminId}/reset-password`;
            document.getElementById('reset-target-username').textContent = username;
            document.getElementById('modal-reset-password').classList.remove('hidden');
        }
    </script>
    @endpush

</x-layouts.admin>
