<?php

use Illuminate\Database\Seeder;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        factory(App\Models\Album::class, 10)->states(['password', 'published'])->create();
        factory(App\Models\Album::class, 10)->states(['password', 'unpublished'])->create();
        factory(App\Models\Album::class, 10)->states(['passwordLess', 'unpublished'])->create();
        factory(App\Models\PublicAlbum::class, 10)->create();
    }
}
