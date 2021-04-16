<?php

declare(strict_types=1);

namespace App\Models;

use App\Abilities\HasSlugRouteKey;
use App\Abilities\HasTitleAsSlug;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use App\Traits\ClearsResponseCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Post extends Model
{
    use Sluggable, HasSlugRouteKey, HasTitleAsSlug, ClearsResponseCache;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'active', 'user_id',
    ];

    public function user(): ?UserRepresentation
    {
        return $this->sso_id ? Keycloak::users()->find($this->sso_id) : null;
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
