<?php

namespace App\Models;


class Album extends Post
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'active', 'image', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
     return $this->morphMany('App\Models\Comment', 'commentable');
    }

    public function tags() {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }

    public function pictures() {
        return $this->hasMany(Picture::class);
    }
}
