<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GoldenBookPost.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $body
 * @property int $active
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User|null $user
 * @method static Builder|GoldenBookPost newModelQuery()
 * @method static Builder|GoldenBookPost newQuery()
 * @method static Builder|GoldenBookPost query()
 * @method static Builder|GoldenBookPost whereActive($value)
 * @method static Builder|GoldenBookPost whereBody($value)
 * @method static Builder|GoldenBookPost whereCreatedAt($value)
 * @method static Builder|GoldenBookPost whereEmail($value)
 * @method static Builder|GoldenBookPost whereId($value)
 * @method static Builder|GoldenBookPost whereName($value)
 * @method static Builder|GoldenBookPost whereUpdatedAt($value)
 * @method static Builder|GoldenBookPost whereUserId($value)
 * @mixin Eloquent
 * @property string|null $published_at
 * @method static Builder|GoldenBookPost published()
 * @method static Builder|GoldenBookPost wherePublishedAt($value)
 */
class GoldenBookPost extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'body', 'email', 'published_at',
    ];

    /**
     * Return if the golden book post is published or not.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * Scope for published golden book posts.
     *
     * @param  Builder  $query
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
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
