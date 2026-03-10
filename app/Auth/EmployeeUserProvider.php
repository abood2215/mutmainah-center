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
        return $user->getAuthPassword() === md5($credentials['password']);
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void {}
}
