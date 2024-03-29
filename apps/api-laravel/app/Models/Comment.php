<?php

declare(strict_types=1);

namespace App\Models;

use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['body', 'post_id', 'user_id', 'parent_id'];

    public function user(): ?UserRepresentation
    {
        return $this->sso_id ? Keycloak::users()->find($this->sso_id) : null;
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
