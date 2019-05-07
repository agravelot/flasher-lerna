<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Observers;

use Tests\TestCase;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_user_will_store_it_in_activity_logs()
    {
        $user = factory(User::class)->create();

        $this->assertSame(1, Activity::count());
        $this->assertSame($user->id, Activity::latest()->first()->subject_id);
    }

    public function test_updating_an_user_will_store_it_in_activity_logs()
    {
        $user = factory(User::class)->create();

        $user->name = 'Some random title';
        $user->save();

        $this->assertSame(2, Activity::count());
        Activity::all()->each(function (Activity $activity, int $key) use ($user) {
            $this->assertSame($user->id, $activity->subject_id);
        });
    }

    public function test_deleting_an_user_will_store_it_in_activity_logs()
    {
        $user = factory(User::class)->create();

        $user->delete();

        $this->assertSame(2, Activity::count());
        $this->assertSame($user->id, Activity::all()->get(0)->subject_id);
        $this->assertNull(Activity::all()->get(1)->subject_id);
    }

    public function test_creating_two_users_will_store_it_in_activity_logs()
    {
        $users = factory(User::class, 2)->create();

        $this->assertSame(2, Activity::count());
        $this->assertSame($users->get(0)->id, Activity::all()->get(0)->subject_id);
        $this->assertSame($users->get(1)->id, Activity::all()->get(1)->subject_id);
    }
}
