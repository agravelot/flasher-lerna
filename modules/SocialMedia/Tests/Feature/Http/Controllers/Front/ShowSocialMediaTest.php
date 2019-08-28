<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Front\Contact;

use Tests\TestCase;
use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_social_medias_are_on_homepage(): void
    {
        $socialMedia = factory(SocialMedia::class)->state('active')->create();

        $response = $this->get('/');

        $response->assertSee($socialMedia->url);
    }

    public function test_non_active_social_medias_are_on_homepage(): void
    {
        $socialMedia = factory(SocialMedia::class)->state('non-active')->create();

        $response = $this->get('/');

        $response->assertDontSee($socialMedia->url);
    }
}
