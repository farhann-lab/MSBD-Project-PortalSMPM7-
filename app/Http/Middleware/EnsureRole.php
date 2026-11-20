<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            abort(403, 'Akun tidak aktif atau tidak dikenal.');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Role ini tidak memiliki akses.');
        }

        return $next($request);
    }
}
