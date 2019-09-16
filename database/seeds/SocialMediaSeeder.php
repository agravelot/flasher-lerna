<?php

use App\Models\SocialMedia;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(SocialMedia::class, 5)->create();
    }
}
