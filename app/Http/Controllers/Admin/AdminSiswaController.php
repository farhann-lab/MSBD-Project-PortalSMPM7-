<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminSiswaController extends Controller
{
    /**
     * Tampilkan daftar siswa dengan pagination, pencarian, dan filter status/kelas.
     */
    public function index(Request $request): View
{
    // 'all' = tampilkan semua data siswa tanpa filter periode
    $taInput  = $request->input('ta', '2026');
    $filterTa = $taInput === 'all' ? null : $taInput;
    $idKelas  = $request->input('kelas');

    $query = Siswa::query()
        ->with(['user', 'penempatanKelas.kelas'])
        ->penempatan($filterTa, $idKelas);

    if ($request->filled('q')) {
        $query->search(trim($request->q));
    }

    $students = $query->orderBy('nama')->paginate(20)->withQueryString();

    // Statistik mengikuti filter periode/kelas yang sedang aktif
    $base = fn () => Siswa::penempatan($filterTa, $idKelas);

    $stats = [
        'total'  => $base()->count(),
        'aktif'  => $base()->where('stat_data', 'A')->count(),
        'keluar' => $base()->where('stat_data', 'K')->count(),
        'mutasi' => $base()->where('stat_data', 'M')->count(),
        'lulus'  => $base()->where('stat_data', 'L')->count(),
    ];

    $daftarTa    = KelasSiswa::select('ta')->distinct()->orderByDesc('ta')->pluck('ta');
    $daftarKelas = Kelas::orderBy('nama')->get();

    return view('pages.admin.master.siswa.index', compact(
        'students', 'stats', 'daftarTa', 'daftarKelas', 'taInput', 'filterTa'
    ));
}

    /**
     * Tampilkan formulir penambahan data siswa baru (3 Tab).
     */
    public function create(): View
    {
        return view('pages.admin.master.siswa.form');
    }

    /**
     * Simpan data siswa baru ke tabel m_siswa.
     */
    public function store(Request $request): RedirectResponse
    {
        // PRD & DESIGN.md: Hanya NIS dan Nama yang berstatus wajib
        $validated = $request->validate([
            'nis'             => ['required', 'string', 'max:10', 'unique:m_siswa,nis'],
            'nama'            => ['required', 'string', 'max:100'],
            'nisn'            => ['nullable', 'string', 'max:10'],
            'jk'              => ['nullable', Rule::in(['L', 'P'])],
            'tmp_lahir'       => ['nullable', 'string', 'max:50'],
            'tgl_lahir'       => ['nullable', 'date'],
            'agama'           => ['nullable', 'string', 'max:10'],
            'status'          => ['nullable', 'string', 'max:2'],
            'anakke'          => ['nullable', 'integer', 'min:1', 'max:99'],
            'alamat'          => ['nullable', 'string', 'max:50'],
            'notelp'          => ['nullable', 'string', 'max:13'],
            // Sekolah Asal
            'sek_asal'        => ['nullable', 'string', 'max:30'],
            'sek_asal_alamat' => ['nullable', 'string', 'max:50'],
            'diterima_kelas'  => ['nullable', 'string', 'max:5'],
            'diterima_tgl'    => ['nullable', 'date'],
            'diterima_smt'    => ['nullable', 'string', 'max:2'],
            'ijazah_no'       => ['nullable', 'string', 'max:50'],
            'ijazah_thn'      => ['nullable', 'string', 'max:4'],
            'skhun_no'        => ['nullable', 'string', 'max:50'],
            'skhun_thn'       => ['nullable', 'string', 'max:4'],
            // Orang Tua / Wali
            'ortu_ayah'       => ['nullable', 'string', 'max:50'],
            'ortu_ibu'        => ['nullable', 'string', 'max:50'],
            'ortu_alamat'     => ['nullable', 'string', 'max:50'],
            'ortu_notelp'     => ['nullable', 'string', 'max:13'],
            'ortu_ayah_pkj'   => ['nullable', 'string', 'max:30'],
            'ortu_ibu_pkj'    => ['nullable', 'string', 'max:30'],
            'wali'            => ['nullable', 'string', 'max:20'],
            'wali_alamat'     => ['nullable', 'string', 'max:50'],
            'notelp_rumah'    => ['nullable', 'string', 'max:13'],
            'wali_pkj'        => ['nullable', 'string', 'max:13'],
            // Akun login opsional
            'buat_akun'       => ['nullable', 'boolean'],
        ]);

        DB::beginTransaction();
        try {
            // Persiapkan data kompatibel RKM (NOT NULL string kosong / default)
            $data = [
                'nis'             => $validated['nis'],
                'nama'            => $validated['nama'],
                'nisn'            => $validated['nisn'] ?? '0',
                'jk'              => $validated['jk'] ?? 'L',
                'tmp_lahir'       => $validated['tmp_lahir'] ?? '',
                'tgl_lahir'       => !empty($validated['tgl_lahir']) ? $validated['tgl_lahir'] : '0000-00-00',
                'agama'           => $validated['agama'] ?? '',
                'status'          => $validated['status'] ?? '',
                'anakke'          => $validated['anakke'] ?? 1,
                'alamat'          => $validated['alamat'] ?? '',
                'notelp'          => $validated['notelp'] ?? '',
                'sek_asal'        => $validated['sek_asal'] ?? '',
                'sek_asal_alamat' => $validated['sek_asal_alamat'] ?? '',
                'diterima_kelas'  => $validated['diterima_kelas'] ?? '',
                'diterima_tgl'    => !empty($validated['diterima_tgl']) ? $validated['diterima_tgl'] : '0000-00-00',
                'diterima_smt'    => $validated['diterima_smt'] ?? '',
                'ijazah_no'       => $validated['ijazah_no'] ?? '',
                'ijazah_thn'      => $validated['ijazah_thn'] ?? '',
                'skhun_no'        => $validated['skhun_no'] ?? '',
                'skhun_thn'       => $validated['skhun_thn'] ?? '',
                'ortu_ayah'       => $validated['ortu_ayah'] ?? '',
                'ortu_ibu'        => $validated['ortu_ibu'] ?? '',
                'ortu_alamat'     => $validated['ortu_alamat'] ?? '',
                'ortu_notelp'     => $validated['ortu_notelp'] ?? '',
                'ortu_ayah_pkj'   => $validated['ortu_ayah_pkj'] ?? '',
                'ortu_ibu_pkj'    => $validated['ortu_ibu_pkj'] ?? '',
                'wali'            => $validated['wali'] ?? '',
                'wali_alamat'     => $validated['wali_alamat'] ?? '',
                'notelp_rumah'    => $validated['notelp_rumah'] ?? '',
                'wali_pkj'        => $validated['wali_pkj'] ?? '',
                'inputID'         => 0, // Sesuai PRD SIS-3 & ARCHITECTURE: inputID selalu 0
                'stat_data'       => 'A', // Default Aktif
                'foto'            => '',
            ];

            $siswa = Siswa::create($data);

            // Jika opsi buat akun dipilih (username = NIS per PRD AKN-2)
            if ($request->boolean('buat_akun')) {
                User::create([
                    'username'             => $siswa->nis,
                    'password'             => Hash::make('Siswa@' . $siswa->nis),
                    'role'                 => 'siswa',
                    'id_siswa'             => $siswa->id,
                    'is_active'            => true,
                    'wajib_ganti_password' => true,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.master.siswa.index')
                ->with('success', "Data siswa {$siswa->nama} (NIS: {$siswa->nis}) berhasil ditambahkan.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan formulir edit data siswa.
     */
    public function edit(Siswa $siswa): View
    {
        return view('pages.admin.master.siswa.form', compact('siswa'));
    }

    /**
     * Perbarui data siswa di m_siswa.
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'nis'             => ['required', 'string', 'max:10', Rule::unique('m_siswa', 'nis')->ignore($siswa->id)],
            'nama'            => ['required', 'string', 'max:100'],
            'nisn'            => ['nullable', 'string', 'max:10'],
            'jk'              => ['nullable', Rule::in(['L', 'P'])],
            'tmp_lahir'       => ['nullable', 'string', 'max:50'],
            'tgl_lahir'       => ['nullable', 'date'],
            'agama'           => ['nullable', 'string', 'max:10'],
            'status'          => ['nullable', 'string', 'max:2'],
            'anakke'          => ['nullable', 'integer', 'min:1', 'max:99'],
            'alamat'          => ['nullable', 'string', 'max:50'],
            'notelp'          => ['nullable', 'string', 'max:13'],
            // Sekolah Asal
            'sek_asal'        => ['nullable', 'string', 'max:30'],
            'sek_asal_alamat' => ['nullable', 'string', 'max:50'],
            'diterima_kelas'  => ['nullable', 'string', 'max:5'],
            'diterima_tgl'    => ['nullable', 'date'],
            'diterima_smt'    => ['nullable', 'string', 'max:2'],
            'ijazah_no'       => ['nullable', 'string', 'max:50'],
            'ijazah_thn'      => ['nullable', 'string', 'max:4'],
            'skhun_no'        => ['nullable', 'string', 'max:50'],
            'skhun_thn'       => ['nullable', 'string', 'max:4'],
            // Orang Tua / Wali
            'ortu_ayah'       => ['nullable', 'string', 'max:50'],
            'ortu_ibu'        => ['nullable', 'string', 'max:50'],
            'ortu_alamat'     => ['nullable', 'string', 'max:50'],
            'ortu_notelp'     => ['nullable', 'string', 'max:13'],
            'ortu_ayah_pkj'   => ['nullable', 'string', 'max:30'],
            'ortu_ibu_pkj'    => ['nullable', 'string', 'max:30'],
            'wali'            => ['nullable', 'string', 'max:20'],
            'wali_alamat'     => ['nullable', 'string', 'max:50'],
            'notelp_rumah'    => ['nullable', 'string', 'max:13'],
            'wali_pkj'        => ['nullable', 'string', 'max:13'],
            // Status Data
            'stat_data'       => ['nullable', Rule::in(['A', 'K', 'M', 'L'])],
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'nis'             => $validated['nis'],
                'nama'            => $validated['nama'],
                'nisn'            => $validated['nisn'] ?? '0',
                'jk'              => $validated['jk'] ?? $siswa->jk,
                'tmp_lahir'       => $validated['tmp_lahir'] ?? '',
                'tgl_lahir'       => !empty($validated['tgl_lahir']) ? $validated['tgl_lahir'] : '0000-00-00',
                'agama'           => $validated['agama'] ?? '',
                'status'          => $validated['status'] ?? '',
                'anakke'          => $validated['anakke'] ?? $siswa->anakke,
                'alamat'          => $validated['alamat'] ?? '',
                'notelp'          => $validated['notelp'] ?? '',
                'sek_asal'        => $validated['sek_asal'] ?? '',
                'sek_asal_alamat' => $validated['sek_asal_alamat'] ?? '',
                'diterima_kelas'  => $validated['diterima_kelas'] ?? '',
                'diterima_tgl'    => !empty($validated['diterima_tgl']) ? $validated['diterima_tgl'] : '0000-00-00',
                'diterima_smt'    => $validated['diterima_smt'] ?? '',
                'ijazah_no'       => $validated['ijazah_no'] ?? '',
                'ijazah_thn'      => $validated['ijazah_thn'] ?? '',
                'skhun_no'        => $validated['skhun_no'] ?? '',
                'skhun_thn'       => $validated['skhun_thn'] ?? '',
                'ortu_ayah'       => $validated['ortu_ayah'] ?? '',
                'ortu_ibu'        => $validated['ortu_ibu'] ?? '',
                'ortu_alamat'     => $validated['ortu_alamat'] ?? '',
                'ortu_notelp'     => $validated['ortu_notelp'] ?? '',
                'ortu_ayah_pkj'   => $validated['ortu_ayah_pkj'] ?? '',
                'ortu_ibu_pkj'    => $validated['ortu_ibu_pkj'] ?? '',
                'wali'            => $validated['wali'] ?? '',
                'wali_alamat'     => $validated['wali_alamat'] ?? '',
                'notelp_rumah'    => $validated['notelp_rumah'] ?? '',
                'wali_pkj'        => $validated['wali_pkj'] ?? '',
                'stat_data'       => $validated['stat_data'] ?? $siswa->stat_data,
            ];

            $siswa->update($data);

            // Sinkronkan username jika NIS berubah dan akun sudah ada
            if ($siswa->user && $siswa->user->username !== $siswa->nis) {
                $siswa->user->update(['username' => $siswa->nis]);
            }

            DB::commit();

            return redirect()
                ->route('admin.master.siswa.index')
                ->with('success', "Data siswa {$siswa->nama} berhasil diperbarui.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Nonaktifkan siswa / ubah status (Soft Delete via stat_data).
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        try {
            $siswa->update(['stat_data' => 'K']);

            if ($siswa->user) {
                $siswa->user->update(['is_active' => false]);
            }

            return redirect()
                ->route('admin.master.siswa.index')
                ->with('success', "Status siswa {$siswa->nama} berhasil diubah menjadi Keluar (Nonaktif).");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengubah status siswa: ' . $e->getMessage());
        }
    }

    /**
     * [MASTER-AKN-001] Pembuatan Akun Siswa Massal.
     * Membuat akun user di siakad_users dengan username = NIS, password acak sementara,
     * dan wajib_ganti_password = 1. Password sementara hanya dikembalikan sekali di response/session.
     * Siswa yang sudah memiliki akun dilewati (idempotent) dan diberikan keterangan status yang jelas.
     */
    public function generateAkunMassal(Request $request)
    {
        // Ambil seluruh data siswa aktif beserta relasi akun user
        $allSiswaInScope = Siswa::with('user')->where('stat_data', 'A')->get();

        if ($allSiswaInScope->isEmpty()) {
            $pesan = 'Tidak ada siswa aktif yang terdaftar di sistem.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $pesan,
                    'data'    => [],
                ], 422);
            }

            return back()->with('error', $pesan);
        }

        // Ambil seluruh username yang sudah terdaftar di siakad_users untuk pencegahan tabrakan
        $existingUsernames = User::pluck('username')->flip()->toArray();

        $kredensialList = [];
        $dilewatiList   = [];

        DB::transaction(function () use ($allSiswaInScope, &$existingUsernames, &$kredensialList, &$dilewatiList) {
            foreach ($allSiswaInScope as $siswa) {
                // 1. Pengecekan apakah siswa sudah memiliki akun terkait (via relasi user)
                if ($siswa->user) {
                    $dilewatiList[] = [
                        'id_siswa'   => $siswa->id,
                        'nis'        => $siswa->nis,
                        'nama'       => $siswa->nama,
                        'kelas'      => $siswa->diterima_kelas ?: '-',
                        'keterangan' => 'Akun sudah ada (Username: ' . $siswa->user->username . ')',
                    ];
                    continue;
                }

                // 2. Pengecekan apakah username (NIS) sudah digunakan di siakad_users oleh akun lain
                if (isset($existingUsernames[$siswa->nis])) {
                    $dilewatiList[] = [
                        'id_siswa'   => $siswa->id,
                        'nis'        => $siswa->nis,
                        'nama'       => $siswa->nama,
                        'kelas'      => $siswa->diterima_kelas ?: '-',
                        'keterangan' => 'Akun sudah ada (NIS sudah terdaftar sebagai username)',
                    ];
                    continue;
                }

                // 3. Generate password sementara acak 8 karakter (kombinasi huruf & angka)
                $tempPassword = 'Smp7' . Str::upper(Str::random(4));

                User::create([
                    'username'             => $siswa->nis,
                    'password'             => Hash::make($tempPassword),
                    'role'                 => 'siswa',
                    'id_siswa'             => $siswa->id,
                    'is_active'            => true,
                    'wajib_ganti_password' => true,
                    'is_admin_utama'       => false,
                ]);

                // Tandai username sebagai terdaftar di memory
                $existingUsernames[$siswa->nis] = true;

                $kredensialList[] = [
                    'id_siswa'           => $siswa->id,
                    'nis'                => $siswa->nis,
                    'nama'               => $siswa->nama,
                    'kelas'              => $siswa->diterima_kelas ?: '-',
                    'password_sementara' => $tempPassword,
                    'keterangan'         => 'Akun baru berhasil dibuat',
                ];
            }
        });

        $totalDibuat   = count($kredensialList);
        $totalDilewati = count($dilewatiList);

        if ($totalDibuat === 0) {
            $pesan = "Semua siswa aktif ({$totalDilewati} siswa) sudah memiliki akun login aktif. Tidak ada akun baru yang digenerate.";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'        => true,
                    'message'        => $pesan,
                    'total_dibuat'   => 0,
                    'total_dilewati' => $totalDilewati,
                    'data'           => [],
                    'dilewati'       => $dilewatiList,
                ]);
            }

            return back()->with('info', $pesan);
        }

        $infoPesan = "Berhasil membuat {$totalDibuat} akun siswa baru.";
        if ($totalDilewati > 0) {
            $infoPesan .= " ({$totalDilewati} siswa dilewati karena akun sudah ada).";
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'        => true,
                'message'        => $infoPesan,
                'total'          => $totalDibuat,
                'total_dilewati' => $totalDilewati,
                'data'           => $kredensialList,
                'dilewati'       => $dilewatiList,
            ]);
        }

        return redirect()->route('admin.master.siswa.index')
            ->with('batch_credentials', $kredensialList)
            ->with('batch_skipped', $dilewatiList)
            ->with('success', $infoPesan . ' Simpan kredensial sementara berikut sebelum menutup halaman!');
    }

    /**
     * [MASTER-AKN-002] Buat Akun Satuan Siswa (Username = NIS).
     */
    public function buatAkun(Request $request, Siswa $siswa)
    {
        if ($siswa->user) {
            $msg = "Siswa '{$siswa->nama}' sudah memiliki akun login (username: {$siswa->user->username}).";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => false,
                    'message'  => $msg,
                    'username' => $siswa->user->username,
                ], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $validated = $request->validate([
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $username = trim($siswa->nis);
        if (empty($username)) {
            $msg = "Siswa '{$siswa->nama}' belum memiliki NIS valid untuk dijadikan username.";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        if (User::where('username', $username)->exists()) {
            $msg = "Username {$username} (NIS) sudah terdaftar pada pengguna lain.";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $plainPassword = !empty($validated['password'])
            ? $validated['password']
            : 'Smp7' . Str::upper(Str::random(4));

        $user = User::create([
            'username'             => $username,
            'password'             => Hash::make($plainPassword),
            'role'                 => 'siswa',
            'id_siswa'             => $siswa->id,
            'is_active'            => ($siswa->stat_data === 'A'),
            'wajib_ganti_password' => true,
            'is_admin_utama'       => false,
        ]);

        $message = "Akun siswa '{$siswa->nama}' berhasil dibuat dengan username: {$username} dan password sementara: {$plainPassword}";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'       => true,
                'username'      => $username,
                'password_baru' => $plainPassword,
                'message'       => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * [MASTER-AKN-001] Reset Password Siswa Satuan (atau Buat Akun Baru jika Belum Punya).
     */
    public function resetPassword(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'password_baru' => ['nullable', 'string', 'min:6'],
        ], [
            'password_baru.min' => 'Password baru minimal 6 karakter.',
        ]);

        $passwordBaru = !empty($validated['password_baru'])
            ? $validated['password_baru']
            : 'Smp7' . Str::upper(Str::random(4));

        $user = $siswa->user;

        if ($user) {
            $user->password = Hash::make($passwordBaru);
            $user->wajib_ganti_password = true;
            $user->save();
        } else {
            // Jika belum punya akun, buatkan otomatis dengan username = NIS
            $user = User::create([
                'username'             => $siswa->nis,
                'password'             => Hash::make($passwordBaru),
                'role'                 => 'siswa',
                'id_siswa'             => $siswa->id,
                'is_active'            => ($siswa->stat_data === 'A'),
                'wajib_ganti_password' => true,
                'is_admin_utama'       => false,
            ]);
        }

        $message = "Password akun siswa '{$siswa->nama}' (username: {$user->username}) berhasil diatur ulang menjadi: {$passwordBaru}";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'       => true,
                'username'      => $user->username,
                'password_baru' => $passwordBaru,
                'message'       => $message,
            ]);
        }

        return redirect()->back()
            ->with('success', $message);
    }
}
