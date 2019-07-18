<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use App\Scope\PublicScope;
use App\Abilities\HasParentMedia;
use App\Abilities\HasParentModel;

/**
 * App\Models\PublicAlbum.
 *
 * @property int                                                                          $id
 * @property string                                                                       $slug
 * @property string                                                                       $title
 * @property string|null                                                                  $body
 * @property string|null                                                                  $published_at
 * @property int                                                                          $private
 * @property int                                                                          $user_id
 * @property \Illuminate\Support\Carbon|null                                              $created_at
 * @property \Illuminate\Support\Carbon|null                                              $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album public()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum wherePrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicAlbum whereUserId($value)
 * @mixin \Eloquent
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Category[]  $categories
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[]   $comments
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Cosplayer[] $cosplayers
 * @property \App\Models\User                                                 $user
 * @property mixed                                                            $cover
 * @property-read mixed $cover_responsive
 */
class PublicAlbum extends Album
{
    use HasParentModel, HasParentMedia;

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new PublicScope());
    }
}
