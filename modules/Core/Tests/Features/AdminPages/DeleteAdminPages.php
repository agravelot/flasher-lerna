<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Tests\Features\AdminPages;

use Tests\TestCase;
use Modules\Core\Entities\Page;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteAdminPages extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanDeletePages()
    {
        $this->disableExceptionHandling();
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
