<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use ClearsResponseCache;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'body', 'email', 'published_at'];

    protected $dates = ['published_at'];

    /**
     * Return if the golden book post is published or not.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * Scope for published golden book posts.
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
