<?php

declare(strict_types=1);

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
    public function run(): void
    {
        $categories = factory(Category::class, 50)->create();

        Album::all()->each(static function ($album) use ($categories): void {
            $album->categories()->attach(
                $categories->random(rand(4, 10))->pluck('id')->toArray()
            );
        });

        Post::all()->each(static function ($posts) use ($categories): void {
            $posts->categories()->attach(
                $categories->random(rand(4, 10))->pluck('id')->toArray()
            );
        });

        Cosplayer::all()->each(static function ($posts) use ($categories): void {
            $posts->categories()->attach(
                $categories->random(rand(4, 10))->pluck('id')->toArray()
            );
        });
    }
}
