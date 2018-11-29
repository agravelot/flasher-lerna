<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldenBookPost extends Model
{
    protected $fillable = [
        'name', 'body', 'email', 'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
