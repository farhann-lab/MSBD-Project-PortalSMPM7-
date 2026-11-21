<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\RoleDatabase;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gunakan koneksi MySQL sesuai role LOGIN pada route operasional SIAKAD.
 *
 * Pasangkan setelah middleware 'auth' dan 'role:admin|guru|siswa'.
 * Tidak dipasang global atau pada route login/publik.
 *
 * Berbeda dengan mengubah Config::set('database.default', ...),
 * perubahan ini dibatasi pada penanganan request dan dipulihkan di finally.
 * Model yang memakai koneksi eksplisit tetap menggunakan koneksi tersebut.
 */
final class UseRoleDatabaseConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        // Mode kompatibilitas: website lama memakai koneksi sebelumnya.
        if (! RoleDatabase::enabled()) {
            return $next($request);
        }

        // Resolver memvalidasi session, status akun, dan ENUM role.
        // Jangan membaca role dari query string, form, atau URL.
        $connection = RoleDatabase::forAuthenticatedRole();

        if (! in_array($connection, ['mysql_admin', 'mysql_guru', 'mysql_siswa'], true)) {
            abort(403, 'Koneksi role tidak diizinkan.');
        }

        // Simpan dan pulihkan supaya tidak bocor ke request lain,
        // termasuk pada worker yang melayani beberapa request.
        $previous = DB::getDefaultConnection();
        DB::setDefaultConnection($connection);

        try {
            return $next($request);
        } finally {
            DB::setDefaultConnection($previous);
        }
    }
}
