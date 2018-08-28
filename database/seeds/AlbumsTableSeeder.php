<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // 'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'active', 'image', 'user_id'

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('albums')->insert([
                'title' => $faker->sentence,
                'body' => $faker->paragraph($i),
                'active' => $faker->boolean,
                'password' => null,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
