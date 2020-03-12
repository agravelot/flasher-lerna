<?php

namespace Tests\Feature\Http\Controller\Api\AdminMedia;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class StoreAdminAlbumMediaWebhookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function send_webhook_will_trigger_media_processing_job(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->sendMediaAddedWebhook('https://url.test/image.png', $album->slug, 'collection');

        $response->assertNoContent();
    }

    /** @test */
    public function send_webhook_with_not_found_model_will_return_422(): void
    {
        $this->actingAsAdmin();

        $response = $this->sendMediaAddedWebhook('https://url.test/image.png', 'bad-slug', 'collection');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('album_slug');
    }

    /** @test */
    public function send_webhook_with_invalid_url_will_return_422(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->sendMediaAddedWebhook('invalidUrl', $album->slug, 'collection');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('media_url');
    }

    /** @test */
    public function user_cannot_send_webhook(): void
    {
        $this->actingAsUser();
        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->sendMediaAddedWebhook('https://url.test/image.png', $album->slug, 'collection');

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_send_webhook(): void
    {
        $response = $this->sendMediaAddedWebhook('https://url.test/image.png', 'nop', 'collection');

        $response->assertUnauthorized();
    }

    private function sendMediaAddedWebhook(string $mediaUrl, string $albumSlug, string $collection): TestResponse
    {
        return $this->postJson('/api/admin/album-media-added', [
            'media_url' => $mediaUrl,
            'album_slug' => $albumSlug,
            'collection' => $collection,
        ]);
    }
}
