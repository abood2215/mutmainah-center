<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireAuth
{
    // مدة الخمول بالثواني (10 دقائق)
    const INACTIVITY_LIMIT = 600;

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $lastActivity = session('last_activity');

        if ($lastActivity && (time() - $lastActivity) > self::INACTIVITY_LIMIT) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')->with('timeout', 'انتهت جلستك بسبب عدم النشاط');
        }

        session(['last_activity' => time()]);

        // دور عيادة: يُسمح فقط بالكشوفات وتقرير المكاتب
        $role = auth()->user()?->role ?? '';
        if ($role === 'clinic') {
            $allowed = $request->routeIs('checks.*') || $request->routeIs('finance.reports');
            if (!$allowed) {
                return redirect()->route('checks.index');
            }
        }

        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        return $response;
    }
}
