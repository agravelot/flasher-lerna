<?php

namespace App\Models;

use App\Facades\Keycloak;
use App\Adapters\Keycloak\UserRepresentation;
use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use ClearsResponseCache;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'body', 'email', 'published_at', 'sso_id'];

    /**
     * @var array<string>
     */
    protected $dates = ['published_at'];

    /**
     * Return if the testimonial post is published or not.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * Scope for published testimonial posts.
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }

    /**
     * Publish the album.
     *
     * @return $this
     */
    public function publish(): self
    {
        $this->published_at = $this->freshTimestamp();

        return $this;
    }

    /**
     * Un-publish the album.
     *
     * @return $this
     */
    public function unpublish(): self
    {
        $this->published_at = null;

        return $this;
    }

    /**
     * Return the related user.
     */
    public function user(): ?UserRepresentation
    {
        return $this->sso_id ? Keycloak::users()->first($this->sso_id) : null;
    }
}
