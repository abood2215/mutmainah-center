<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class EmployeeUser implements Authenticatable
{
    public function __construct(public readonly object $data) {}

    public function getAuthIdentifierName(): string  { return 'id'; }
    public function getAuthIdentifier(): mixed        { return $this->data->id; }
    public function getAuthPasswordName(): string    { return 'arway'; }
    public function getAuthPassword(): string        { return $this->data->arway; }
    public function getRememberToken(): ?string      { return null; }
    public function setRememberToken($value): void   {}
    public function getRememberTokenName(): string   { return ''; }

    public function getName(): string
    {
        return trim($this->data->first_name . ' ' . $this->data->middle_initial);
    }
}
