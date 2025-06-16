<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN user memiliki kolom is_admin dengan nilai true
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); // Lanjutkan permintaan
        }

        // Jika bukan admin atau belum login, redirect ke homepage atau halaman login
        // Gunakan with() untuk flash pesan error ke session (bisa ditampilkan di Blade)
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}