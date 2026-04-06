<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FAQRCode\Google2FA;

class TwoFactorSetup extends Component
{
    public string $secret   = '';
    public string $qrUrl    = '';
    public string $code     = '';
    public bool   $enabled  = false;
    public bool   $success  = false;
    public bool   $failed   = false;
    public string $message  = '';

    public function mount(): void
    {
        $user          = Auth::user();
        $this->enabled = (bool) $user->two_factor_enabled;

        if (!$this->enabled) {
            $google       = new Google2FA();
            $this->secret = $user->two_factor_secret
                ?: $google->generateSecretKey();

            // احفظ السر مؤقتاً حتى يتحقق المستخدم
            if (!$user->two_factor_secret) {
                DB::table('employees')
                    ->where('id', $user->getAuthIdentifier())
                    ->update(['two_factor_secret' => $this->secret]);
            }

            $this->qrUrl = $google->getQRCodeInline(
                config('app.name'),
                $user->getName(),
                $this->secret
            );
        }
    }

    public function confirm(): void
    {
        $this->failed  = false;
        $this->message = '';

        $google = new \PragmaRX\Google2FA\Google2FA();
        $user   = Auth::user();

        $valid = $google->verifyKey($this->secret, trim($this->code), 2);

        if ($valid) {
            DB::table('employees')
                ->where('id', $user->getAuthIdentifier())
                ->update([
                    'two_factor_secret'  => $this->secret,
                    'two_factor_enabled' => true,
                ]);

            session(['2fa_verified' => true]);

            DB::table('activity_logs')->insert([
                'user_id'     => $user->getAuthIdentifier(),
                'user_name'   => $user->getName(),
                'action'      => '2fa_enabled',
                'subject'     => 'auth',
                'subject_id'  => 0,
                'description' => 'تم تفعيل المصادقة الثنائية',
                'created_at'  => now(),
            ]);

            $this->success = true;
            $this->enabled = true;
            $this->message = 'تم تفعيل المصادقة الثنائية بنجاح!';
        } else {
            $this->failed  = true;
            $this->message = 'الرمز غير صحيح. حاول مرة أخرى.';
            $this->code    = '';
        }
    }

    public function disable(): void
    {
        $user = Auth::user();

        DB::table('employees')
            ->where('id', $user->getAuthIdentifier())
            ->update([
                'two_factor_secret'  => null,
                'two_factor_enabled' => false,
            ]);

        DB::table('activity_logs')->insert([
            'user_id'     => $user->getAuthIdentifier(),
            'user_name'   => $user->getName(),
            'action'      => '2fa_disabled',
            'subject'     => 'auth',
            'subject_id'  => 0,
            'description' => 'تم إلغاء تفعيل المصادقة الثنائية',
            'created_at'  => now(),
        ]);

        session()->forget('2fa_verified');
        $this->redirect(route('dashboard'));
    }

    #[Title('إعداد المصادقة الثنائية')]
    public function render()
    {
        return view('livewire.auth.two-factor-setup')
            ->layout('layouts.app');
    }
}
