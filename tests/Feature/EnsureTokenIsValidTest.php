<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\EnsureTokenIsValid;
use App\Services\JWTService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

final class EnsureTokenIsValidTest extends TestCase
{
    public function test_api_test_returns_403_when_token_is_missing(): void
    {
        $this->getJson('/api/test')
            ->assertStatus(403)
            ->assertSeeText('Invalid token.');
    }

    public function test_api_test_returns_403_when_token_is_invalid(): void
    {
        // Token that JWTService::isValid() should reject
        $this->getJson('/api/test', [
            'Authorization' => 'Bearer invalid-token',
        ])
            ->assertStatus(403)
            ->assertSeeText('Invalid token.');
    }

    public function test_api_test_returns_200_when_token_is_valid(): void
    {
        $validToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NiIsImtpZCI6ImY3NDA2M2ExODBkZDkxNWI0ZTZkMjM3NDJkYWFmNTdmIn0.eyJlbWFpbCI6InVzZXJAZXhhbXBsZS5jb20iLCJzY29wZSI6InNoYXJlOndvcmtzaGVldCJ9.in1U_7pZP1dWtVEr_rPKkTRJFOlbGn-Z814ADkh_8f4G4laT0JFWquA9L9XzrXkdCpnWu45xJkL9KtKEMVo0hQ';

        $this->getJson('/api/test', [
            'Authorization' => 'Bearer ' . $validToken,
        ])
            ->assertOk();
    }
}
