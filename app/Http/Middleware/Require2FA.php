<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Require2FA
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // إذا 2FA مفعّل ولم يتحقق منه بعد في هذه الجلسة
        if ($user->two_factor_enabled && !session('2fa_verified')) {
            // نسمح بالوصول لصفحة التحقق فقط
            if (!$request->routeIs('2fa.verify', '2fa.setup', 'logout')) {
                return redirect()->route('2fa.verify');
            }
        }

        return $next($request);
    }
}
