<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        $middleware->alias([
            'auth.employee' => \App\Http\Middleware\RequireAuth::class,
            'require.2fa'   => \App\Http\Middleware\Require2FA::class,
            'require.admin' => \App\Http\Middleware\RequireAdmin::class,
        ]);
        $middleware->redirectUsersTo('/');
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // معالجة خطأ انتهاء التوكن (419) — التحويل إلى صفحة الدخول
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response) {
            if ($response->getStatusCode() === 419) {
                return redirect()->route('login')
                    ->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مجدداً.');
            }
            return $response;
        });
    })->create();
