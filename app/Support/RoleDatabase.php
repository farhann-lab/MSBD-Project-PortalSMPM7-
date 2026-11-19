<?php

namespace App\Support;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

/**
 * Pemilih koneksi berdasarkan role hasil login; bukan role dari request/query string.
 * Tidak mengubah database default global, sehingga query internal Laravel tetap terisolasi.
 */
final class RoleDatabase
{
    public static function enabled(): bool
    {
        return (bool) config('siakad_rbac.enabled', false);
    }

    public static function auth(): ?string
    {
        return self::enabled() ? 'mysql_auth' : null;
    }

    public static function admin(): ?string
    {
        return self::enabled() ? 'mysql_admin' : null;
    }

    public static function public(): ?string
    {
        return self::enabled() ? 'mysql_public' : null;
    }

    public static function forAuthenticatedRole(): ?string
    {
        $user = Auth::user();
        if (! $user || ! $user->is_active) {
            throw new AuthorizationException('Pengguna belum terautentikasi atau nonaktif.');
        }

        return match ($user->role) {
            'admin' => self::admin(),
            'guru' => self::enabled() ? 'mysql_guru' : null,
            'siswa' => self::enabled() ? 'mysql_siswa' : null,
            default => throw new AuthorizationException('Role tidak dikenal.'),
        };
    }
}
