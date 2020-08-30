<?php

declare(strict_types=1);

namespace Tests\Feature\Guards;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class KeycloakGuardTest extends TestCase
{
    /** @test */
    public function should_not_validate_token_signature(): void
    {
        $response = $this->getJson('/api/admin/albums', ['Authorization' => 'Bearer '.$this->token()]);

        $response->assertOk();
    }

    private function token($roles = ['admin']): string
    {
        // Prepare private/public keys and a default JWT token, with a simple payload

        $privateKey = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $publicKey = openssl_pkey_get_details($privateKey)['key'];

        $payload = [
            'sub' => '42',
            'email' => 'johndoe@example.com',
            'preferred_username' => 'johndoe',
            'email_verified' => true,
            'resource_access' => ['account' => []],
            'realm_access' => [
                'roles' => $roles,
            ],
            'scope' => 'openid email profile',

        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    /** @test */
    public function non_logged_in_user_should_return_401(): void
    {
        Route::get('/api/test-route', static fn () => json_encode(auth()->user()))
            ->name('api.test-route')
            ->middleware('auth:api');

        $this->getJson('/api/test-route')
            ->assertStatus(401);
    }

    /** @test */
    public function user_send_jwt_to_api_is_correctly_authenticated(): void
    {
        Route::get('/api/test-route', static fn () => json_encode(auth()->user()))
            ->name('api.test-route')
            ->middleware('auth:api');

        $this->getJson('/api/test-route', ['Authorization' => 'Bearer '.$this->token([])])
            ->assertJsonPath('id', '42')
            ->assertJsonPath('username', 'johndoe')
            ->assertJsonPath('email', 'johndoe@example.com')
            ->assertJsonPath('email_verified', true);
    }

    /** @test */
    public function admin_should_be_able_to_access_admin_resource(): void
    {
        Route::get('/api/test-route', static fn () => json_encode(auth()->user()))
            ->name('api.test-route')
            ->middleware(['auth:api', 'admin']);

        $this->getJson('/api/test-route', ['Authorization' => 'Bearer '.$this->token()])
            ->assertJsonPath('id', '42')
            ->assertJsonPath('username', 'johndoe')
            ->assertJsonPath('email', 'johndoe@example.com')
            ->assertJsonPath('email_verified', true);
    }

    /** @test */
    public function user_should_not_be_able_to_access_admin_resource(): void
    {
        Route::get('/api/test-route', static fn () => json_encode(auth()->user()))
            ->name('api.test-route')
            ->middleware(['auth:api', 'admin']);

        $this->getJson('/api/test-route', ['Authorization' => 'Bearer '.$this->token(['user'])])
            ->assertStatus(403);
    }
}
