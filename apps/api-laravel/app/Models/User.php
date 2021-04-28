<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use LogicException;
use Vizir\KeycloakWebGuard\Models\KeycloakUser;

class User extends KeycloakUser implements MustVerifyEmail
{
    use Notifiable, Authorizable;

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
       'id', 'username', 'email', 'email_verified', 'realm_access', 'realm_roles', 'resource_access', 'notify_on_album_published', 'enabled',
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
        return $this->id;
    }

    public function hasVerifiedEmail(): bool
    {
        if (optional($this->token)->email_verified === true) {
            return true;
        }

        return $this->email_verified ?? false;
    }

    public function markEmailAsVerified(): bool
    {
        throw new LogicException('This method should not be executed.');
    }

    public function sendEmailVerificationNotification(): void
    {
        throw new LogicException('This method should not be executed.');
    }

    public function getEmailForVerification(): string
    {
        throw new LogicException('This method should not be executed.');
    }
}
