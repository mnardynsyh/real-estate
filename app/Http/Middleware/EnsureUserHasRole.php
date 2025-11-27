<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role user SESUAI dengan role yang diminta route
        if (Auth::user()->role !== $role) {
            
            // Logika Redirect jika salah kamar:
            
            // Jika user Customer mencoba masuk area Admin -> Tendang ke Dashboard Customer
            if (Auth::user()->role === 'customer') {
                return redirect()->route('customer.dashboard');
            }

            // Jika user Admin mencoba masuk area Customer -> Tendang ke Dashboard Admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Default: Tampilkan halaman 403 Forbidden
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}