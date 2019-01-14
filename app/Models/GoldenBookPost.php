<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GoldenBookPost.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $email
 * @property string                          $body
 * @property int                             $active
 * @property int|null                        $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\User|null           $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereUserId($value)
 * @mixin \Eloquent
 */
class GoldenBookPost extends Model
{
    protected $fillable = [
        'name', 'body', 'email', 'active',
    ];

    public function scopeActive(Builder $query)
    {
        $query->where('active', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
