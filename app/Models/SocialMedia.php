<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\SocialMedia.
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $icon
 * @property string $color
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SocialMedia active()
 * @method static Builder|SocialMedia newModelQuery()
 * @method static Builder|SocialMedia newQuery()
 * @method static Builder|SocialMedia query()
 * @method static Builder|SocialMedia whereActive($value)
 * @method static Builder|SocialMedia whereColor($value)
 * @method static Builder|SocialMedia whereCreatedAt($value)
 * @method static Builder|SocialMedia whereIcon($value)
 * @method static Builder|SocialMedia whereId($value)
 * @method static Builder|SocialMedia whereName($value)
 * @method static Builder|SocialMedia whereUpdatedAt($value)
 * @method static Builder|SocialMedia whereUrl($value)
 * @mixin Eloquent
 */
class SocialMedia extends Model
{
    use ClearsResponseCache;
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
