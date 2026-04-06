<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FA\Google2FA;

class TwoFactor extends Component
{
    public string $code    = '';
    public bool   $failed  = false;
    public string $message = '';

    #[Title('التحقق بخطوتين')]
    public function verify(): void
    {
        $this->failed  = false;
        $this->message = '';

        $user   = Auth::user();
        $google = new Google2FA();

        $valid = $google->verifyKey(
            $user->two_factor_secret,
            trim($this->code),
            2 // window: تقبل ±2 فترة (60 ثانية تقريباً)
        );

        if ($valid) {
            session(['2fa_verified' => true]);

            DB::table('activity_logs')->insert([
                'user_id'     => $user->getAuthIdentifier(),
                'user_name'   => $user->getName(),
                'action'      => '2fa_verified',
                'subject'     => 'auth',
                'subject_id'  => 0,
                'description' => 'تحقق 2FA ناجح | IP: ' . request()->ip(),
                'created_at'  => now(),
            ]);

            $this->redirect(route('dashboard'));
            return;
        }

        $this->failed  = true;
        $this->message = 'الرمز غير صحيح أو منتهي الصلاحية.';
        $this->code    = '';

        DB::table('activity_logs')->insert([
            'user_id'     => $user->getAuthIdentifier(),
            'user_name'   => $user->getName(),
            'action'      => '2fa_failed',
            'subject'     => 'auth',
            'subject_id'  => 0,
            'description' => 'رمز 2FA خاطئ | IP: ' . request()->ip(),
            'created_at'  => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.auth.two-factor')
            ->layout('layouts.guest');
    }
}
