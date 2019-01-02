<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

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
