<?php

declare(strict_types=1);

namespace App\Models;

use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'email', 'message'];

    public function user(): ?UserRepresentation
    {
        return $this->sso_id ? Keycloak::users()->find($this->sso_id) : null;
    }
}
