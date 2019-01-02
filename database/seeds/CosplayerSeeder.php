<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

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

        Album::all()->each(function ($album) use ($cosplayers) {
            $album->cosplayers()->attach(
                $cosplayers->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
