<?php

declare(strict_types=1);

use App\Models\Album;
use Illuminate\Database\Seeder;

class CosplayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $cosplayers = factory(App\Models\Cosplayer::class, 10)->create();

        Album::all()->each(static function ($album) use ($cosplayers) {
            $album->cosplayers()->attach(
                $cosplayers->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
