<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Tests\Features\AdminPages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Modules\Core\Entities\Page;
use Tests\TestCase;

class ShowAdminPages extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeePage()
    {
        $this->actingAsAdmin();
        $page = factory(Page::class)->create();

        $response = $this->showPage($page);

        $response->assertStatus(200)
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
