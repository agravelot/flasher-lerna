<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(static function (User $user): void {
            $user->albums()->saveMany(
                collect()
                    ->push(factory(App\Models\Album::class)->states(['password', 'published'])->make())
                    ->push(factory(App\Models\Album::class)->states(['password', 'unpublished'])->make())
                    ->push(factory(App\Models\Album::class)->states(['passwordLess', 'unpublished'])->make())
                    ->push(factory(App\Models\PublicAlbum::class)->make())
            );
        });
    }
}
