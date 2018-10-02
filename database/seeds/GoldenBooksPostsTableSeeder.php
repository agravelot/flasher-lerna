<?php

use Illuminate\Database\Seeder;

class GoldenBooksPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $goldenBookPosts = factory(\App\Models\GoldenBookPost::class, 10)
            ->create();
    }
}
