<?php

namespace App\Models;


use App\User;

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
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
     return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function pictures() {
        return $this->hasMany(Picture::class);
    }
}
