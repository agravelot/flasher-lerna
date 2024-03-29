<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Admin\AdminPages;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanUpdatePage(): void
    {
        $this->actingAsAdmin();
        $page = factory(Page::class)->create();

        $page->title = $title = 'newValue';
        $response = $this->updatePage($page);

        $response->assertOk()
            ->assertJson($this->getJsonArray($page));
        $this->assertSame($title, $page->fresh()->title);
    }

    private function getJsonArray(Page $page): array
    {
        return [
            'data' => [
                'id' => $page->id,
                'name' => $page->name,
                'title' => $page->title,
                'description' => $page->description,
            ],
        ];
    }

    private function updatePage(Page $page): TestResponse
    {
        return $this->json('patch', "/api/admin/pages/{$page->id}", [
            'name' => $page->name,
            'title' => $page->title,
            'description' => $page->description,
        ]);
    }

    public function testUserCannotUpdatePages(): void
    {
        $this->actingAsUser();
        $page = factory(Page::class)->create(['title' => 'testValue']);
        $page->title = 'newValue';

        $response = $this->updatePage($page);

        $response->assertStatus(403);
        $this->assertCount(1, Page::all());
        $this->assertSame('testValue', $page->fresh()->title);
    }

    public function testGuestCannotUpdatePages(): void
    {
        $page = factory(Page::class)->create(['title' => 'testValue']);
        $page->title = 'newValue';

        $response = $this->updatePage($page);

        $response->assertStatus(401);
        $this->assertCount(1, Page::all());
        $this->assertSame('testValue', $page->fresh()->title);
    }
}
