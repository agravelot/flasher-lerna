<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use App\Abilities\HasParentModelTrait;

/**
 * App\Models\PublishedGoldenBookPost.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $email
 * @property string                          $body
 * @property string|null                     $published_at
 * @property int|null                        $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost published()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublishedGoldenBookPost whereUserId($value)
 * @mixin \Eloquent
 */
class PublishedGoldenBookPost extends GoldenBookPost
{
    use HasParentModelTrait;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->published();
        });
    }
}
