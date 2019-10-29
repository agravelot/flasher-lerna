<?php

namespace Tests\Feature\Http\Controller\Admin\AdminPages;

use Tests\TestCase;
use App\Models\Page;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeePages()
    {
        $this->actingAsAdmin();
        $page = factory(Page::class)->create();

        $response = $this->getPages();

        $response->assertOk()
            ->assertJsonFragment(['name' => $page->name, 'title' => $page->title]);
    }

    private function getPages(): TestResponse
    {
        return $this->getJson('/api/admin/pages');
    }

    public function testUserCannotSeePages()
    {
        $this->actingAsUser();

        $response = $this->getPages();

        $response->assertStatus(403);
    }

    public function testGuestCannotSeePages()
    {
        $response = $this->getPages();

        $response->assertStatus(401);
    }
}
