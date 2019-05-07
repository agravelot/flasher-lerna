<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\GoldenBookPost.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $email
 * @property string                          $body
 * @property int                             $active
 * @property int|null                        $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\User|null           $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereUserId($value)
 * @mixin \Eloquent
 *
 * @property string|null $published_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost published()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost wherePublishedAt($value)
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
    public function isPublished()
    {
        return $this->published_at != null;
    }

    /**
     * Scope for published golden book posts.
     *
     * @param Builder $query
     */
    public function scopePublished(Builder $query)
    {
        $query->whereNotNull('published_at');
    }

    /**
     * Publish the album.
     *
     * @return $this
     */
    public function publish()
    {
        $this->published_at = $this->freshTimestamp();

        return $this;
    }

    /**
     * Un-publish the album.
     *
     * @return $this
     */
    public function unpublish()
    {
        $this->published_at = null;

        return $this;
    }

    /**
     * Return the related user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
