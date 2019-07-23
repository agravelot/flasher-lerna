<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Eloquent;
use App\Scope\PublicScope;
use Illuminate\Support\Carbon;
use App\Abilities\HasParentMedia;
use App\Abilities\HasParentModel;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * App\Models\PublicAlbum.
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $body
 * @property string|null $published_at
 * @property int $private
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Media[] $media
 * @method static Builder|Album findSimilarSlugs($attribute, $config, $slug)
 * @method static Builder|PublicAlbum newModelQuery()
 * @method static Builder|PublicAlbum newQuery()
 * @method static Builder|Album public ()
 * @method static Builder|PublicAlbum query()
 * @method static Builder|PublicAlbum whereBody($value)
 * @method static Builder|PublicAlbum whereCreatedAt($value)
 * @method static Builder|PublicAlbum whereId($value)
 * @method static Builder|PublicAlbum wherePrivate($value)
 * @method static Builder|PublicAlbum wherePublishedAt($value)
 * @method static Builder|PublicAlbum whereSlug($value)
 * @method static Builder|PublicAlbum whereTitle($value)
 * @method static Builder|PublicAlbum whereUpdatedAt($value)
 * @method static Builder|PublicAlbum whereUserId($value)
 * @mixin Eloquent
 * @property Collection|Category[] $categories
 * @property Collection|Comment[] $comments
 * @property Collection|Cosplayer[] $cosplayers
 * @property User $user
 * @property mixed $cover
 * @property-read mixed $cover_responsive
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album public()
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
