<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tag'];


    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function albums()
    {
        return $this->morphedByMany(Album::class, 'taggable');
    }
}
