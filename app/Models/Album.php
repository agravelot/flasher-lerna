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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * App\Models\Album.
 *
 * @property int                                                                          $id
 * @property string                                                                       $slug
 * @property string                                                                       $title
 * @property string|null                                                                  $body
 * @property string|null                                                                  $published_at
 * @property string|null                                                                  $password
 * @property int                                                                          $user_id
 * @property \Illuminate\Support\Carbon|null                                              $created_at
 * @property \Illuminate\Support\Carbon|null                                              $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Category[]              $categories
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[]               $comments
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Cosplayer[]             $cosplayers
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property \App\Models\User                                                             $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereUserId($value)
 * @mixin \Eloquent
 *
 * @property int $private
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album public()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album wherePrivate($value)
 */
class Album extends Model implements HasMedia
{
    use Sluggable, SluggableScopeHelpers, HasMediaTrait;

    protected $fillable = [
        'title',
        'slug',
        'seo_title',
        'excerpt',
        'body',
        'meta_description',
        'meta_keywords',
        'published_at',
        'user_id',
        'private',
    ];

    public function scopePublic(Builder $query)
    {
        $query->whereNotNull('published_at')
            ->where('private', false);
    }

    public function isPublic()
    {
        return $this->isPublished() && $this->isPasswordLess();
    }

    public function isPublished()
    {
        return $this->published_at !== null;
    }

    public function isPasswordLess()
    {
        return $this->private == false;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function cosplayers()
    {
        return $this->belongsToMany(Cosplayer::class);
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
                'source' => 'title',
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
        $this->addMediaCollection('pictures')
            ->acceptsFile(function (File $file) {
                return mb_strpos($file->mimeType, 'image/') === 0;
            });
    }

    /**
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('pictures');
    }
}
