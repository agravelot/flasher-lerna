<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = ['email', 'cosplayer_id', 'message', 'confirmed_at'];

    public function cosplayer(): BelongsTo
    {
        return $this->belongsTo(Cosplayer::class);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'token';
    }
}
