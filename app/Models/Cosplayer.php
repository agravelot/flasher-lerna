<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * App\Models\Cosplayer.
 *
 * @property int                                                                          $id
 * @property string                                                                       $name
 * @property string                                                                       $slug
 * @property string|null                                                                  $description
 * @property string|null                                                                  $picture
 * @property int|null                                                                     $user_id
 * @property \Illuminate\Support\Carbon|null                                              $created_at
 * @property \Illuminate\Support\Carbon|null                                              $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Album[]                 $albums
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Category[]              $categories
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property \App\Models\User|null                                                        $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereUserId($value)
 * @mixin \Eloquent
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\PublicAlbum[] $publicAlbums
 * @property mixed                                                              $initial
 */
class Cosplayer extends Model implements HasMedia
{
    use Sluggable, SluggableScopeHelpers, HasMediaTrait;

    protected $fillable = ['name', 'description', 'slug', 'user_id'];

    public function getInitialAttribute()
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function albums()
    {
        return $this->belongsToMany(Album::class);
    }

    public function publicAlbums()
    {
        return $this->belongsToMany(PublicAlbum::class, 'album_cosplayer');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
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

    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsFile(function (File $file) {
                return mb_strpos($file->mimeType, 'image/') === 0;
            });
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->crop('crop-center', 96, 96)
            ->sharpen(7)
            ->performOnCollections('avatar');
    }
}
