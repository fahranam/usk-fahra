<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Nama Fungsi: handle
     * Tujuan: Membatasi hak akses rute hanya untuk pengguna yang memiliki hak/peran sebagai Administrator.
     * Alur: Memeriksa apakah pengguna sudah login (jika belum dialihkan ke login), memastikan akun admin memiliki role 'admin', dan menolak akses (HTTP 403 Forbidden) jika pengguna bukan Administrator.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->email === 'admin@gmail.com' && $user->role !== 'admin') {
            \App\Models\User::where('id', $user->id)->update(['role' => 'admin']);
            $user->role = 'admin';
        }

        if ($user->role !== 'admin' && $user->email !== 'admin@gmail.com') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        }

        return $next($request);
    }
}
