<?php

namespace Tests\Unit\Jobs;

use App\Jobs\GenerateSitemap;
use App\Jobs\NotifySitemapUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\ModelTestCase;

class GenerateSitemapTest extends ModelTestCase
{
    use RefreshDatabase;

    public function test_generate_sitemap_will_trigger_notify_sitemap_update(): void
    {
        Queue::fake();
        Queue::assertNothingPushed();

        Queue::push(new GenerateSitemap());

        Queue::assertPushedWithChain(GenerateSitemap::class, [
            NotifySitemapUpdate::class,
        ]);
    }
}
