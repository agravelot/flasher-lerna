<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Admin\AdminPages;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeePages(): void
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

    public function testUserCannotSeePages(): void
    {
        $this->actingAsUser();

        $response = $this->getPages();

        $response->assertStatus(403);
    }

    public function testGuestCannotSeePages(): void
    {
        $response = $this->getPages();

        $response->assertStatus(401);
    }
}
