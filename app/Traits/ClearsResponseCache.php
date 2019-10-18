<?php

namespace App\Traits;

use App\Jobs\GenerateSitemap;
use App\Jobs\NotifySitemapUpdate;
use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache(): void
    {
        self::created(static function (): void {
            ResponseCache::clear();
            GenerateSitemap::withChain([
                new NotifySitemapUpdate(),
            ])->dispatch();
        });

        self::updated(static function (): void {
            ResponseCache::clear();
            GenerateSitemap::withChain([
                new NotifySitemapUpdate(),
            ])->dispatch();
        });

        self::deleted(static function (): void {
            ResponseCache::clear();
            GenerateSitemap::withChain([
                new NotifySitemapUpdate(),
            ])->dispatch();
        });
    }
}
