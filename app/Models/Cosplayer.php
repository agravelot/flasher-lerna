<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cosplayer
 *
 * @property int $id
 * @property string $name
 * @property string|null $pîcture
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Album[] $albums
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer wherePîcture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cosplayer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
