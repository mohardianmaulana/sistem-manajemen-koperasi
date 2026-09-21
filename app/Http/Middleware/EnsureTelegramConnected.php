<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTelegramConnected
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Cek jika user belum menghubungkan Telegram (misal: telegram_chat_id masih null)
        if ($user && is_null($user->telegram_chat_id)) {
            
            // Izinkan akses HANYA ke halaman hubungkan telegram dan route logout
            if (!$request->is('hubungkan-telegram*') && !$request->is('logout')) {
                return redirect()->route('home.index')
                    ->with('warning', 'Anda harus menghubungkan akun Telegram terlebih dahulu untuk mengakses sistem.');
            }
        }

        return $next($request);
    }
}
