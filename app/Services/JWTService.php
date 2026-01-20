<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Firebase\JWT\Key;

class JWTService
{
    public function decode(string $jwt)
    {
        $decoded = JWT::decode($jwt, JWK::parseKey(config('jwt.public')));
        return $decoded;
    }

    public function isValid($jwt): bool
    {
        if (is_string($jwt) === false || empty($jwt)) {
            return false;
        }
        try {
            $this->decode($jwt);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}