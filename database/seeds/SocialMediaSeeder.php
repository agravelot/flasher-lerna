<?php

declare(strict_types=1);

use App\Models\SocialMedia;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        factory(SocialMedia::class, 5)->create();
    }
}
