<?php

declare(strict_types=1);

namespace Tests\Feature\Guards;

use Firebase\JWT\JWT;
use Tests\TestCase;

class KeycloakGuardTest extends TestCase
{
    /** @test */
    public function should_not_validate_token_signature(): void
    {
        $response = $this->json('get', '/api/admin/albums', [], ['Authorization' => 'Bearer '.$this->token()]);

        $response->assertOk();
    }

    private function token(): string
    {
        // Prepare private/public keys and a default JWT token, with a simple payload

        $privateKey = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $publicKey = openssl_pkey_get_details($privateKey)['key'];

        $payload = [
            'preferred_username' => 'johndoe',
            'email_verified' => true,
            'resource_access' => ['account' => []],
            'realm_access' => [
                'roles' => ['admin'],
            ],
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }
}
