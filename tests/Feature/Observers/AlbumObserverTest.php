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
use App\Models\Album;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlbumObserverTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function test_creating_an_album_will_store_it_in_activity_logs()
    {
        $album = factory(Album::class)->create(['user_id' => $this->user->id]);

        $this->assertSame(1, Activity::count());
        $this->assertSame($album->id, Activity::latest()->first()->subject_id);
    }

    public function test_updating_an_album_will_store_it_in_activity_logs()
    {
        $album = factory(Album::class)->create(['user_id' => $this->user->id]);

        $album->title = 'Some random title';
        $album->save();

        $this->assertSame(2, Activity::count());
        Activity::all()->each(function (Activity $activity, int $key) use ($album) {
            $this->assertSame($album->id, $activity->subject_id);
        });
    }

    public function test_deleting_an_album_will_store_it_in_activity_logs()
    {
        $album = factory(Album::class)->create(['user_id' => $this->user->id]);

        $album->delete();

        $this->assertSame(2, Activity::count());
        $this->assertSame($album->id, Activity::all()->get(0)->subject_id);
        $this->assertNull(Activity::all()->get(1)->subject_id);
    }

    public function test_creating_two_albums_will_store_it_in_activity_logs()
    {
        $albums = factory(Album::class, 2)->create(['user_id' => $this->user->id]);

        $this->assertSame(2, Activity::count());
        $this->assertSame($albums->get(0)->id, Activity::all()->get(0)->subject_id);
        $this->assertSame($albums->get(1)->id, Activity::all()->get(1)->subject_id);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Activity::all()->each(function (Activity $activity) {
            $activity->delete();
        });
    }
}
