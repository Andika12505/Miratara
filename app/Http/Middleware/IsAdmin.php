<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah user telah login DAN memiliki is_admin = 1
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request); // Lanjutkan permintaan jika user adalah admin
        }

        // Jika user tidak login atau bukan admin, arahkan mereka ke halaman login
        // dengan pesan error kustom menggunakan flash key 'error'
        return redirect('/masuk')->with('error', 'Anda tidak memiliki akses ke halaman admin karena bukan administrator.');
    }
}
