<?php

namespace Tests\Feature\Observers;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ContactObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_contact_will_store_it_in_activity_logs()
    {
        $contact = factory(Contact::class)->create();

        $this->assertSame(1, Activity::count());
        $this->assertSame($contact->id, Activity::latest()->first()->subject_id);
    }

    public function test_updating_an_contact_will_store_it_in_activity_logs()
    {
        $contact = factory(Contact::class)->create();

        $contact->name = 'Some random title';
        $contact->save();

        $this->assertSame(2, Activity::count());
        Activity::all()->each(function (Activity $activity, int $key) use ($contact) {
            $this->assertSame($contact->id, $activity->subject_id);
        });
    }

    public function test_deleting_an_contact_will_store_it_in_activity_logs()
    {
        $contact = factory(Contact::class)->create();

        $contact->delete();

        $this->assertSame(2, Activity::count());
        $this->assertSame($contact->id, Activity::all()->get(0)->subject_id);
        $this->assertSame(null, Activity::all()->get(1)->subject_id);
    }


    public function test_creating_two_contacts_will_store_it_in_activity_logs()
    {
        $contacts = factory(Contact::class,2)->create();

        $this->assertSame(2, Activity::count());
        $this->assertSame($contacts->get(0)->id, Activity::all()->get(0)->subject_id);
        $this->assertSame($contacts->get(1)->id, Activity::all()->get(1)->subject_id);
    }
}