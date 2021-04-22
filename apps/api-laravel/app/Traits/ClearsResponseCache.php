<?php

declare(strict_types=1);

namespace App\Traits;

use App\Jobs\NotifySitemapUpdate;
use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache(): void
    {
        self::created(static function (): void {
            ResponseCache::clear();
            dispatch(new NotifySitemapUpdate());
        });

        self::updated(static function (): void {
            ResponseCache::clear();
            dispatch(new NotifySitemapUpdate());
        });

        self::deleted(static function (): void {
            ResponseCache::clear();
            dispatch(new NotifySitemapUpdate());
        });
    }
}
