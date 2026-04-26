<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\DB;

class EmployeeUserProvider implements UserProvider
{
    public function retrieveById($identifier): ?Authenticatable
    {
        $row = DB::table('employees')->where('id', $identifier)->where('state', 1)->first();
        return $row ? new EmployeeUser($row) : null;
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable { return null; }
    public function updateRememberToken(Authenticatable $user, $token): void {}

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $row = DB::table('employees')
            ->where('user_name', $credentials['user_name'])
            ->where('state', 1)
            ->first();
        return $row ? new EmployeeUser($row) : null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $stored   = $user->getAuthPassword();
        $plain    = $credentials['password'];

        // bcrypt (الحسابات المُرقَّاة)
        if (str_starts_with($stored, '$2y$') || str_starts_with($stored, '$2b$')) {
            return password_verify($plain, $stored);
        }

        // MD5 legacy — تحقق ثم رقِّ تلقائياً
        if ($stored === md5($plain)) {
            DB::table('employees')
                ->where('id', $user->getAuthIdentifier())
                ->update(['arway' => password_hash($plain, PASSWORD_BCRYPT)]);
            return true;
        }

        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void {}
}
