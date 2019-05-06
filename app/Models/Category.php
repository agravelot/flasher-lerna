<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use App\Abilities\HasNameAsSlug;
use App\Abilities\HasSlugRouteKey;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

/**
 * App\Models\Category.
 *
 * @property int                                                          $id
 * @property string                                                       $name
 * @property string                                                       $slug
 * @property string|null                                                  $description
 * @property \Illuminate\Support\Carbon|null                              $created_at
 * @property \Illuminate\Support\Carbon|null                              $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Album[] $albums
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Post[]  $posts
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\PublicAlbum[] $publishedAlbums
 */
class Category extends Model
{
    use Sluggable, SluggableScopeHelpers, HasSlugRouteKey, HasNameAsSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Posts relationships.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'categorizable');
    }

    /**
     * Albums relationships.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function albums()
    {
        return $this->morphedByMany(Album::class, 'categorizable');
    }

    /**
     * Album relationship, only published.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function publishedAlbums()
    {
        return $this->morphedByMany(PublicAlbum::class, 'categorizable');
    }
}
