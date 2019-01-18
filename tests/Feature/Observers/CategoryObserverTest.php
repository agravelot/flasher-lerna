<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Observers;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class CategoryObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_category_will_store_it_in_activity_logs()
    {
        $category = factory(Category::class)->create();

        $this->assertSame(1, Activity::count());
        $this->assertSame($category->id, Activity::latest()->first()->subject_id);
    }

    public function test_updating_an_category_will_store_it_in_activity_logs()
    {
        $category = factory(Category::class)->create();

        $category->name = 'Some random title';
        $category->save();

        $this->assertSame(2, Activity::count());
        Activity::all()->each(function (Activity $activity, int $key) use ($category) {
            $this->assertSame($category->id, $activity->subject_id);
        });
    }

    public function test_deleting_an_category_will_store_it_in_activity_logs()
    {
        $category = factory(Category::class)->create();

        $category->delete();

        $this->assertSame(2, Activity::count());
        $this->assertSame($category->id, Activity::all()->get(0)->subject_id);
        $this->assertNull(Activity::all()->get(1)->subject_id);
    }

    public function test_creating_two_categorys_will_store_it_in_activity_logs()
    {
        $categorys = factory(Category::class, 2)->create();

        $this->assertSame(2, Activity::count());
        $this->assertSame($categorys->get(0)->id, Activity::all()->get(0)->subject_id);
        $this->assertSame($categorys->get(1)->id, Activity::all()->get(1)->subject_id);
    }
}
