<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
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
                collect()->concat(factory(App\Models\Album::class, 2)->states(['password', 'published', 'withMedias'])->make())
                    ->concat(factory(App\Models\Album::class, 2)->states(['password', 'unpublished', 'withMedias'])->make())
                    ->concat(factory(App\Models\Album::class, 2)->states(['passwordLess', 'unpublished', 'withMedias'])->make())
                    ->concat(factory(App\Models\Album::class, 2)->states(['passwordLess', 'published', 'withMedias'])->make())
            );
        });
    }
}
