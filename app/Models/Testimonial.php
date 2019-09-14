<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Testimonial.
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
 * @method static Builder|Testimonial newModelQuery()
 * @method static Builder|Testimonial newQuery()
 * @method static Builder|Testimonial query()
 * @method static Builder|Testimonial whereActive($value)
 * @method static Builder|Testimonial whereBody($value)
 * @method static Builder|Testimonial whereCreatedAt($value)
 * @method static Builder|Testimonial whereEmail($value)
 * @method static Builder|Testimonial whereId($value)
 * @method static Builder|Testimonial whereName($value)
 * @method static Builder|Testimonial whereUpdatedAt($value)
 * @method static Builder|Testimonial whereUserId($value)
 * @mixin Eloquent
 * @property Carbon|null $published_at
 * @method static Builder|Testimonial published()
 * @method static Builder|Testimonial wherePublishedAt($value)
 */
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
