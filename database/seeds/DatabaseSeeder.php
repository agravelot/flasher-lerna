<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
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
    }
}
