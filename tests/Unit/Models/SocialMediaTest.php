<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Unit\Models;

use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class SocialMediaTest extends ModelTestCase
{
    use WithFaker, RefreshDatabase;

    public function test_all_social_media_with_active_scope_are_all_active()
    {
        factory(SocialMedia::class)->state('active')->create();

        $socialMedias = SocialMedia::active()->get();

        $this->assertNotEmpty($socialMedias);
    }
}
