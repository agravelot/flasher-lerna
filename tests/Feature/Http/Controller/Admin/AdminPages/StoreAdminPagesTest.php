<?php

namespace Modules\Core\Tests\Features\AdminPages;

use Tests\TestCase;
use App\Models\Page;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanStorePage()
    {
        $this->actingAsAdmin();
        $page = factory(Page::class)->make();

        $response = $this->storePage($page);

        $response->assertStatus(201)
            ->assertJson($this->getJsonArray($page));
    }

    private function getJsonArray(Page $page): array
    {
        return [
            'data' => [
//                'id' => $page->id,
                'name' => $page->name,
                'title' => $page->title,
                'description' => $page->description,
            ],
        ];
    }

    private function storePage(Page $page): TestResponse
    {
        return $this->json('post', '/api/admin/pages', [
            'name' => $page->name,
            'title' => $page->title,
            'description' => $page->description,
        ]);
    }

    public function testUserCannotStorePages()
    {
        $this->actingAsUser();
        $page = factory(Page::class)->make();

        $response = $this->storePage($page);

        $response->assertStatus(403);
        $this->assertCount(0, Page::all());
    }

    public function testGuestCannotStorePages()
    {
        $page = factory(Page::class)->make();

        $response = $this->storePage($page);

        $response->assertStatus(401);
        $this->assertCount(0, Page::all());
    }
}
