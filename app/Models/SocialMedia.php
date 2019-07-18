<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\SocialMedia.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $url
 * @property string                          $icon
 * @property string                          $color
 * @property int                             $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|\App\Models\SocialMedia active()
 * @method static Builder|\App\Models\SocialMedia newModelQuery()
 * @method static Builder|\App\Models\SocialMedia newQuery()
 * @method static Builder|\App\Models\SocialMedia query()
 * @method static Builder|\App\Models\SocialMedia whereActive($value)
 * @method static Builder|\App\Models\SocialMedia whereColor($value)
 * @method static Builder|\App\Models\SocialMedia whereCreatedAt($value)
 * @method static Builder|\App\Models\SocialMedia whereIcon($value)
 * @method static Builder|\App\Models\SocialMedia whereId($value)
 * @method static Builder|\App\Models\SocialMedia whereName($value)
 * @method static Builder|\App\Models\SocialMedia whereUpdatedAt($value)
 * @method static Builder|\App\Models\SocialMedia whereUrl($value)
 * @mixin \Eloquent
 */
class SocialMedia extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon', 'url', 'color', 'active'];

    /**
     * Scope for active social medias.
     */
    public function scopeActive(): Builder
    {
        return self::where('active', true);
    }
}
