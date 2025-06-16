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
        // Debug: Cek status auth dan user
        if (!Auth::check()) {
            \Log::info('User not authenticated');
            return redirect('/masuk')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = Auth::user();
        \Log::info('User ID: ' . $user->id . ', is_admin: ' . $user->is_admin);
        
        if (!$user->is_admin) {
            \Log::info('User is not admin');
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }
        
        \Log::info('Admin access granted');
        return $next($request);
}
}