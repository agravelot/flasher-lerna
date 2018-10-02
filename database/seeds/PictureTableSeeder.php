<?php

use App\Models\Album;
use App\Models\Picture;
use Illuminate\Database\Seeder;

class PictureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Album::all()->each(function ($album)  {
            $album->pictures()->saveMany(
                factory(Picture::class, 10)->make()
            );
            $album->pictureHeader()->save(
                factory(Picture::class)->make()
            );
        });
    }
}
