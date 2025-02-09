<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Memeriksa apakah pengguna terautentikasi dan memiliki role yang sesuai
        if (auth()->check() && auth()->user()->role->name == $role) {
            return $next($request);
        }

        // Jika tidak memiliki akses, redirect ke halaman lain
        return redirect('/'); // Atau halaman lain jika akses ditolak
    }
}