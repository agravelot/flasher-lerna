<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GoldenBookPost
 *
 * @property int $id
 * @property string $name
 * @property string $content
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GoldenBookPost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoldenBookPost extends Model
{
    protected $fillable = [
        'name', 'content', 'active'
    ];
}
