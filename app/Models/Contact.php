<?php

namespace App\Models;

use App\Facades\Keycloak;
use App\Adapters\Keycloak\UserRepresentation;
use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use ClearsResponseCache;

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
