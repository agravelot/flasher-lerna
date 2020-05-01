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
        $user->email = $data['email'] ?? null;
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
            $response->throw();
        }

        return $this->createUser($response->json());
    }

    public function create(UserRepresentation $user): void
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->post("/admin/realms/$realm/users", $user->toArray());

        if ($response->status() !== 201) {
            $response->throw();
        }
    }

    public function update(array $data)
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->put("/admin/realms/$realm/users", $data);

        if (!$response->ok()) {
            $response->throw();
        }
    }

    public function delete(string $ssoId): void
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->withToken($this->keycloak->getAccessToken())
            ->delete("/admin/realms/$realm/users/$ssoId");

        if ($response->status() !== 204) {
            $response->throw();
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
