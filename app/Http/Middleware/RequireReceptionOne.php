<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireReceptionOne
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = auth()->user()?->role ?? '';

        if (!in_array($role, ['admin', 'reception1'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'ليس لديك صلاحية للوصول لهذه الصفحة');
        }

        return $next($request);
    }
}
