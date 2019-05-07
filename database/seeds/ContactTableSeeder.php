<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use App\Models\User;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
