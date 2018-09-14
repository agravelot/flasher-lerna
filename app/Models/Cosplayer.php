<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cosplayer extends Model
{
    protected $fillable = ['name', 'picture'];

    public function albums()
    {
        return $this->belongsToMany(Album::class);
    }

    public function categories() {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
