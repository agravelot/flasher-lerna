<?php

namespace Tests\Feature\Http\Controller\Api\AdminMedia;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreMediaWebhookTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
    }

    /** @test */
    public function send_webhook_will_trigger_media_processing(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();
        $this->assertCount(0, $album->fresh()->media);
        $path = "albums/{$album->id}";
        $exceptedFileName = "$album->slug.jpeg";
        Storage::disk('s3')->putFileAs($path, UploadedFile::fake()->image($exceptedFileName), $exceptedFileName);

        $response = $this->sendMediaAddedWebhook($exceptedFileName, $album->id);

        $response->assertCreated()
            ->assertJsonPath('data.mime_type', 'image/png')
            ->assertJsonPath('data.name', 'filename.png');
        //->assertJsonPath('data.path', '');
        $this->assertCount(1, $album->fresh()->media);
    }

    /** @test */
    public function send_webhook_with_not_found_model_will_return_422(): void
    {
        $this->actingAsAdmin();

        $response = $this->sendMediaAddedWebhook('/image.png', 4242);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('Upload.MetaData.modelId');
    }

//    /** @test */
//    public function send_webhook_with_invalid_url_will_return_422(): void
//    {
//        $this->actingAsAdmin();
//        /** @var Album $album */
//        $album = factory(Album::class)->create();
//
//        $response = $this->sendMediaAddedWebhook('nonValidName', $album->id);
//
//        $response->assertStatus(422)
//            ->assertJsonValidationErrors('media_name');
//    }

    /** @test */
    public function user_cannot_send_webhook(): void
    {
        $this->actingAsUser();
        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->sendMediaAddedWebhook('image.png', $album->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_send_webhook(): void
    {
        Storage::fake('s3');
        $response = $this->sendMediaAddedWebhook('image.png', 1);

        $response->assertUnauthorized();
    }

    private function sendMediaAddedWebhook(string $mediaName, string $albumId): TestResponse
    {
        return $this->postJson('/api/admin/album-media-added', [
            'Upload' => [
                'ID' => 'azeaze',
                'Size' => 123,
                'Offset' => 123,
                'IsFinal' => true,
                'IsPartial' => true,
                'MetaData' => [
                    'filename' => 'filename.png',
                    'modelClass' => 'album',
                    'modelId' => $albumId,
                ],
                'Storage' => [
                    'Type' => 'filestore',
                    'Path' => '/svr/files/14b1c4c77771671a8479bc0444bbc5ce',
                ],
            ],
            'HTTPRequest' => [
                'Method' => 'POST',
                'URI' => '/600x400/000/fff',
                'RemoteAddr' => 'https://dummyimage.com',
                'Header' => [
                    'test' => '',
                ],
            ],
        ]);
    }
}
