<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    /**
     * Tampilkan daftar seluruh akun admin.
     */
    public function index(): View
    {
        $admins = User::where('role', 'admin')
            ->orderByDesc('is_admin_utama')
            ->orderBy('username')
            ->get();

        return view('pages.admin.manajemen-admin', compact('admins'));
    }

    /**
     * Tambah akun admin baru.
     * Username unik, password di-hash bcrypt, wajib_ganti_password diset 1.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                'unique:siakad_users,username',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ], [
            'username.required'   => 'Username admin wajib diisi.',
            'username.unique'     => 'Username tersebut sudah digunakan oleh akun lain.',
            'username.alpha_dash' => 'Username hanya boleh berupa huruf, angka, tanda strip, atau garis bawah.',
            'username.min'        => 'Username minimal terdiri dari 3 karakter.',
            'password.required'   => 'Password awal wajib diisi.',
            'password.min'        => 'Password awal minimal 8 karakter.',
        ]);

        User::create([
            'username'             => strtolower(trim($validated['username'])),
            'password'             => Hash::make($validated['password']),
            'role'                 => 'admin',
            'is_active'            => true,
            'wajib_ganti_password' => true,
            'is_admin_utama'       => false,
        ]);

        return redirect()->route('admin.manajemen-admin.index')
            ->with('success', "Akun admin '{$validated['username']}' berhasil dibuat. User diwajibkan ganti password saat login pertama.");
    }

    /**
     * Toggle status aktif / nonaktif akun admin.
     * Aturan Bisnis: Akun Admin Utama tidak bisa dinonaktifkan oleh siapa pun.
     */
    public function toggleStatus(User $admin): RedirectResponse
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        if ($admin->isAdminUtama()) {
            return redirect()->route('admin.manajemen-admin.index')
                ->with('error', 'Pelanggaran Aturan Bisnis: Akun Admin Utama tidak dapat dinonaktifkan.');
        }

        $admin->is_active = !$admin->is_active;
        $admin->save();

        $statusLabel = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.manajemen-admin.index')
            ->with('success', "Status akun admin '{$admin->username}' berhasil {$statusLabel}.");
    }

    /**
     * Reset password akun admin.
     * Set password baru dan kembalikan wajib_ganti_password = 1 agar pengguna memperbarui kata sandi.
     */
    public function resetPassword(Request $request, User $admin): RedirectResponse
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $validated = $request->validate([
            'password_baru' => [
                'required',
                'string',
                'min:8',
            ],
        ], [
            'password_baru.required' => 'Password baru untuk reset wajib diisi.',
            'password_baru.min'      => 'Password baru minimal 8 karakter.',
        ]);

        $admin->password = Hash::make($validated['password_baru']);
        $admin->wajib_ganti_password = true;
        $admin->save();

        return redirect()->route('admin.manajemen-admin.index')
            ->with('success', "Password akun '{$admin->username}' berhasil direset. User wajib mengganti password saat login.");
    }
}
