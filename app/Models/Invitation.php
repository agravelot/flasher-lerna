<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = ['email', 'cosplayer_id', 'message', 'confirmed_at'];
    protected $dates = ['confirmed_at'];

    public function isExpired(): bool
    {
        return $this->created_at->addDays(14)->lessThan(now());
    }

    public function isAccepted(): bool
    {
        return $this->confirmed_at !== null;
    }

    public function cosplayer(): BelongsTo
    {
        return $this->belongsTo(Cosplayer::class);
    }
}
