<?php

namespace Tests\Jobs;

use Tests\ModelTestCase;
use App\Jobs\GenerateSitemap;
use App\Jobs\NotifySitemapUpdate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
