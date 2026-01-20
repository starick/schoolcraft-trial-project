<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Firebase\JWT\Key;

class JWTService
{
    public function decode(string $jwt): string
    {
        $decoded = JWT::decode($jwt, JWK::parseKey(config('jwt.public')));
        return json_encode($decoded);
    }

    public function getEmail(string $jwt): string
    {
        return $this->decode($jwt)['email'] ?? null;
    }

    public function isValid(string $jwt): bool
    {
        try {
            $this->decode($jwt);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}