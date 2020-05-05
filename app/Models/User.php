<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Vizir\KeycloakWebGuard\Models\KeycloakUser;

class User extends KeycloakUser
{
    use Notifiable;

    // Set profile as empty array to work with api.
    public function __construct(array $profile = [])
    {
        parent::__construct($profile);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
       'sub', 'preferred_username', 'email', 'emailVerified', 'realm_access', 'resource_access', 'attributes',
    ];

    /**
     * Return if this user is an admin.
     */
    public function isAdmin(): bool
    {
        // Web
        if ($this->realm_access) {
            return in_array('admin', $this->realm_access['roles'], true);
        }

        // Api
        return $this->token && in_array('admin', $this->token->realm_access->roles, true);
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey(): ?string
    {
        return $this->sub;
    }
}
