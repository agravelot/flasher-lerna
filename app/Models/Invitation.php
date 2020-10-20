<?php

declare(strict_types=1);

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    use Uuids;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $fillable = ['email', 'cosplayer_id', 'message', 'confirmed_at'];
    protected $dates = ['confirmed_at'];

    protected $with = ['cosplayer'];

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
