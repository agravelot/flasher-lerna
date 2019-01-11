<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_view_create_page_for_a_category_and_is_redirected_to_login()
    {
        $response = $this->showCategoryCreate();

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    private function showCategoryCreate(): TestResponse
    {
        return $this->get('/admin/categories/create');
    }

    public function test_user_can_not_view_create_page_for_a_category()
    {
        $this->actingAsUser();

        $response = $this->showCategoryCreate();

        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_page_for_a_category()
    {
        $this->actingAsAdmin();

        $response = $this->showCategoryCreate();

        $response->assertStatus(200);
    }
}
