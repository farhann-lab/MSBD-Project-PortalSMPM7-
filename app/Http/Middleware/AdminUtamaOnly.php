<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUtamaOnly
{
    /**
     * Handle an incoming request.
     * Hanya memperbolehkan user dengan role 'admin' dan is_admin_utama = true.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isAdmin() || !$user->isAdminUtama()) {
            abort(403, 'Akses Ditolak: Menu ini hanya dapat diakses oleh Admin Utama.');
        }

        return $next($request);
    }
}
