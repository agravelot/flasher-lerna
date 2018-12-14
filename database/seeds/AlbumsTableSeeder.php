<?php

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
                    ->concat(factory(App\Models\Album::class, 2)->state('password')->state('published')->make())
                    ->concat(factory(App\Models\Album::class, 2)->state('password')->state('unpublished')->make())
                    ->concat(factory(App\Models\Album::class, 2)->state('passwordLess')->state('unpublished')->make())
                    ->concat(factory(App\Models\Album::class, 2)->state('passwordLess')->state('published')->make())
            );
        });
    }
}
