<?php

namespace Tests\Feature\Http\Controller\Admin\SocialMedia;

use Tests\TestCase;
use App\Models\SocialMedia;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroySocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_destroy_a_socialMedia()
    {
        $this->actingAsAdmin();

        /* @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->deleteSocialMedia($socialMedia->id);

        $this->assertSame(0, SocialMedia::count());
        $response->assertStatus(302);
        $response->assertRedirect('/admin/social-medias');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('Social media successfully deleted')
            ->assertDontSee($socialMedia->name);
    }

    private function deleteSocialMedia(int $id): TestResponse
    {
        return $this->delete('/admin/social-medias/'.$id);
    }

    public function test_user_can_not_destroy_a_socialMedia()
    {
        $this->actingAsUser();

        /* @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->deleteSocialMedia($socialMedia->id);

        $this->assertSame(1, SocialMedia::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_socialMedia_and_is_redirected_to_login()
    {
        /* @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->deleteSocialMedia($socialMedia->id);

        $this->assertSame(1, SocialMedia::count());
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
