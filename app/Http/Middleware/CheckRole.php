<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Memeriksa apakah role pengguna yang sedang melakukan request termasuk dalam daftar role yang diizinkan
        if (in_array($request->user()->role, $roles)) {
            // Jika pengguna memiliki salah satu dari role yang diizinkan, maka lanjutkan request
            return $next($request);
        }
        // Jika pengguna tidak memiliki salah satu dari role yang diizinkan, maka redirect ke halaman utama ('/')
        return redirect('/');
    }
}
