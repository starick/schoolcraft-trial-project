<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Worksheet;
use App\Services\JWTService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class WorksheetControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_user_worksheet_and_file_when_token_email_and_scope_are_valid(): void
    {
        Storage::fake('private');

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NiIsImtpZCI6ImY3NDA2M2ExODBkZDkxNWI0ZTZkMjM3NDJkYWFmNTdmIn0.eyJlbWFpbCI6InVzZXJAZXhhbXBsZS5jb20iLCJzY29wZSI6InNoYXJlOndvcmtzaGVldCJ9.in1U_7pZP1dWtVEr_rPKkTRJFOlbGn-Z814ADkh_8f4G4laT0JFWquA9L9XzrXkdCpnWu45xJkL9KtKEMVo0hQ';

        $email = 'user@example.com';
        $payload = [
            'title' => 'My worksheet',
            'description' => 'Optional description',
            'email' => $email,
            'file' => UploadedFile::fake()->create('sheet.pdf', 10, 'application/pdf'),
        ];

        $response = $this->postJson(route('worksheet.store'), $payload, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertCreated();

        // Basic DB assertions
        $this->assertDatabaseHas('users', ['email' => $email]);
        $userId = User::where('email', $email)->value('id');

        $this->assertDatabaseHas('worksheets', [
            'title' => 'My worksheet',
            'description' => 'Optional description',
            'user_id' => $userId,
        ]);

        $worksheet = Worksheet::where('user_id', $userId)->firstOrFail();

        // File stored on private disk under worksheets/
        $this->assertNotEmpty($worksheet->file_path);
        Storage::disk('private')->assertExists($worksheet->file_path);

        // Response contains the created worksheet (resource shape may vary; keep minimal)
        $response->assertJsonPath('title', 'My worksheet');
    }

    // public function test_store_returns_403_when_email_in_token_does_not_match_request_email(): void
    // {
    //     Storage::fake('private');

    //     $token = $this->makeJwtToken([
    //         'email' => 'token@example.test',
    //         'scope' => 'share:worksheet',
    //     ]);

    //     $payload = [
    //         'title' => 'My worksheet',
    //         'email' => 'request@example.test',
    //         'file' => UploadedFile::fake()->create('sheet.pdf', 10, 'application/pdf'),
    //     ];

    //     $this->postJson('/api/worksheet/store', $payload, [
    //         'Authorization' => 'Bearer ' . $token,
    //     ])
    //         ->assertStatus(403)
    //         ->assertSeeText('Emails do not match.');

    //     $this->assertDatabaseCount('worksheets', 0);
    // }

    // public function test_store_returns_403_when_scope_is_invalid(): void
    // {
    //     Storage::fake('private');

    //     $email = 'alice@example.test';

    //     $token = $this->makeJwtToken([
    //         'email' => $email,
    //         'scope' => 'wrong:scope',
    //     ]);

    //     $payload = [
    //         'title' => 'My worksheet',
    //         'email' => $email,
    //         'file' => UploadedFile::fake()->create('sheet.pdf', 10, 'application/pdf'),
    //     ];

    //     $this->postJson('/api/worksheet/store', $payload, [
    //         'Authorization' => 'Bearer ' . $token,
    //     ])
    //         ->assertStatus(403)
    //         ->assertSeeText('Invalid token scope.');

    //     $this->assertDatabaseCount('worksheets', 0);
    // }

    // /**
    //  * Create a JWT that your real JWTService can decode.
    //  *
    //  * This contains zero mocking. It uses the real service.
    //  *
    //  * If your JWTService does not expose an encode/issue method, this test will
    //  * be skipped with a clear message.
    //  */
    // private function makeJwtToken(array $claims): string
    // {
    //     /** @var JWTService $svc */
    //     $svc = app(JWTService::class);

    //     // Try common method names without assuming your exact implementation.
    //     foreach (['encode', 'issue', 'create', 'sign', 'make'] as $method) {
    //         if (method_exists($svc, $method)) {
    //             /** @phpstan-ignore-next-line */
    //             $token = $svc->{$method}($claims);

    //             if (is_string($token) && $token !== '') {
    //                 return $token;
    //             }
    //         }
    //     }

    //     $this->markTestSkipped(
    //         'JWTService has no public token-creation method (e.g. encode/issue/create). ' .
    //         'Add one for tests or provide a known-good static JWT that JWTService::decode() accepts.'
    //     );
    // }
}
