<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Cek apakah user punya salah satu dari role yang diizinkan.
     * Contoh pemakaian di route: RoleMiddleware::class . ':petugas,admin'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! $request->user()->hasAnyRole($roles)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}
