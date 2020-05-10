<?php

namespace Tests\Unit\Models;

use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ModelTestCase;

class SocialMediaTest extends ModelTestCase
{
    use RefreshDatabase;

    public function test_all_social_media_with_active_scope_are_all_active(): void
    {
        factory(SocialMedia::class)->state('active')->create();

        $socialMedias = SocialMedia::active()->get();

        $this->assertCount(1, $socialMedias);
    }
}
