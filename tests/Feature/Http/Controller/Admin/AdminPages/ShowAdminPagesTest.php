<?php

namespace Tests\Feature\Http\Controller\Admin\AdminPages;

use Tests\TestCase;
use App\Models\Page;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeePage()
    {
        $this->actingAsAdmin();
        $page = factory(Page::class)->create();

        $response = $this->showPage($page);

        $response->assertOk()
            ->assertJsonFragment(['name' => $page->name, 'title' => $page->title, 'description' => $page->description]);
    }

    private function showPage(Page $page): TestResponse
    {
        return $this->getJson("/api/admin/pages/{$page->id}");
    }

    public function testUserCannotSeePage()
    {
        $this->actingAsUser();
        $page = factory(Page::class)->create();

        $response = $this->showPage($page);

        $response->assertStatus(403);
    }

    public function testGuestCannotSeePage()
    {
        $page = factory(Page::class)->create();
        $response = $this->showPage($page);

        $response->assertStatus(401);
    }
}
