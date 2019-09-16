<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use App\Abilities\HasParentModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\PublishedTestimonial.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $body
 * @property string|null $published_at
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PublishedTestimonial newModelQuery()
 * @method static Builder|PublishedTestimonial newQuery()
 * @method static Builder|Testimonial published()
 * @method static Builder|PublishedTestimonial query()
 * @method static Builder|PublishedTestimonial whereBody($value)
 * @method static Builder|PublishedTestimonial whereCreatedAt($value)
 * @method static Builder|PublishedTestimonial whereEmail($value)
 * @method static Builder|PublishedTestimonial whereId($value)
 * @method static Builder|PublishedTestimonial whereName($value)
 * @method static Builder|PublishedTestimonial wherePublishedAt($value)
 * @method static Builder|PublishedTestimonial whereUpdatedAt($value)
 * @method static Builder|PublishedTestimonial whereUserId($value)
 * @mixin Eloquent
 * @property User|null $user
 */
class PublishedTestimonial extends Testimonial
{
    use HasParentModel;

    public static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(static function ($query) {
            $query->published();
        });
    }
}
