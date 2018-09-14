<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AlbumsTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(GoldenBooksPostsTableSeeder::class);
        $this->call(ContactTableSeeder::class);
        $this->call(CosplayerSeeder::class);
        $this->call(CategoriesSeeder::class);
    }
}
