<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Login extends Component
{
    public string $username = '';
    public string $password = '';
    public bool   $failed   = false;
    public string $errorMsg = '';

    private function throttleKey(): string
    {
        return 'login.' . request()->ip();
    }

    #[Title('تسجيل الدخول — مركز مطمئنة')]
    public function login(): void
    {
        $this->failed   = false;
        $this->errorMsg = '';

        // Rate Limiting: 5 محاولات كل 5 دقائق
        $key = $this->throttleKey();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds        = RateLimiter::availableIn($key);
            $this->failed   = true;
            $this->errorMsg = "محاولات كثيرة جداً. انتظر {$seconds} ثانية.";
            Log::warning('Login throttled', ['ip' => request()->ip(), 'username' => $this->username]);
            return;
        }

        $credentials = [
            'user_name' => trim($this->username),
            'password'  => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            session()->regenerate();
            $this->logAttempt(true);
            $this->redirect(route('dashboard'));
            return;
        }

        // فشل الدخول
        RateLimiter::hit($key, 300);
        $remaining = max(0, 5 - RateLimiter::attempts($key));

        $this->logAttempt(false);
        $this->failed   = true;
        $this->errorMsg = $remaining > 0
            ? "اسم المستخدم أو كلمة المرور غير صحيحة. ({$remaining} محاولة متبقية)"
            : 'تم تجاوز عدد المحاولات. انتظر 5 دقائق.';
        $this->password = '';
    }

    private function logAttempt(bool $success): void
    {
        try {
            DB::table('activity_logs')->insert([
                'user_id'     => Auth::id() ?? 0,
                'user_name'   => $this->username,
                'action'      => $success ? 'login_success' : 'login_failed',
                'subject'     => 'auth',
                'subject_id'  => 0,
                'description' => ($success ? 'دخول ناجح' : 'محاولة دخول فاشلة')
                               . ' | IP: ' . request()->ip()
                               . ' | ' . substr(request()->userAgent() ?? '', 0, 80),
                'created_at'  => now(),
            ]);
        } catch (\Throwable) {}
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.guest');
    }
}
