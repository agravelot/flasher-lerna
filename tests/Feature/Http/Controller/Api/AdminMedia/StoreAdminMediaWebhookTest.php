<?php

namespace Tests\Feature\Http\Controller\Api\AdminMedia;

use App\Jobs\MediaAddedWebhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class StoreAdminMediaWebhookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function send_media_will_trigger_media_processing_job(): void
    {
        Queue::fake();
        $this->actingAsAdmin();
        Queue::assertNotPushed(MediaAddedWebhook::class);

        $response = $this->sendMediaAddedWebhook();

        $response->assertNoContent();
        Queue::assertPushed(MediaAddedWebhook::class);
    }

    private function sendMediaAddedWebhook(): TestResponse
    {
        return $this->post('/api/admin/media-added');
    }
}
