<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GooldenBookPost extends Model
{
    protected $fillable = [
        'name', 'content', 'active'
    ];
}
