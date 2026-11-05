<x-layouts.admin 
    title="Reset Password Pengguna" 
    activeMenu="sistem-reset-password"
    :breadcrumbs="[
        ['label' => 'Akun & Sistem', 'url' => route('admin.sistem.pengguna')],
        ['label' => 'Reset Password', 'url' => ''],
    ]"
>
    <div class="space-y-6">

        <!-- Header Action Card -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">Pusat Reset Kredensial & Pemulihan Akun</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Bantu pemulihan akses akun siswa, guru, atau staff yang lupa kata sandi dengan mengembalikan ke format default atau password acak.
                </p>
            </div>
        </div>

        <!-- Security Warning Alert -->
        <div class="p-5 rounded-3xl bg-amber-50/70 border border-amber-200/80 text-amber-900 text-xs flex items-start gap-3">
            <x-icon name="shield-check" class="w-5 h-5 text-amber-700 mt-0.5 shrink-0" />
            <div class="space-y-1">
                <span class="font-bold text-sm block">Protokol Keamanan Password SIAKAD</span>
                <p class="text-amber-800 leading-relaxed">
                    Setiap aksi reset akan otomatis mengaktifkan status <code>must_change_password = true</code>. Pengguna diwajibkan membuat password baru secara mandiri saat berhasil melakukan login pertama kali setelah reset.
                </p>
            </div>
        </div>

        <!-- Reset Form Card -->
        <div class="p-6 sm:p-8 rounded-3xl bg-white border border-border-soft shadow-xs space-y-6">
            <div class="max-w-2xl space-y-5">
                <h3 class="text-base font-bold text-darkgreen">Formulir Reset Password Cepat</h3>

                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">1. Masukkan Username / NIP / NISN Akun</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <x-icon name="magnifying-glass" class="w-4 h-4 text-muted-text absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input type="text" placeholder="Contoh: 0098231001 atau 19750814..." class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-border-soft bg-white text-xs sm:text-sm text-darkgreen font-medium focus:ring-2 focus:ring-mint focus:outline-hidden">
                        </div>
                        <button type="button" class="px-4 py-2.5 rounded-xl bg-soft hover:bg-border-soft text-darkgreen text-xs font-bold transition-colors">
                            Cek Akun
                        </button>
                    </div>
                    <span class="text-[11px] text-muted-text mt-1 block">Ketik nomor identitas akun yang ingin direset.</span>
                </div>

                <!-- Account Result Card (Preview) -->
                <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-forest text-white font-bold flex items-center justify-center shrink-0">
                            A
                        </div>
                        <div>
                            <div class="font-bold text-sm text-darkgreen">Aditya Pratama Putra</div>
                            <div class="text-xs text-muted-text font-mono">NISN: 0098231001 • Siswa Kelas VII-1</div>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">
                        Siswa
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-2">2. Pilih Metode Reset</label>
                    <div class="space-y-2.5">
                        <label class="flex items-start gap-3 p-3.5 rounded-2xl border border-border-soft hover:bg-soft/50 cursor-pointer transition-colors">
                            <input type="radio" name="reset_method" value="default" checked class="mt-1 text-forest focus:ring-mint">
                            <div>
                                <span class="font-bold text-xs sm:text-sm text-darkgreen block">Reset ke Default Sistem (Direkomendasikan)</span>
                                <span class="text-xs text-muted-text">Password akan dikembalikan ke format standar: <code>smpm7-{nisn}</code> untuk siswa atau <code>guru-{nip}</code> untuk guru.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3.5 rounded-2xl border border-border-soft hover:bg-soft/50 cursor-pointer transition-colors">
                            <input type="radio" name="reset_method" value="manual" class="mt-1 text-forest focus:ring-mint">
                            <div>
                                <span class="font-bold text-xs sm:text-sm text-darkgreen block">Tentukan Password Sementara Manual</span>
                                <span class="text-xs text-muted-text">Tetapkan kata sandi acak sementara yang akan diserahkan langsung kepada pemilik akun.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" class="px-6 py-3 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs flex items-center gap-2 transition-colors">
                        <x-icon name="key" class="w-4 h-4" />
                        <span>Eksekusi Reset Kata Sandi</span>
                    </button>
                </div>
            </div>
        </div>

        @php
            $recentResets = [
                ['name' => 'Dimas Arya Saputra', 'role' => 'Siswa (VII-1)', 'admin' => 'Administrator', 'waktu' => '5 Nov 2026 14:10', 'status' => 'Berhasil'],
                ['name' => 'Bilal Ramadhan', 'role' => 'Siswa (VII-1)', 'admin' => 'Administrator', 'waktu' => '3 Nov 2026 09:25', 'status' => 'Berhasil'],
                ['name' => 'Sarah Wijaya, S.Pd', 'role' => 'Guru', 'admin' => 'Administrator', 'waktu' => '28 Okt 2026 11:40', 'status' => 'Berhasil'],
            ];
        @endphp

        <!-- Recent Reset History -->
        <div class="bg-white rounded-3xl border border-border-soft shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-border-soft flex items-center justify-between">
                <h3 class="font-bold text-darkgreen text-sm sm:text-base">Riwayat Reset Password Terakhir</h3>
                <span class="text-xs text-muted-text">Audit Log Keamanan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm divide-y divide-border-soft">
                    <thead class="bg-soft/75 text-darkgreen font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Nama Pengguna</th>
                            <th class="py-3.5 px-4 sm:px-6">Peran</th>
                            <th class="py-3.5 px-4 sm:px-6">Dieksekusi Oleh</th>
                            <th class="py-3.5 px-4 sm:px-6">Waktu Eksekusi</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach($recentResets as $r)
                            <tr class="hover:bg-soft/40 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-bold text-darkgreen">
                                    {{ $r['name'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text">
                                    {{ $r['role'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text font-medium">
                                    {{ $r['admin'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-muted-text font-mono text-xs">
                                    {{ $r['waktu'] }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                        {{ $r['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.admin>