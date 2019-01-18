<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

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
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\PublicAlbum[] $publishedAlbums
 */
class Category extends Model
{
    use Sluggable, SluggableScopeHelpers;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'categorizable');
    }

    public function albums()
    {
        return $this->morphedByMany(Album::class, 'categorizable');
    }

    public function publishedAlbums()
    {
        return $this->morphedByMany(PublicAlbum::class, 'categorizable');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
