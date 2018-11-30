<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contacts = factory(Contact::class, 100)
            ->create();

        $user = User::find(1);

        foreach ($contacts as $contact) {
            if ($contact->id % 2 == 0) {
                $contact->user()->associate($user);
                $contact->save();
            }
        }
    }
}
