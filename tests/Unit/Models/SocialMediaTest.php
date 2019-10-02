<?php

namespace Tests\Unit\Models;

use Tests\ModelTestCase;
use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
