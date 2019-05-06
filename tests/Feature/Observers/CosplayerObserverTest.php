<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Observers;

use Tests\TestCase;
use App\Models\Cosplayer;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CosplayerObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_cosplayer_will_store_it_in_activity_logs()
    {
        $cosplayer = factory(Cosplayer::class)->create();

        $this->assertSame(1, Activity::count());
        $this->assertSame($cosplayer->id, Activity::latest()->first()->subject_id);
    }

    public function test_updating_an_cosplayer_will_store_it_in_activity_logs()
    {
        $cosplayer = factory(Cosplayer::class)->create();

        $cosplayer->name = 'Some random title';
        $cosplayer->save();

        $this->assertSame(2, Activity::count());
        Activity::all()->each(function (Activity $activity, int $key) use ($cosplayer) {
            $this->assertSame($cosplayer->id, $activity->subject_id);
        });
    }

    public function test_deleting_an_cosplayer_will_store_it_in_activity_logs()
    {
        $cosplayer = factory(Cosplayer::class)->create();

        $cosplayer->delete();

        $this->assertSame(2, Activity::count());
        $this->assertSame($cosplayer->id, Activity::all()->get(0)->subject_id);
        $this->assertNull(Activity::all()->get(1)->subject_id);
    }

    public function test_creating_two_cosplayers_will_store_it_in_activity_logs()
    {
        $cosplayers = factory(Cosplayer::class, 2)->create();

        $this->assertSame(2, Activity::count());
        $this->assertSame($cosplayers->get(0)->id, Activity::all()->get(0)->subject_id);
        $this->assertSame($cosplayers->get(1)->id, Activity::all()->get(1)->subject_id);
    }
}
