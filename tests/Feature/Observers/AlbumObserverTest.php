<?php

namespace Tests\Feature\Observers;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class AlbumObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_album_will_store_it_in_activity_logs()
    {
        $album = factory(Album::class)->state('withUser')->create();

        $this->assertSame(1, Activity::count());
        $this->assertSame($album->id, Activity::latest()->first()->subject_id);
    }

    public function test_updating_an_album_will_store_it_in_activity_logs()
    {
        $album = factory(Album::class)->state('withUser')->create();

        $album->title = 'Some random title';
        $album->save();

        $this->assertSame(2, Activity::count());
        Activity::all()->each(function (Activity $activity, int $key) use ($album) {
            $this->assertSame($album->id, $activity->subject_id);
        });
    }

    public function test_deleting_an_album_will_store_it_in_activity_logs()
    {
        $album = factory(Album::class)->state('withUser')->create();

        $album->delete();

        $this->assertSame(2, Activity::count());
        $this->assertSame($album->id, Activity::all()->get(0)->subject_id);
        $this->assertSame(null, Activity::all()->get(1)->subject_id);
    }


    public function test_creating_two_albums_will_store_it_in_activity_logs()
    {
        $albums = factory(Album::class,2)->state('withUser')->create();

        $this->assertSame(2, Activity::count());
        $this->assertSame($albums->get(0)->id, Activity::all()->get(0)->subject_id);
        $this->assertSame($albums->get(1)->id, Activity::all()->get(1)->subject_id);
    }
}