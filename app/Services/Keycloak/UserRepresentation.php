<?php

namespace App\Services\Keycloak;

/**
 * Class UserRepresentation according keycloak api.
 *
 * @see https://www.keycloak.org/docs-api/10.0/rest-api/index.html#_userrepresentation
 */
class UserRepresentation
{
    public array $access = [];
    public array $attributes = [];
    public array $clientConsents = [];
    public int $createdTimestamp;
    public array $credentials = [];
    public ?string $email;
    public bool $emailVerified;
    public bool $enabled;
    public bool $totp;
    public ?string $firstName;
    public string $id;
    public ?string $lastName;
    public array $realmRoles = [];
    public array $groups = [];
    public string $username;

    public function addCredential(Credential $credential): void
    {
        $this->credentials[] = $credential;
    }

    public function addGroup(string $group): void
    {
        $this->groups[] = $group;
    }

    public static function fromArray(array $data): self {
        $user = new self();
        $user->id = $data['id'];
        $user->username = $data['username'];
        $user->email = $data['email'] ?? null;
        $user->emailVerified = $data['emailVerified'];
        $user->groups = $data['groups'] ?? [];
        $user->enabled = $data['enabled'];
        $user->firstName = $data['firstName'] ?? null;
        $user->lastName = $data['lastName'] ?? null;

        return $user;
    }

    public function toArray(): array
    {
        $credentials = [];
        foreach ($this->credentials as $credential) {
            $credentials[] = (array) $credential;
        }

        return [
            'email' => $this->email,
            'username' => $this->username,
            'emailVerified' => $this->emailVerified,
            'enabled' => true,
            'totp' => false,
            'credentials' => $credentials,
            'groups' => $this->groups,
            'attributes' => $this->attributes,
        ];
    }
}
