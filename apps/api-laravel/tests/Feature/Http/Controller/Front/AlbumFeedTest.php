<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front;

use App\Models\Album;
use Tests\TestCase;

class AlbumFeedTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $response = $this->get('/feed');

        $response->assertOk();
    }
}
