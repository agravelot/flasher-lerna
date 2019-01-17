<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\ActivateGoldenBook;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase;

    const USER_DATA = [
        'published' => 1,
    ];

    public function test_admin_can_publish_a_goldenbook_post()
    {
        $this->actingAsAdmin();
        $goldenbookPost = factory(GoldenBookPost::class)->state('unpublished')->create();

        $response = $this->storePublished(['goldenbook_id' => $goldenbookPost->id]);

        $this->assertTrue($goldenbookPost->fresh()->isPublished());
        $response->assertRedirect('/admin/goldenbook');
        $this->followRedirects($response)
            ->assertSee('Goldenbook post published');
    }

    public function test_admin_can_not_publish_an_inexistant_goldenbook_post()
    {
        $this->actingAsAdmin();

        $response = $this->storePublished(['goldenbook_id' => 42]);

        $response->assertRedirect('/admin/goldenbook');
        $this->followRedirects($response)
            ->assertSee('The selected goldenbook id is invalid.');
    }

    public function test_guest_can_not_publish_goldenbook_post_and_is_reirected_to_login()
    {
        $goldenbookPost = factory(GoldenBookPost::class)->state('unpublished')->create();

        $response = $this->storePublished(['goldenbook_id' => $goldenbookPost->id]);

        $this->assertFalse($goldenbookPost->fresh()->isPublished());
        $response->assertRedirect('/login');
    }

    public function test_user_can_not_publish_goldenbook_post()
    {
        $this->actingAsUser();
        $goldenbookPost = factory(GoldenBookPost::class)->state('unpublished')->create();

        $response = $this->storePublished(['goldenbook_id' => $goldenbookPost->id]);

        $this->followRedirects($response)
            ->assertStatus(403);
        $this->assertFalse($goldenbookPost->fresh()->isPublished());
    }

    private function storePublished(array $data): TestResponse
    {
        session()->setPreviousUrl('/admin/goldenbook');

        return $this->post('/admin/published-goldenbook', $data);
    }


    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
