<?php

namespace App\Traits;

use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache(): void
    {
        self::created(static function (): void {
            ResponseCache::clear();
        });

        self::updated(static function (): void {
            ResponseCache::clear();
        });

        self::deleted(static function (): void {
            ResponseCache::clear();
        });
    }
}
