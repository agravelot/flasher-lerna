<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use App\Models\User;
use Illuminate\Database\Seeder;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::all()->each(function (User $user) {
            $user->albums()->saveMany(
                collect()
                    ->push(factory(App\Models\Album::class)->states(['password', 'published'])->make())
                    ->push(factory(App\Models\Album::class)->states(['password', 'unpublished'])->make())
                    ->push(factory(App\Models\Album::class)->states(['passwordLess', 'unpublished'])->make())
                    ->push(factory(App\Models\PublicAlbum::class)->state('withMedias')->make())
            );
        });
    }
}
