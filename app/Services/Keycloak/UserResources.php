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

        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->get("/admin/realms/$realm/users");

        $users = [];
        foreach ($response->json() as $user) {
            $users[] = $this->createUser($user);
        }

        return $users;
    }

    private function createUser(array $data): UserRepresentation
    {
        $user = new UserRepresentation();
        $user->id = $data['id'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->emailVerified = (bool) $data['emailVerified'];

        return $user;
    }

    public function find(string $ssoId): UserRepresentation
    {
        $realm = $this->keycloak->realm;

        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->get("/admin/realms/$realm/users/$ssoId");

        if (!$response->ok()) {
            throw new \LogicException('User not found');
        }

        return $this->createUser($response->json());
    }

    public function create(User $user): void
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

        if ($response->status() !== 201) {
            throw new \LogicException('Unable to create new user');
        }
    }

    public function update(array $data)
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->put("/admin/realms/$realm/users", $data);

        if (!$response->ok()) {
            throw new \LogicException('Unable to update user');
        }
    }

    public function delete(string $ssoId): void
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->delete("/admin/realms/$realm/users/$ssoId");

        if ($response->status() !== 204) {
            throw new \LogicException('Unable to delete user');
        }
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
