<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = ['filePath', 'mineType', 'originalName', 'hashName', 'size', 'extension'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function albumHeader() {
        return $this->belongsTo(Album::class);
    }

    public function post()
    {
        return $this->hasOne(Picture::class);
    }
}
