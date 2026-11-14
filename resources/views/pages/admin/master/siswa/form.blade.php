@php
    $isEdit = isset($siswa);
    $title = $isEdit ? 'Edit Data Siswa' : 'Tambah Siswa Baru';
@endphp

<x-layouts.admin 
    :title="$title" 
    activeMenu="master-siswa"
    :breadcrumbs="[
        ['label' => 'Data Master', 'url' => route('admin.master.siswa.index')],
        ['label' => 'Data Siswa', 'url' => route('admin.master.siswa.index')],
        ['label' => $title, 'url' => ''],
    ]"
>
    <div class="max-w-5xl space-y-6">

        <!-- Header -->
        <div class="p-6 rounded-3xl bg-white border border-border-soft shadow-xs flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkgreen tracking-tight">{{ $title }}</h2>
                <p class="text-xs sm:text-sm text-muted-text mt-1">
                    Form terstruktur 3 tab (Identitas, Sekolah Asal, Orang Tua/Wali). Sesuai PRD SIS-2, <strong>hanya NIS dan Nama yang wajib diisi</strong>.
                </p>
            </div>
            <a href="{{ route('admin.master.siswa.index') }}" class="px-4 py-2 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs sm:text-sm font-semibold flex items-center gap-1.5 transition-colors">
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

        <!-- Form Card dengan Tab Navigation -->
        <form 
            action="{{ $isEdit ? route('admin.master.siswa.update', $siswa) : route('admin.master.siswa.store') }}" 
            method="POST" 
            class="p-6 sm:p-8 rounded-3xl bg-white border border-border-soft shadow-xs space-y-8"
        >
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <!-- 3 Tabs Navigation Buttons -->
            <div class="border-b border-border-soft">
                <nav class="flex space-x-2 sm:space-x-4" aria-label="Tabs">
                    <button 
                        type="button" 
                        onclick="switchTab('identitas')" 
                        id="tab-btn-identitas"
                        class="tab-btn px-4 py-2.5 rounded-t-xl text-xs sm:text-sm font-bold border-b-2 border-forest text-forest bg-soft/50 transition-colors"
                    >
                        1. Identitas Pribadi
                    </button>
                    <button 
                        type="button" 
                        onclick="switchTab('sekolah')" 
                        id="tab-btn-sekolah"
                        class="tab-btn px-4 py-2.5 rounded-t-xl text-xs sm:text-sm font-medium text-muted-text hover:text-darkgreen hover:bg-soft/30 transition-colors"
                    >
                        2. Sekolah Asal & Penerimaan
                    </button>
                    <button 
                        type="button" 
                        onclick="switchTab('ortu')" 
                        id="tab-btn-ortu"
                        class="tab-btn px-4 py-2.5 rounded-t-xl text-xs sm:text-sm font-medium text-muted-text hover:text-darkgreen hover:bg-soft/30 transition-colors"
                    >
                        3. Orang Tua & Wali
                    </button>
                </nav>
            </div>

            <!-- TAB 1: IDENTITAS PRIBADI -->
            <div id="tab-content-identitas" class="tab-pane space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- NIS (Wajib) -->
                    <div>
                        <label for="nis" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nis" 
                            name="nis" 
                            value="{{ old('nis', $siswa->nis ?? '') }}" 
                            required 
                            maxlength="10"
                            placeholder="Contoh: 3901" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest @error('nis') border-rose-400 @enderror"
                        >
                        <span class="text-[11px] text-muted-text block mt-1">Maksimal 10 karakter. Digunakan sebagai username login.</span>
                    </div>

                    <!-- Nama Lengkap (Wajib) -->
                    <div>
                        <label for="nama" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Nama Lengkap Siswa <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            value="{{ old('nama', $siswa->nama ?? '') }}" 
                            required 
                            maxlength="100"
                            placeholder="Contoh: Muhammad Farhan" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest @error('nama') border-rose-400 @enderror"
                        >
                    </div>

                    <!-- NISN -->
                    <div>
                        <label for="nisn" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            NISN (Opsional)
                        </label>
                        <input 
                            type="text" 
                            id="nisn" 
                            name="nisn" 
                            value="{{ old('nisn', ($siswa->nisn ?? '0') === '0' ? '' : ($siswa->nisn ?? '')) }}" 
                            maxlength="10"
                            placeholder="10 digit nomor NISN nasional" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jk" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Jenis Kelamin
                        </label>
                        <select 
                            id="jk" 
                            name="jk" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                            <option value="L" {{ old('jk', $siswa->jk ?? 'L') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('jk', $siswa->jk ?? 'L') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tmp_lahir" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Tempat Lahir
                        </label>
                        <input 
                            type="text" 
                            id="tmp_lahir" 
                            name="tmp_lahir" 
                            value="{{ old('tmp_lahir', $siswa->tmp_lahir ?? '') }}" 
                            maxlength="50"
                            placeholder="Kota / Kabupaten kelahiran" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tgl_lahir" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Tanggal Lahir
                        </label>
                        <input 
                            type="date" 
                            id="tgl_lahir" 
                            name="tgl_lahir" 
                            value="{{ old('tgl_lahir', isset($siswa) ? $siswa->getFormattedDate('tgl_lahir') : '') }}" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Agama -->
                    <div>
                        <label for="agama" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Agama
                        </label>
                        <select 
                            id="agama" 
                            name="agama" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                            @php $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']; @endphp
                            @foreach($agamas as $ag)
                                <option value="{{ $ag }}" {{ old('agama', $siswa->agama ?? 'Islam') === $ag ? 'selected' : '' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status dalam Keluarga & Anak Ke -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="status" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                Status Keluarga
                            </label>
                            <input 
                                type="text" 
                                id="status" 
                                name="status" 
                                value="{{ old('status', $siswa->status ?? 'AK') }}" 
                                maxlength="2"
                                placeholder="AK (Anak Kandung)" 
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                        </div>
                        <div>
                            <label for="anakke" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                Anak Ke-
                            </label>
                            <input 
                                type="number" 
                                id="anakke" 
                                name="anakke" 
                                value="{{ old('anakke', $siswa->anakke ?? 1) }}" 
                                min="1" 
                                max="99" 
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                        </div>
                    </div>

                    <!-- Alamat Rumah -->
                    <div class="sm:col-span-2">
                        <label for="alamat" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Alamat Tempat Tinggal
                        </label>
                        <input 
                            type="text" 
                            id="alamat" 
                            name="alamat" 
                            value="{{ old('alamat', $siswa->alamat ?? '') }}" 
                            maxlength="50"
                            placeholder="Alamat jalan, nomor, RT/RW, kelurahan" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- No Telp Siswa -->
                    <div>
                        <label for="notelp" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            No. Telepon / HP Siswa
                        </label>
                        <input 
                            type="text" 
                            id="notelp" 
                            name="notelp" 
                            value="{{ old('notelp', $siswa->notelp ?? '') }}" 
                            maxlength="13"
                            placeholder="Contoh: 081234567890" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    @if($isEdit)
                        <!-- Status Keaktifan Data Siswa -->
                        <div>
                            <label for="stat_data" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                Status Data Siswa
                            </label>
                            <select 
                                id="stat_data" 
                                name="stat_data" 
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                                <option value="A" {{ old('stat_data', $siswa->stat_data) === 'A' ? 'selected' : '' }}>Aktif (A)</option>
                                <option value="K" {{ old('stat_data', $siswa->stat_data) === 'K' ? 'selected' : '' }}>Keluar (K)</option>
                                <option value="M" {{ old('stat_data', $siswa->stat_data) === 'M' ? 'selected' : '' }}>Mutasi (M)</option>
                                <option value="L" {{ old('stat_data', $siswa->stat_data) === 'L' ? 'selected' : '' }}>Lulus (L)</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <!-- TAB 2: SEKOLAH ASAL & PENERIMAAN -->
            <div id="tab-content-sekolah" class="tab-pane hidden space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Sekolah Asal -->
                    <div>
                        <label for="sek_asal" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Nama Sekolah Asal (SD/MI)
                        </label>
                        <input 
                            type="text" 
                            id="sek_asal" 
                            name="sek_asal" 
                            value="{{ old('sek_asal', $siswa->sek_asal ?? '') }}" 
                            maxlength="30"
                            placeholder="Contoh: SD Muhammadiyah 01 Medan" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Alamat Sekolah Asal -->
                    <div>
                        <label for="sek_asal_alamat" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Alamat Sekolah Asal
                        </label>
                        <input 
                            type="text" 
                            id="sek_asal_alamat" 
                            name="sek_asal_alamat" 
                            value="{{ old('sek_asal_alamat', $siswa->sek_asal_alamat ?? '') }}" 
                            maxlength="50"
                            placeholder="Kota / Jalan sekolah asal" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Diterima di Kelas & Semester -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="diterima_kelas" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                Diterima di Kelas
                            </label>
                            <input 
                                type="text" 
                                id="diterima_kelas" 
                                name="diterima_kelas" 
                                value="{{ old('diterima_kelas', $siswa->diterima_kelas ?? '7') }}" 
                                maxlength="5"
                                placeholder="Contoh: 7" 
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                        </div>
                        <div>
                            <label for="diterima_smt" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                Semester
                            </label>
                            <input 
                                type="text" 
                                id="diterima_smt" 
                                name="diterima_smt" 
                                value="{{ old('diterima_smt', $siswa->diterima_smt ?? '1') }}" 
                                maxlength="2"
                                placeholder="1" 
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                        </div>
                    </div>

                    <!-- Tanggal Diterima -->
                    <div>
                        <label for="diterima_tgl" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Tanggal Diterima
                        </label>
                        <input 
                            type="date" 
                            id="diterima_tgl" 
                            name="diterima_tgl" 
                            value="{{ old('diterima_tgl', isset($siswa) ? $siswa->getFormattedDate('diterima_tgl') : '') }}" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Ijazah SD No & Tahun -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2">
                            <label for="ijazah_no" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                No. Ijazah SD/MI
                            </label>
                            <input 
                                type="text" 
                                id="ijazah_no" 
                                name="ijazah_no" 
                                value="{{ old('ijazah_no', $siswa->ijazah_no ?? '') }}" 
                                maxlength="50"
                                placeholder="DN-07/D-SD/..." 
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                        </div>
                        <div>
                            <label for="ijazah_thn" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                Tahun
                            </label>
                            <input 
                                type="text" 
                                id="ijazah_thn" 
                                name="ijazah_thn" 
                                value="{{ old('ijazah_thn', $siswa->ijazah_thn ?? '') }}" 
                                maxlength="4"
                                placeholder="2024" 
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                        </div>
                    </div>

                    <!-- SKHUN No & Tahun -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2">
                            <label for="skhun_no" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                No. SKHUN (Jika Ada)
                            </label>
                            <input 
                                type="text" 
                                id="skhun_no" 
                                name="skhun_no" 
                                value="{{ old('skhun_no', $siswa->skhun_no ?? '') }}" 
                                maxlength="50"
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                        </div>
                        <div>
                            <label for="skhun_thn" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                                Tahun
                            </label>
                            <input 
                                type="text" 
                                id="skhun_thn" 
                                name="skhun_thn" 
                                value="{{ old('skhun_thn', $siswa->skhun_thn ?? '') }}" 
                                maxlength="4"
                                placeholder="2024" 
                                class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ORANG TUA & WALI -->
            <div id="tab-content-ortu" class="tab-pane hidden space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Ayah & Pekerjaan -->
                    <div>
                        <label for="ortu_ayah" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Nama Ayah Kandung
                        </label>
                        <input 
                            type="text" 
                            id="ortu_ayah" 
                            name="ortu_ayah" 
                            value="{{ old('ortu_ayah', $siswa->ortu_ayah ?? '') }}" 
                            maxlength="50"
                            placeholder="Nama lengkap ayah" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>
                    <div>
                        <label for="ortu_ayah_pkj" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Pekerjaan Ayah
                        </label>
                        <input 
                            type="text" 
                            id="ortu_ayah_pkj" 
                            name="ortu_ayah_pkj" 
                            value="{{ old('ortu_ayah_pkj', $siswa->ortu_ayah_pkj ?? '') }}" 
                            maxlength="30"
                            placeholder="PNS / Swasta / Wiraswasta" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Ibu & Pekerjaan -->
                    <div>
                        <label for="ortu_ibu" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Nama Ibu Kandung
                        </label>
                        <input 
                            type="text" 
                            id="ortu_ibu" 
                            name="ortu_ibu" 
                            value="{{ old('ortu_ibu', $siswa->ortu_ibu ?? '') }}" 
                            maxlength="50"
                            placeholder="Nama lengkap ibu" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>
                    <div>
                        <label for="ortu_ibu_pkj" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Pekerjaan Ibu
                        </label>
                        <input 
                            type="text" 
                            id="ortu_ibu_pkj" 
                            name="ortu_ibu_pkj" 
                            value="{{ old('ortu_ibu_pkj', $siswa->ortu_ibu_pkj ?? '') }}" 
                            maxlength="30"
                            placeholder="Ibu Rumah Tangga / PNS / Swasta" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Alamat & Telp Orang Tua -->
                    <div>
                        <label for="ortu_alamat" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Alamat Orang Tua
                        </label>
                        <input 
                            type="text" 
                            id="ortu_alamat" 
                            name="ortu_alamat" 
                            value="{{ old('ortu_alamat', $siswa->ortu_alamat ?? '') }}" 
                            maxlength="50"
                            placeholder="Sama dengan alamat siswa atau tulis lengkap" 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>
                    <div>
                        <label for="ortu_notelp" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            No. Telepon / WhatsApp Orang Tua
                        </label>
                        <input 
                            type="text" 
                            id="ortu_notelp" 
                            name="ortu_notelp" 
                            value="{{ old('ortu_notelp', $siswa->ortu_notelp ?? '') }}" 
                            maxlength="13"
                            placeholder="0812..." 
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <!-- Wali & Pekerjaan (Opsional) -->
                    <div class="sm:col-span-2 pt-2 border-t border-border-soft">
                        <span class="text-[11px] font-bold text-muted-text uppercase tracking-wider block mb-2">Informasi Wali (Jika Ada)</span>
                    </div>

                    <div>
                        <label for="wali" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Nama Wali
                        </label>
                        <input 
                            type="text" 
                            id="wali" 
                            name="wali" 
                            value="{{ old('wali', $siswa->wali ?? '') }}" 
                            maxlength="20"
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>
                    <div>
                        <label for="wali_pkj" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Pekerjaan Wali
                        </label>
                        <input 
                            type="text" 
                            id="wali_pkj" 
                            name="wali_pkj" 
                            value="{{ old('wali_pkj', $siswa->wali_pkj ?? '') }}" 
                            maxlength="13"
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>

                    <div>
                        <label for="wali_alamat" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            Alamat Wali
                        </label>
                        <input 
                            type="text" 
                            id="wali_alamat" 
                            name="wali_alamat" 
                            value="{{ old('wali_alamat', $siswa->wali_alamat ?? '') }}" 
                            maxlength="50"
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>
                    <div>
                        <label for="notelp_rumah" class="block text-xs font-bold text-darkgreen uppercase tracking-wider mb-1">
                            No. Telepon Rumah / Wali
                        </label>
                        <input 
                            type="text" 
                            id="notelp_rumah" 
                            name="notelp_rumah" 
                            value="{{ old('notelp_rumah', $siswa->notelp_rumah ?? '') }}" 
                            maxlength="13"
                            class="w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm bg-soft border border-border-soft focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-forest"
                        >
                    </div>
                </div>
            </div>

            @if(!$isEdit)
                <!-- Pembuatan Akun Login Otomatis -->
                <div class="pt-4 border-t border-border-soft">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="buat_akun" 
                            value="1" 
                            {{ old('buat_akun') ? 'checked' : '' }} 
                            class="mt-1 w-4 h-4 text-forest rounded-lg border-border-soft focus:ring-forest"
                        >
                        <div>
                            <span class="text-xs sm:text-sm font-bold text-darkgreen block">
                                Buat Akun Login SIAKAD Sekaligus (Username = NIS)
                            </span>
                            <span class="text-[11px] text-muted-text block">
                                Akun dibuat dengan password sementara <code class="bg-soft px-1.5 py-0.5 rounded text-forest font-bold">Siswa@[NIS]</code> dan ditandai wajib ganti password pada login pertama.
                            </span>
                        </div>
                    </label>
                </div>
            @endif

            <!-- Submit Button Bar -->
            <div class="pt-6 border-t border-border-soft flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.master.siswa.index') }}" 
                    class="px-5 py-2.5 rounded-xl border border-border-soft hover:bg-soft text-muted-text text-xs sm:text-sm font-semibold transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-forest hover:bg-forest/90 text-white text-xs sm:text-sm font-bold shadow-xs transition-colors"
                >
                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data Siswa' }}
                </button>
            </div>
        </form>

    </div>

    @push('scripts')
    <script>
        function switchTab(tabName) {
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            
            // Remove active classes on buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-b-2', 'border-forest', 'text-forest', 'bg-soft/50');
                btn.classList.add('text-muted-text');
            });

            // Show current tab
            const target = document.getElementById('tab-content-' + tabName);
            if (target) target.classList.remove('hidden');

            // Highlight active button
            const activeBtn = document.getElementById('tab-btn-' + tabName);
            if (activeBtn) {
                activeBtn.classList.remove('text-muted-text');
                activeBtn.classList.add('border-b-2', 'border-forest', 'text-forest', 'bg-soft/50');
            }
        }
    </script>
    @endpush
</x-layouts.admin>
