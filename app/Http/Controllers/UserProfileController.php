<?php

namespace App\Http\Controllers;

use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    /**
     * Tampilkan profil akun Guru (AUT-7).
     */
    public function profileGuru()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            abort(403, 'Akses khusus guru.');
        }

        $guru = $user->guru;
        $tasmAktif = Tahun::getTasmAktif();

        // Penugasan mengajar aktif pada semester ini
        $penugasanList = collect();
        $isWaliKelas = false;
        $waliKelasList = collect();

        if ($guru) {
            $penugasanList = $guru->guruMapel()
                ->where('tasm', $tasmAktif)
                ->with(['kelas', 'mapel'])
                ->get();

            $waliKelasList = $guru->waliKelas()
                ->where('tasm', $tasmAktif)
                ->with('kelas')
                ->get();

            $isWaliKelas = $waliKelasList->isNotEmpty();
        }

        return view('pages.guru.profil', compact(
            'user',
            'guru',
            'tasmAktif',
            'penugasanList',
            'isWaliKelas',
            'waliKelasList'
        ));
    }

    /**
     * Tampilkan profil akun Siswa (AUT-7, DIR-8).
     */
    public function profileSiswa()
    {
        $user = Auth::user();
        if (!$user || !$user->isSiswa()) {
            abort(403, 'Akses khusus siswa.');
        }

        $siswa = $user->siswa;
        $taAktif = Tahun::getTaAktif();

        // Penempatan kelas aktif siswa pada tahun ajaran ini
        $kelasPenempatan = null;
        if ($siswa) {
            $kelasPenempatan = $siswa->kelasAktif($taAktif);
        }

        return view('pages.siswa.profil', compact(
            'user',
            'siswa',
            'taAktif',
            'kelasPenempatan'
        ));
    }

    /**
     * Aksi ganti password mandiri dari menu profil (AUT-6).
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $identifier = $user->username;
        if ($user->isSiswa() && $user->siswa) {
            $identifierNIS = $user->siswa->nis;
        } else {
            $identifierNIS = null;
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
                function ($attribute, $value, $fail) use ($identifier, $identifierNIS) {
                    if (strcasecmp($value, $identifier) === 0) {
                        $fail('Password baru tidak boleh sama dengan username Anda.');
                    }
                    if ($identifierNIS && strcasecmp($value, $identifierNIS) === 0) {
                        $fail('Password baru tidak boleh sama dengan NIS Anda.');
                    }
                },
            ],
        ], [
            'current_password.current_password' => 'Password saat ini salah.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'wajib_ganti_password' => false,
        ]);

        return back()->with('success', 'Password Anda berhasil diperbarui.');
    }
}
