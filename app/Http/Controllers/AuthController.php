<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login utama SIAKAD (semua role).
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect(Auth::user()->dashboardUrl());
        }

        return view('pages.login.index');
    }

    /**
     * Proses autentikasi user dengan rate limiting & validasi status akun.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username atau ID wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('username')) . '|' . $request->ip());

        // Batasi percobaan login (5x gagal -> kunci 60 detik)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan gagal. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Cari user & verifikasi status aktif
        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'username' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
            ]);
        }

        if (!$user->is_active) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'username' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Administrator sekolah.',
            ]);
        }

        // Login sukses
        RateLimiter::clear($throttleKey);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Mode wajib ganti password
        if ($user->wajib_ganti_password) {
            return redirect()->route('password.change')
                ->with('warning', 'Demi keamanan, Anda wajib memperbarui password sementara terlebih dahulu.');
        }

        return redirect()->intended($user->dashboardUrl());
    }

    /**
     * Tampilkan antarmuka Ubah Password (layout fokus).
     */
    public function showChangePassword()
    {
        return view('pages.auth.change-password', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Proses perubahan password (AUT-6).
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) use ($user) {
                    if (strcasecmp($value, $user->username) === 0) {
                        $fail('Password baru tidak boleh sama dengan username.');
                    }
                    if ($user->id_siswa && strcasecmp($value, (string) $user->username) === 0) {
                        $fail('Password baru tidak boleh sama dengan NIS Anda.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        // Simpan password baru & matikan flag wajib ganti password
        $user->forceFill([
            'password' => Hash::make($request->input('password')),
            'wajib_ganti_password' => false,
        ])->save();

        // Logout perangkat lain
        Auth::logoutOtherDevices($request->input('password'));

        return redirect($user->dashboardUrl())
            ->with('success', 'Password Anda berhasil diperbarui.');
    }

    /**
     * Keluar dari sesi aplikasi.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Anda telah berhasil keluar dari sistem.');
    }
}

