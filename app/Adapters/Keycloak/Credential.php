<?php

declare(strict_types=1);

namespace App\Adapters\Keycloak;

class Credential
{
    public string $algorithm = 'bcrypt';
    public string $hashedSaltedValue;
    public int $hashIterations;
    public string $type = 'password';
    public bool $temporary = true;

    public function __construct(string $hashedSaltedValue)
    {
        $this->hashedSaltedValue = $hashedSaltedValue;
        $this->hashIterations = (int) config('hashing.bcrypt.rounds');
    }
}
