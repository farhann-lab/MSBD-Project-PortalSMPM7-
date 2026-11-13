<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminGuruController extends Controller
{
    /**
     * Tampilkan daftar guru dan tendik dengan pagination, pencarian, dan filter status.
     */
    public function index(Request $request): View
    {
        $query = Guru::query()->with('user');

        // Filter status: A (Aktif), P (Pensiun/Cuti), M (Mutasi/Keluar)
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Pencarian nama atau NIP
        if ($request->filled('q')) {
            $query->search(trim($request->q));
        }

        $teachers = $query->orderBy('nama')->paginate(20)->withQueryString();

        $stats = [
            'total'   => Guru::count(),
            'aktif'   => Guru::where('stat_data', 'A')->count(),
            'pensiun' => Guru::where('stat_data', 'P')->count(),
            'mutasi'  => Guru::where('stat_data', 'M')->count(),
        ];

        return view('pages.admin.master.guru.index', compact('teachers', 'stats'));
    }

    /**
     * Tampilkan formulir penambahan data guru baru.
     */
    public function create(): View
    {
        return view('pages.admin.master.guru.form');
    }

    /**
     * Simpan data guru baru beserta opsi pembuatan akun login SIAKAD.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'nama'      => ['required', 'string', 'max:50'],
            'nip'       => ['nullable', 'string', 'max:20', 'unique:m_guru,nip'],
            'jk'        => ['required', Rule::in(['L', 'P'])],
            'is_bk'     => ['required', Rule::in(['1', '2'])],
            'buat_akun' => ['nullable', 'boolean'],
        ];

        // Validasi opsional akun login jika checkbox buat_akun dicentang
        if ($request->boolean('buat_akun')) {
            $rules['username'] = [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                'unique:siakad_users,username',
            ];
            $rules['password'] = ['required', 'string', 'min:6'];
        }

        $messages = [
            'nama.required'       => 'Nama lengkap guru wajib diisi.',
            'nama.max'            => 'Nama lengkap maksimal 50 karakter.',
            'nip.unique'          => 'NIP tersebut sudah terdaftar pada guru lain.',
            'jk.required'         => 'Jenis kelamin wajib dipilih.',
            'is_bk.required'      => 'Kategori peran guru wajib ditentukan.',
            'username.required'   => 'Username login wajib diisi jika membuat akun.',
            'username.unique'     => 'Username tersebut sudah digunakan akun lain.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, garis bawah, dan strip.',
            'password.required'   => 'Password akun login wajib diisi.',
            'password.min'        => 'Password akun login minimal 6 karakter.',
        ];

        $validated = $request->validate($rules, $messages);

        DB::transaction(function () use ($validated, $request) {
            $guru = Guru::create([
                'nama'      => trim($validated['nama']),
                'nip'       => !empty($validated['nip']) ? trim($validated['nip']) : null,
                'jk'        => $validated['jk'],
                'is_bk'     => $validated['is_bk'],
                'stat_data' => 'A', // default saat dibuat selalu Aktif
            ]);

            // Jika admin meminta buat akun login sekaligus
            if ($request->boolean('buat_akun')) {
                User::create([
                    'username'             => strtolower(trim($validated['username'])),
                    'password'             => Hash::make($validated['password']),
                    'role'                 => 'guru',
                    'id_guru'              => $guru->id,
                    'is_active'            => true,
                    'wajib_ganti_password' => true,
                    'is_admin_utama'       => false,
                ]);
            }
        });

        return redirect()->route('admin.master.guru.index')
            ->with('success', "Data guru '{$validated['nama']}' berhasil ditambahkan ke database.");
    }

    /**
     * Tampilkan formulir edit data guru.
     */
    public function edit(Guru $guru): View
    {
        $guru->load('user');
        return view('pages.admin.master.guru.form', compact('guru'));
    }

    /**
     * Simpan pembaruan data guru.
     */
    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:50'],
            'nip'       => ['nullable', 'string', 'max:20', Rule::unique('m_guru', 'nip')->ignore($guru->id)],
            'jk'        => ['required', Rule::in(['L', 'P'])],
            'is_bk'     => ['required', Rule::in(['1', '2'])],
            'stat_data' => ['required', Rule::in(['A', 'P', 'M'])],
        ], [
            'nama.required'  => 'Nama lengkap guru wajib diisi.',
            'nama.max'       => 'Nama lengkap maksimal 50 karakter.',
            'nip.unique'     => 'NIP tersebut sudah digunakan oleh data guru lain.',
            'jk.required'    => 'Jenis kelamin wajib dipilih.',
            'is_bk.required' => 'Kategori peran guru wajib ditentukan.',
        ]);

        DB::transaction(function () use ($guru, $validated) {
            $guru->update([
                'nama'      => trim($validated['nama']),
                'nip'       => !empty($validated['nip']) ? trim($validated['nip']) : null,
                'jk'        => $validated['jk'],
                'is_bk'     => $validated['is_bk'],
                'stat_data' => $validated['stat_data'],
            ]);

            // Sinkronisasi status akun jika guru memiliki akun login
            if ($guru->user) {
                // Akun aktif hanya jika status guru 'A' (Aktif)
                $guru->user->update([
                    'is_active' => ($validated['stat_data'] === 'A'),
                ]);
            }
        });

        return redirect()->route('admin.master.guru.index')
            ->with('success', "Data guru '{$guru->nama}' berhasil diperbarui.");
    }

    /**
     * Toggle status aktif / pensiun guru melalui AJAX atau redirect biasa.
     */
    public function toggleStatus(Request $request, Guru $guru)
    {
        $newStatus = ($guru->stat_data === 'A') ? 'P' : 'A';
        $guru->stat_data = $newStatus;
        $guru->save();

        // Sinkronisasi akun login terkait di siakad_users
        if ($guru->user) {
            $guru->user->is_active = ($newStatus === 'A');
            $guru->user->save();
        }

        $label = $guru->label_status;

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'status'     => $newStatus,
                'label'      => $label,
                'is_active'  => ($newStatus === 'A'),
                'badgeClass' => $guru->badge_class,
                'message'    => "Status guru '{$guru->nama}' diubah menjadi {$label}.",
            ]);
        }

        return redirect()->back()
            ->with('success', "Status guru '{$guru->nama}' berhasil diubah menjadi {$label}.");
    }

    /**
     * Soft-deactivate data guru (ubah stat_data menjadi 'M' / Mutasi/Keluar).
     * DILARANG melakukan hard delete fisik demi integritas data akademik RKM.
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        // Ubah status guru menjadi 'M' (Mutasi / Nonaktif Tetap)
        $guru->stat_data = 'M';
        $guru->save();

        // Nonaktifkan akses login akun guru jika ada
        if ($guru->user) {
            $guru->user->is_active = false;
            $guru->user->save();
        }

        return redirect()->route('admin.master.guru.index')
            ->with('success', "Guru '{$guru->nama}' berhasil dinonaktifkan (Status: Mutasi/Keluar). Akun login dinonaktifkan.");
    }

    /**
     * Reset password akun login guru (atau buat akun baru jika belum ada).
     */
    public function resetPassword(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'password_baru' => ['required', 'string', 'min:6'],
        ], [
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min'      => 'Password baru minimal 6 karakter.',
        ]);

        $user = $guru->user;

        if ($user) {
            $user->password = Hash::make($validated['password_baru']);
            $user->wajib_ganti_password = true;
            $user->save();
        } else {
            // Jika guru belum punya akun, buatkan akun baru otomatis
            $cleanName = Str::slug(explode(' ', trim($guru->nama))[0] ?? 'guru', '');
            $username = $cleanName . $guru->id;
            
            // Pastikan username unik
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $cleanName . $guru->id . $counter;
                $counter++;
            }

            $user = User::create([
                'username'             => $username,
                'password'             => Hash::make($validated['password_baru']),
                'role'                 => 'guru',
                'id_guru'              => $guru->id,
                'is_active'            => ($guru->stat_data === 'A'),
                'wajib_ganti_password' => true,
                'is_admin_utama'       => false,
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'username' => $user->username,
                'message'  => "Password akun guru '{$guru->nama}' (username: {$user->username}) berhasil disetel ulang.",
            ]);
        }

        return redirect()->back()
            ->with('success', "Password akun guru '{$guru->nama}' (username: {$user->username}) berhasil disetel ulang.");
    }
}
