<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldenBookPost extends Model
{
    protected $fillable = [
        'name', 'content', 'active'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
