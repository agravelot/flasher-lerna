<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Tests\Feature\AdminPages;

use Tests\TestCase;
use Modules\Core\Entities\Page;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexAdminPages extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeePages()
    {
        $this->actingAsAdmin();
        $page = factory(Page::class)->create();

        $response = $this->getPages();

        $response->assertStatus(200)
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
