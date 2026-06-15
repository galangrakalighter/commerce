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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Contoh pengecekan logika admin (sesuaikan dengan logic auth Anda)
        if (auth()->check() && auth()->user()->is_admin === true) {
            return $next($request);
        }

        // JIKA GAGAL MASUK SINI:
        // Cek jika request meminta JSON atau dikirim lewat AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Akses ditolak. Anda bukan administrator.'
            ], 403);
        }

        // Jika request halaman biasa, lempar ke halaman error 403 bawaan Laravel
        abort(403, 'Unauthorized action.');
    }
}
