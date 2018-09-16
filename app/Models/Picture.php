<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Picture
 *
 * @property int $id
 * @property string $filename
 * @property int $album_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Album $album
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Picture extends Model
{
    protected $fillable = ['filename'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
