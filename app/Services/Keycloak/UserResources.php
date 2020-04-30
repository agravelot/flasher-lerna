<?php

namespace App\Services\Keycloak;

use App\Models\User;

class UserResources
{
    private Keycloak $keycloak;

    public function __construct(Keycloak $keycloak)
    {
        $this->keycloak = $keycloak;
    }

    public function all(): array
    {
        $realm = $this->keycloak->realm;

        return $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->get("/admin/realms/$realm/users")
            ->json();
    }

    public function find(string $ssoId): array
    {
        $realm = $this->keycloak->realm;

        return $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->get("/admin/realms/$realm/users/$ssoId")
            ->json();
    }

    public function create(User $user)
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->post("/admin/realms/$realm/users", [
                'email' => $user->email,
                'username' => $user->name,
                'emailVerified' => (bool) $user->email_verified_at,
                'enabled' => true,
                'totp' => false,
                'credentials' => [new Credential($user->password)],
                'groups' => $user->role === 'admin' ? ['admin'] : [],
                'attributes' => [
                    'notifyOnAlbumPublished' => $user->notify_on_album_published,
                ],
            ]);
    }

    public function update(array $data)
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->put("/admin/realms/$realm/users", $data);
    }

    public function delete(string $ssoId): void
    {
        $realm = $this->keycloak->realm;
        $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->delete("/admin/realms/$realm/users/$ssoId")
            ->json();
    }

    public function count(): int
    {
        $realm = $this->keycloak->realm;

        return  (int) $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->get("/admin/realms/$realm/users/count")
            ->json();
    }
}
