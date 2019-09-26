<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use ClearsResponseCache;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'message'];

    /**
     * Return the related user of this contact (is nullable).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
