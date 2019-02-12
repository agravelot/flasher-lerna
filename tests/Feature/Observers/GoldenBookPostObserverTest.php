<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Observers;

use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class GoldenBookPostObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_goldenBookPost_will_store_it_in_activity_logs()
    {
        $goldenBookPost = factory(GoldenBookPost::class)->create();

        $this->assertSame(1, Activity::count());
        $this->assertSame($goldenBookPost->id, Activity::latest()->first()->subject_id);
    }

    public function test_updating_an_goldenBookPost_will_store_it_in_activity_logs()
    {
        $goldenBookPost = factory(GoldenBookPost::class)->create();

        $goldenBookPost->name = 'Some random title';
        $goldenBookPost->save();

        $this->assertSame(2, Activity::count());
        Activity::all()->each(function (Activity $activity, int $key) use ($goldenBookPost) {
            $this->assertSame($goldenBookPost->id, $activity->subject_id);
        });
    }

    public function test_deleting_an_goldenBookPost_will_store_it_in_activity_logs()
    {
        $goldenBookPost = factory(GoldenBookPost::class)->create();

        $goldenBookPost->delete();

        $this->assertSame(2, Activity::count());
        $this->assertSame($goldenBookPost->id, Activity::all()->get(0)->subject_id);
        $this->assertNull(Activity::all()->get(1)->subject_id);
    }

    public function test_creating_two_goldenBookPosts_will_store_it_in_activity_logs()
    {
        $goldenBookPosts = factory(GoldenBookPost::class, 2)->create();

        $this->assertSame(2, Activity::count());
        $this->assertSame($goldenBookPosts->get(0)->id, Activity::all()->get(0)->subject_id);
        $this->assertSame($goldenBookPosts->get(1)->id, Activity::all()->get(1)->subject_id);
    }
}
