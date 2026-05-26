<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Pastikan dia sudah login DAN role-nya sesuai dengan yang diminta
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request); // Silakan lewat!
        }

        // Kalau role-nya tidak cocok, munculkan halaman error "Dilarang Masuk"
        abort(403, 'Akses Ditolak: Anda tidak memiliki ID Card untuk ruangan ini.');
    }
}