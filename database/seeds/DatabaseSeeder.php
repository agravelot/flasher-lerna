<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AlbumsTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(TestimonialTableSeeder::class);
        $this->call(ContactTableSeeder::class);
        $this->call(CosplayerSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(SocialMediaSeeder::class);
        $this->call(InvitationsTableSeeder::class);
    }
}
