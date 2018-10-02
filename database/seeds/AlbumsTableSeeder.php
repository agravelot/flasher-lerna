<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user)  {
            $user->albums()->saveMany(
                factory(App\Models\Album::class, 2)
                    ->make()
            );
        });
    }
}
