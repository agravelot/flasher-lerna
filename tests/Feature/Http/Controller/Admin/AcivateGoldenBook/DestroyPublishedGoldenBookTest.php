<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\ActivateGoldenBook;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DestroyPublishedGoldenBookTest extends TestCase
{
    use RefreshDatabase;

    const USER_DATA = [
        'unpublished' => 1,
    ];

    public function test_admin_can_ununpublish_a_goldenbook_post()
    {
        $this->actingAsAdmin();
        $goldenbookPost = factory(GoldenBookPost::class)->state('published')->create();

        $response = $this->destroyPublished($goldenbookPost->id);

        $this->assertSame(1, GoldenBookPost::count());
        $this->assertNull(GoldenBookPost::first()->published_at);
        $response->assertRedirect('/admin/goldenbook');
        $this->followRedirects($response)
            ->assertSee('Goldenbook post unpublished');
    }

    private function destroyPublished(int $id, array $data = []): TestResponse
    {
        session()->setPreviousUrl('/admin/goldenbook');

        return $this->delete('/admin/published-goldenbook/' . $id, $data);
    }

    public function test_admin_can_not_unpublish_an_inexistant_goldenbook_post()
    {
        $this->actingAsAdmin();

        $response = $this->destroyPublished(42);

        $response->assertStatus(404);
    }

    public function test_user_can_not_ununpublish_a_goldenbook_post()
    {
        $this->actingAsUser();
        $goldenbookPost = factory(GoldenBookPost::class)->state('published')->create();

        $response = $this->destroyPublished($goldenbookPost->id);

        $this->followRedirects($response)
            ->assertStatus(403);
        $this->assertTrue($goldenbookPost->fresh()->isPublished());
    }

    public function test_guest_can_not_ununpublish_a_goldenbook_post()
    {
        $goldenbookPost = factory(GoldenBookPost::class)->state('published')->create();

        $response = $this->destroyPublished($goldenbookPost->id);

        $response->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
