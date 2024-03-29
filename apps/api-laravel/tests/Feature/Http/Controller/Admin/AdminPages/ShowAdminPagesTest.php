<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Admin\AdminPages;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ShowAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeePage(): void
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

    public function testUserCannotSeePage(): void
    {
        $this->actingAsUser();
        $page = factory(Page::class)->create();

        $response = $this->showPage($page);

        $response->assertStatus(403);
    }

    public function testGuestCannotSeePage(): void
    {
        $page = factory(Page::class)->create();
        $response = $this->showPage($page);

        $response->assertStatus(401);
    }
}
