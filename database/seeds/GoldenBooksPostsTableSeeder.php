<?php

use Illuminate\Database\Seeder;

class GoldenBooksPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $goldenBookPosts = factory(\App\Models\GoldenBookPost::class, 10)
            ->create();
    }
}
