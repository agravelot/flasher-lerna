<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\GoldenBookPost;

use Tests\TestCase;
use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexGoldenBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_index_page_with_multiple_goldenBookPosts()
    {
        $this->actingAsAdmin();
        $goldenBookPosts = factory(GoldenBookPost::class, 5)
            ->state('published')
            ->create();

        $response = $this->showGoldenBookPostIndex();

        $response->assertStatus(200)
            ->assertDontSee('Nothing to show');

        $goldenBookPosts->each(function (GoldenBookPost $goldenBookPost) use ($response) {
            $response->assertSee($goldenBookPost->name)
                ->assertSee($goldenBookPost->email)
                ->assertSee($goldenBookPost->body);
        });
    }

    private function showGoldenBookPostIndex(): TestResponse
    {
        return $this->get('/admin/goldenbook');
    }

    public function test_admin_can_view_index_page_with_one_active_goldenBookPost()
    {
        $this->actingAsAdmin();
        $goldenBookPost = factory(GoldenBookPost::class)
            ->state('published')
            ->create();

        $response = $this->showGoldenBookPostIndex();

        $response->assertStatus(200)
            ->assertSee($goldenBookPost->name)
            ->assertSee($goldenBookPost->email)
            ->assertSee($goldenBookPost->body)
            ->assertDontSee('Nothing to show');
    }

    public function test_admin_can_view_index_page_with_one_unactive_goldenBookPost()
    {
        $this->actingAsAdmin();
        $goldenBookPost = factory(GoldenBookPost::class)
            ->state('unpublished')
            ->create();

        $response = $this->showGoldenBookPostIndex();

        $response->assertStatus(200)
            ->assertSee($goldenBookPost->name)
            ->assertSee($goldenBookPost->email)
            ->assertSee($goldenBookPost->body)
            ->assertDontSee('Nothing to show');
    }

    public function test_admin_can_view_index_page_with_no_goldenBookPost()
    {
        $this->actingAsAdmin();

        $response = $this->showGoldenBookPostIndex();

        $response->assertStatus(200)
            ->assertSee('Nothing to show');
    }

    public function test_guest_can_not_view_index_page_for_a_goldenBookPost_and_is_redirected_to_login()
    {
        $response = $this->showGoldenBookPostIndex();

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_goldenBookPost_can_not_view_index_page_for_a_goldenBookPost()
    {
        $this->actingAsUser();

        $response = $this->showGoldenBookPostIndex();

        $response->assertStatus(403);
    }
}
