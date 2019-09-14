<?php

namespace App\Traits;

use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache()
    {
        self::created(static function () {
            ResponseCache::clear();
        });

        self::updated(static function () {
            ResponseCache::clear();
        });

        self::deleted(static function () {
            ResponseCache::clear();
        });
    }
}
