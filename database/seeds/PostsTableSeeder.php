<?php

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user)  {
            $user->posts()->saveMany(
                factory(Post::class, 2)
                    ->make()
            );
        });
    }
}
