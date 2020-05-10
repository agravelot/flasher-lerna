<?php

declare(strict_types=1);

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = factory(Contact::class, 100)
            ->create();
    }
}
