<x-layouts.admin 
    title="Data Guru & Tendik" 
    activeMenu="master-guru"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.guru.index')],
        ['label' => 'Guru & Tendik', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Flash Message Alerts -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 shrink-0" />
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-lg font-bold">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <x-icon name="x-circle" class="w-5 h-5 text-rose-600 shrink-0" />
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 text-lg font-bold">&times;</button>
            </div>
        @endif

        <!-- Header Action Card & Quick Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-bold text-darkgreen tracking-tight">Manajemen Guru &amp; Tenaga Kependidikan</h2>
                    <p class="text-xs sm:text-sm text-muted-text mt-1">
                        Daftar pendidik resmi dan staf SMP Muhammadiyah 7 Medan yang terintegrasi dengan basis data RKM dan akun SIAKAD.
                    </p>
                </div>
                <div class="mt-4 pt-4 border-t border-border-soft/60 flex items-center justify-between">
                    <span class="text-xs font-semibold text-muted-text">Total: <strong class="text-darkgreen">{{ $stats['total'] }} Guru</strong></span>
                    <a href="{{ route('admin.master.guru.create') }}" class="px-4 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                        <x-icon name="user-plus" class="w-4 h-4" />
                        <span>Tambah Guru Baru</span>
                    </a>
                </div>
            </div>

            <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col justify-between">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Status Aktif (A)</span>
                <div class="my-2">
                    <span class="text-3xl font-extrabold text-emerald-800">{{ $stats['aktif'] }}</span>
                    <span class="text-xs text-muted-text ml-1">orang</span>
                </div>
                <span class="text-[11px] text-muted-text">Muncul di direktori publik & jadwal</span>
            </div>

            <div class="p-5 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col justify-between">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Pensiun / Cuti / Mutasi</span>
                <div class="my-2">
                    <span class="text-3xl font-extrabold text-amber-800">{{ $stats['pensiun'] + $stats['mutasi'] }}</span>
                    <span class="text-xs text-muted-text ml-1">orang</span>
                </div>
                <span class="text-[11px] text-muted-text">Pensiun: {{ $stats['pensiun'] }} | Mutasi: {{ $stats['mutasi'] }}</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-border-soft shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.master.guru.index') }}" class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="w-full sm:w-96 relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text">
                        <x-icon name="search" class="w-4 h-4 text-sage" />
                    </span>
                    <input 
                        type="text" 
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama guru atau NIP..." 
                        class="w-full pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest transition-all"
                    >
                </div>

                <div class="w-full sm:w-auto flex items-center gap-2">
                    <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest text-darkgreen font-medium">
                        <option value="">Semua Status ({{ $stats['total'] }})</option>
                        <option value="A" {{ request('status') === 'A' ? 'selected' : '' }}>Aktif Mengajar ({{ $stats['aktif'] }})</option>
                        <option value="P" {{ request('status') === 'P' ? 'selected' : '' }}>Pensiun / Cuti ({{ $stats['pensiun'] }})</option>
                        <option value="M" {{ request('status') === 'M' ? 'selected' : '' }}>Mutasi / Keluar ({{ $stats['mutasi'] }})</option>
                    </select>

                    @if(request('q') || request('status'))
                        <a href="{{ route('admin.master.guru.index') }}" class="px-3 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs flex items-center gap-1 transition-colors">
                            <x-icon name="refresh" class="w-3.5 h-3.5 text-sage" />
                            <span>Reset</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Master Data Table -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">No</th>
                            <th class="py-3.5 px-4 sm:px-6">Nama & NIP</th>
                            <th class="py-3.5 px-4 sm:px-6">Jenis Kelamin</th>
                            <th class="py-3.5 px-4 sm:px-6">Peran</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Akun Login</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft font-medium text-darkgreen">
                        @forelse($teachers as $index => $teacher)
                            <tr class="hover:bg-soft/40 transition-colors" id="row-guru-{{ $teacher->id }}">
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text">
                                    {{ $teachers->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-forest/10 text-forest font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($teacher->nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-darkgreen block">{{ $teacher->nama }}</span>
                                            <span class="text-[11px] text-muted-text">
                                                {{ $teacher->nip ? 'NIP. ' . $teacher->nip : 'NIP: (Belum diisi)' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text">
                                    {{ $teacher->label_jk }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    @if($teacher->is_bk === '1')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                            <x-icon name="academic-cap" class="w-3.5 h-3.5 text-indigo-500" />
                                            Guru BK
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-semibold">
                                            <x-icon name="book-open" class="w-3.5 h-3.5 text-emerald-600" />
                                            Guru Mapel
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span id="badge-status-{{ $teacher->id }}" class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $teacher->badge_class }}">
                                        {{ $teacher->label_status }} ({{ $teacher->stat_data }})
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center" id="col-user-{{ $teacher->id }}">
                                    @if($teacher->user)
                                        <div class="inline-flex flex-col items-center">
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold {{ $teacher->user->is_active ? 'text-emerald-700' : 'text-rose-700' }}">
                                                <x-icon name="{{ $teacher->user->is_active ? 'check-circle' : 'x-circle' }}" class="w-3.5 h-3.5 shrink-0" />
                                                <span>{{ $teacher->user->username }}</span>
                                            </span>
                                            <span class="text-[10px] text-muted-text">
                                                {{ $teacher->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    @else
                                        <button 
                                            type="button" 
                                            onclick="openBuatAkunModal({{ $teacher->id }}, '{{ addslashes($teacher->nama) }}', '{{ $teacher->nip ?? '' }}')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-forest hover:text-white border border-emerald-200 text-[11px] font-bold transition-colors"
                                            title="Buat Akun Satuan Guru"
                                        >
                                            <x-icon name="plus" class="w-3 h-3" />
                                            <span>Buat Akun</span>
                                        </button>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Edit Data -->
                                        <a href="{{ route('admin.master.guru.edit', $teacher) }}" class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-soft transition-colors" title="Edit Data Guru">
                                            <x-icon name="pencil" class="w-4 h-4" />
                                        </a>

                                        <!-- Toggle Status Aktif / Pensiun (AJAX) -->
                                        <button 
                                            type="button" 
                                            onclick="toggleGuruStatus({{ $teacher->id }}, '{{ addslashes($teacher->nama) }}')"
                                            class="p-1.5 rounded-lg text-muted-text hover:text-amber-600 hover:bg-amber-50 transition-colors" 
                                            title="Ubah Status Aktif/Pensiun"
                                        >
                                            <x-icon name="refresh" class="w-4 h-4" />
                                        </button>

                                        <!-- Reset Password / Buat Akun Modal Trigger -->
                                        @if($teacher->user)
                                            <button 
                                                type="button" 
                                                onclick="openResetModal({{ $teacher->id }}, '{{ addslashes($teacher->nama) }}', '{{ $teacher->user->username ?? '' }}')"
                                                class="p-1.5 rounded-lg text-muted-text hover:text-indigo-600 hover:bg-indigo-50 transition-colors" 
                                                title="Reset Password Guru"
                                            >
                                                <x-icon name="key" class="w-4 h-4" />
                                            </button>
                                        @else
                                            <button 
                                                type="button" 
                                                onclick="openBuatAkunModal({{ $teacher->id }}, '{{ addslashes($teacher->nama) }}', '{{ $teacher->nip ?? '' }}')"
                                                class="p-1.5 rounded-lg text-muted-text hover:text-forest hover:bg-forest/10 transition-colors" 
                                                title="Buat Akun Login Guru"
                                            >
                                                <x-icon name="user-plus" class="w-4 h-4" />
                                            </button>
                                        @endif

                                        <!-- Soft Delete (Mutasi / Nonaktif Tetap) -->
                                        <form action="{{ route('admin.master.guru.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Nonaktifkan guru {{ addslashes($teacher->nama) }} (Status Mutasi/Keluar)? Akun login akan dinonaktifkan.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-muted-text hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Nonaktifkan (Mutasi/Keluar)">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-muted-text">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <x-icon name="users" class="w-8 h-8 text-sage/40" />
                                        <p class="font-semibold">Tidak ada data guru yang sesuai.</p>
                                        <p class="text-xs">Coba ubah kata kunci pencarian atau opsi filter status.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination & Count -->
            <div class="p-4 sm:px-6 border-t border-border-soft bg-soft/30 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-muted-text">
                <div>
                    Menampilkan <strong>{{ $teachers->firstItem() ?? 0 }} - {{ $teachers->lastItem() ?? 0 }}</strong> dari <strong>{{ $teachers->total() }}</strong> data guru
                </div>
                <div>
                    {{ $teachers->links() }}
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Buat Akun Satuan Guru (AKN-1) -->
    <div id="buatAkunModal" class="fixed inset-0 z-50 hidden bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft relative animate-in fade-in duration-150">
            <h3 class="text-base font-bold text-darkgreen" id="modalBuatAkunTitle">Buat Akun Satuan Guru</h3>
            <p class="text-xs text-muted-text mt-1">
                Tentukan username dan password awal login untuk guru ini.
            </p>

            <form id="buatAkunForm" onsubmit="submitBuatAkun(event)" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" id="buatAkunGuruId" value="">

                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                        Username Login
                    </label>
                    <input 
                        type="text" 
                        id="buatAkunUsernameInput" 
                        placeholder="Contoh: nip_guru / nama_guru" 
                        class="w-full px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest font-mono"
                    >
                    <span class="text-[11px] text-muted-text block mt-1">Biarkan kosong untuk otomatis menggunakan format rekomendasi unik.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                        Password Awal (Sementara)
                    </label>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            id="buatAkunPasswordInput" 
                            placeholder="Biarkan kosong untuk password acak..." 
                            class="flex-1 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest font-mono"
                        >
                        <button type="button" onclick="generateRandomGuruPassword()" class="px-3 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-xs font-bold text-darkgreen transition-colors">
                            Acak
                        </button>
                    </div>
                    <span class="text-[11px] text-muted-text block mt-1">Password awal ter-hash dan guru wajib ganti saat pertama login.</span>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" onclick="closeBuatAkunModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-muted-text hover:bg-soft transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitBuatAkun" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                        Buat Akun Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Kredensial Berhasil Dibuat -->
    <div id="modalKredensialGuru" class="fixed inset-0 z-50 hidden bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft relative animate-in fade-in duration-150 space-y-4">
            <div class="flex items-center gap-2.5 text-emerald-700">
                <x-icon name="check-circle" class="w-6 h-6 shrink-0" />
                <h3 class="text-base font-bold text-darkgreen">Akun Guru Berhasil Dibuat</h3>
            </div>
            <p class="text-xs text-muted-text">
                Salin dan berikan kredensial sementara ini kepada guru bersangkutan. Password hanya ditampilkan satu kali ini.
            </p>

            <div class="p-4 rounded-2xl bg-soft border border-border-soft space-y-2.5 text-xs font-mono">
                <div class="flex justify-between items-center">
                    <span class="text-muted-text font-sans">Username:</span>
                    <strong class="text-darkgreen text-sm" id="resultGuruUsername">-</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-muted-text font-sans">Password Sementara:</span>
                    <strong class="text-forest text-sm font-bold" id="resultGuruPassword">-</strong>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="copyGuruCredentials()" class="px-4 py-2 rounded-xl bg-soft border border-border-soft hover:bg-forest/10 text-xs font-bold text-darkgreen transition-colors">
                    Salin Teks
                </button>
                <button type="button" onclick="closeKredensialGuruModal()" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                    Selesai
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password / Buat Akun -->
    <div id="resetPasswordModal" class="fixed inset-0 z-50 hidden bg-darkgreen/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl border border-border-soft relative animate-in fade-in duration-150">
            <h3 class="text-base font-bold text-darkgreen" id="modalTitle">Atur Kata Sandi Guru</h3>
            <p class="text-xs text-muted-text mt-1" id="modalSubtitle">
                Masukkan password baru untuk akun guru ini.
            </p>

            <form id="resetPasswordForm" onsubmit="submitResetPassword(event)" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" id="resetGuruId" value="">

                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="newPasswordInput" 
                        required 
                        minlength="6"
                        placeholder="Minimal 6 karakter..." 
                        class="w-full px-3.5 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                    >
                    <span class="text-[11px] text-muted-text block mt-1">Guru diwajibkan mengganti kata sandi ini saat login perdana.</span>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" onclick="closeResetModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-muted-text hover:bg-soft transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitReset" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs font-bold shadow-xs transition-colors">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script AJAX untuk Toggle Status & Reset Password -->
    <script>
        const csrfToken = '{{ csrf_token() }}';

        async function toggleGuruStatus(guruId, guruNama) {
            if (!confirm(`Ubah status keaktifan guru "${guruNama}"?`)) return;

            try {
                const response = await fetch(`/admin/master/guru/${guruId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    const badge = document.getElementById(`badge-status-${guruId}`);
                    if (badge) {
                        badge.className = `px-2.5 py-0.5 rounded-full text-[11px] font-semibold border ${data.badgeClass}`;
                        badge.textContent = `${data.label} (${data.status})`;
                    }
                    location.reload(); // Reload agar metrik atas dan akun status terbarui
                } else {
                    alert(data.message || 'Gagal mengubah status guru.');
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
            }
        }

        function openBuatAkunModal(guruId, guruNama, nip) {
            document.getElementById('buatAkunGuruId').value = guruId;
            document.getElementById('modalBuatAkunTitle').textContent = `Buat Akun Guru: ${guruNama}`;
            document.getElementById('buatAkunUsernameInput').value = nip ? nip : '';
            document.getElementById('buatAkunPasswordInput').value = '';
            document.getElementById('buatAkunModal').classList.remove('hidden');
        }

        function closeBuatAkunModal() {
            document.getElementById('buatAkunModal').classList.add('hidden');
        }

        function generateRandomGuruPassword() {
            const randomStr = Math.random().toString(36).substring(2, 7).toUpperCase();
            document.getElementById('buatAkunPasswordInput').value = `Guru7-${randomStr}`;
        }

        async function submitBuatAkun(e) {
            e.preventDefault();
            const guruId = document.getElementById('buatAkunGuruId').value;
            const username = document.getElementById('buatAkunUsernameInput').value;
            const password = document.getElementById('buatAkunPasswordInput').value;
            const btn = document.getElementById('btnSubmitBuatAkun');

            btn.disabled = true;
            btn.textContent = 'Membuat Akun...';

            try {
                const response = await fetch(`/admin/master/guru/${guruId}/buat-akun`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username: username ? username : null,
                        password: password ? password : null
                    })
                });

                const data = await response.json();
                if (data.success) {
                    closeBuatAkunModal();
                    document.getElementById('resultGuruUsername').textContent = data.username;
                    document.getElementById('resultGuruPassword').textContent = data.password_baru;
                    document.getElementById('modalKredensialGuru').classList.remove('hidden');
                } else {
                    alert(data.message || 'Gagal membuat akun guru.');
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Buat Akun Sekarang';
            }
        }

        function copyGuruCredentials() {
            const u = document.getElementById('resultGuruUsername').textContent;
            const p = document.getElementById('resultGuruPassword').textContent;
            navigator.clipboard.writeText(`Username: ${u}\nPassword: ${p}`);
            alert('Kredensial berhasil disalin ke clipboard!');
        }

        function closeKredensialGuruModal() {
            document.getElementById('modalKredensialGuru').classList.add('hidden');
            location.reload();
        }

        function openResetModal(guruId, guruNama, username) {
            document.getElementById('resetGuruId').value = guruId;
            document.getElementById('modalTitle').textContent = `Kata Sandi Guru: ${guruNama}`;
            document.getElementById('modalSubtitle').textContent = username 
                ? `Akun: ${username}. Password baru akan menggantikan yang lama.` 
                : 'Guru ini belum memiliki akun. Sistem akan otomatis membuatkan akun login baru.';
            document.getElementById('newPasswordInput').value = '';
            document.getElementById('resetPasswordModal').classList.remove('hidden');
            document.getElementById('newPasswordInput').focus();
        }

        function closeResetModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }

        async function submitResetPassword(e) {
            e.preventDefault();
            const guruId = document.getElementById('resetGuruId').value;
            const password = document.getElementById('newPasswordInput').value;
            const btn = document.getElementById('btnSubmitReset');

            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            try {
                const response = await fetch(`/admin/master/guru/${guruId}/reset-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password_baru: password })
                });

                const data = await response.json();
                if (data.success) {
                    alert(data.message);
                    closeResetModal();
                    location.reload();
                } else {
                    alert(data.message || 'Gagal memperbarui kata sandi.');
                }
            } catch (err) {
                alert('Terjadi kesalahan koneksi.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan Password';
            }
        }
    </script>
</x-layouts.admin>
