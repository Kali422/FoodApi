<?php

namespace App\Security;

use App\Security\Exception\TokenValidationException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenValidator
{
    private string $publicKeyPath;

    public function __construct(string $publicKeyPath)
    {
        $this->publicKeyPath = $publicKeyPath;
    }

    public function validate(string $token): void
    {
        try {
            JWT::decode($token, new Key(file_get_contents($this->publicKeyPath),'RS256'));
        } catch (\Throwable $exception) {
            throw new TokenValidationException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}