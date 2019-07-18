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
use App\Abilities\HasParentModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\PublishedGoldenBookPost.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $body
 * @property string|null $published_at
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PublishedGoldenBookPost newModelQuery()
 * @method static Builder|PublishedGoldenBookPost newQuery()
 * @method static Builder|GoldenBookPost published()
 * @method static Builder|PublishedGoldenBookPost query()
 * @method static Builder|PublishedGoldenBookPost whereBody($value)
 * @method static Builder|PublishedGoldenBookPost whereCreatedAt($value)
 * @method static Builder|PublishedGoldenBookPost whereEmail($value)
 * @method static Builder|PublishedGoldenBookPost whereId($value)
 * @method static Builder|PublishedGoldenBookPost whereName($value)
 * @method static Builder|PublishedGoldenBookPost wherePublishedAt($value)
 * @method static Builder|PublishedGoldenBookPost whereUpdatedAt($value)
 * @method static Builder|PublishedGoldenBookPost whereUserId($value)
 * @mixin Eloquent
 * @property User|null $user
 */
class PublishedGoldenBookPost extends GoldenBookPost
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
