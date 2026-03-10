<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $username = '';
    public string $password = '';
    public bool   $failed   = false;

    #[Title('تسجيل الدخول — مركز مطمئنة')]
    public function login(): void
    {
        $this->failed = false;

        $credentials = [
            'user_name' => trim($this->username),
            'password'  => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            $this->redirect(route('dashboard'));
            return;
        }

        $this->failed   = true;
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.guest');
    }
}
