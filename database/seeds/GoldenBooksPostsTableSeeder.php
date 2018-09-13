<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
