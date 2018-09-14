<?php

use App\Models\Album;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = factory(User::class, 1)
            ->create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('secret'),
                'role' => 'admin',
            ])
            ->each(function ($u) {
                $u->albums()->save(factory(Album::class)->make());
            })
            ->each(function ($u) {
                $u->posts()->save(factory(Post::class)->make());
            });

        $users = factory(User::class, 10)
            ->create()
            ->each(function ($u) {
                $u->albums()->save(factory(Album::class)->make());
            })
            ->each(function ($u) {
                $u->posts()->save(factory(Post::class)->make());
            });
    }
}
