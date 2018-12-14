<?php

use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = factory(Category::class, 50)->create();

        Album::all()->each(function ($album) use ($categories) {
            $album->categories()->attach(
                $categories->random(rand(5, 10))->pluck('id')->toArray()
            );
        });

        Post::all()->each(function ($posts) use ($categories) {
            $posts->categories()->attach(
                $categories->random(rand(5, 10))->pluck('id')->toArray()
            );
        });

        Cosplayer::all()->each(function ($posts) use ($categories) {
            $posts->categories()->attach(
                $categories->random(rand(5, 10))->pluck('id')->toArray()
            );
        });
    }
}
