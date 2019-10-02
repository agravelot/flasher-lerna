<?php

namespace Tests\Feature\Http\Controller\Admin\AdminPages;

use Tests\TestCase;
use App\Models\Page;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanDeletePages()
    {
        $this->actingAsAdmin();
        $page = factory(Page::class)->create();
        $this->assertCount(1, Page::all());

        $response = $this->deletePage($page);

        $response->assertStatus(204);
        $this->assertCount(0, Page::all());
    }

    private function deletePage(Page $page): TestResponse
    {
        return $this->json('delete', "/api/admin/pages/{$page->id}");
    }

    public function testUserCannotDeletePages()
    {
        $this->actingAsUser();

        $page = factory(Page::class)->create();
        $this->assertCount(1, Page::all());

        $response = $this->deletePage($page);

        $response->assertStatus(403);
        $this->assertCount(1, Page::all());
    }

    public function testGuestCannotDeletePages()
    {
        $page = factory(Page::class)->create();
        $this->assertCount(1, Page::all());

        $response = $this->deletePage($page);

        $response->assertStatus(401);
        $this->assertCount(1, Page::all());
    }
}
