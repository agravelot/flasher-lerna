<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\GoldenBookPost;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DestroyGoldenBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_destroy_an_active_goldenBookPost()
    {
        $this->actingAsAdmin();

        /* @var GoldenBookPost $goldenBookPost */
        $goldenBookPost = factory(GoldenBookPost::class)
            ->state('active')
            ->create();

        $this->disableExceptionHandling();
        $response = $this->deleteGoldenBookPost($goldenBookPost->id);

        $this->assertSame(0, GoldenBookPost::count());
        $response->assertStatus(302);
        $response->assertRedirect('/admin/goldenbook');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('Goldenbook post successfully deleted')
            ->assertDontSee($goldenBookPost->name)
            ->assertDontSee($goldenBookPost->email);
    }

    private function deleteGoldenBookPost(string $id): TestResponse
    {
        return $this->delete('/admin/goldenbook/' . $id);
    }

    public function test_goldenBookPost_can_not_destroy_a_goldenBookPost()
    {
        $this->actingAsUser();

        /* @var GoldenBookPost $goldenBookPost */
        $goldenBookPost = factory(GoldenBookPost::class)->create();

        $response = $this->deleteGoldenBookPost($goldenBookPost->id);

        $this->assertSame(1, GoldenBookPost::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_goldenBookPost_and_is_redirected_to_login()
    {
        /* @var GoldenBookPost $goldenBookPost */
        $goldenBookPost = factory(GoldenBookPost::class)->create();

        $response = $this->deleteGoldenBookPost($goldenBookPost->id);

        $this->assertSame(1, GoldenBookPost::count());
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
