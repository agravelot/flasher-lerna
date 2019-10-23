<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = ['email', 'cosplayer_id', 'message'];

    public function cosplayer(): BelongsTo
    {
        return $this->belongsTo(Cosplayer::class);
    }
}
