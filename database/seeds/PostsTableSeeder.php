<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::all()->each(function ($user) {
            $user->posts()->saveMany(
                factory(Post::class, 2)
                    ->make()
            );
        });
    }
}
