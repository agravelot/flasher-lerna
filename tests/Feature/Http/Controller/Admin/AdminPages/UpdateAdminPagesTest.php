<?php

namespace Modules\Core\Tests\Features\AdminPages;

use Tests\TestCase;
use App\Models\Page;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanUpdatePage()
    {
        $this->actingAsAdmin();
        $page = factory(Page::class)->create();

        $page->title = $title = 'newValue';
        $response = $this->updatePage($page);

        $response->assertStatus(200)
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

    public function testUserCannotUpdatePages()
    {
        $this->actingAsUser();
        $page = factory(Page::class)->create(['title' => 'testValue']);
        $page->title = 'newValue';

        $response = $this->updatePage($page);

        $response->assertStatus(403);
        $this->assertCount(1, Page::all());
        $this->assertSame('testValue', $page->fresh()->title);
    }

    public function testGuestCannotUpdatePages()
    {
        $page = factory(Page::class)->create(['title' => 'testValue']);
        $page->title = 'newValue';

        $response = $this->updatePage($page);

        $response->assertStatus(401);
        $this->assertCount(1, Page::all());
        $this->assertSame('testValue', $page->fresh()->title);
    }
}
