<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Album extends Model implements HasMedia
{
    use Sluggable, SluggableScopeHelpers, HasMediaTrait;

    protected $fillable = [
        'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'published_at', 'user_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function setPublishedAtAttribute($value)
    {
        if ($value === true) {
            $this->attributes['published_at'] = Carbon::now();
        } elseif ($value === false){
            $this->attributes['published_at'] = null;
        } else {
            $this->attributes['published_at'] = $value;
        }
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
        return $this->password === null;
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
