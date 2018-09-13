<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = factory(App\Models\User::class, 1)
            ->create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('secret'),
                'role' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'email_verified_at' => Carbon::now(),
                'remember_token' => str_random(10),
            ])
            ->each(function ($u) {
                $u->albums()->save(factory(App\Models\Album::class)->make());
            })
            ->each(function ($u) {
                $u->posts()->save(factory(\App\Models\Post::class)->make());
            });

        $users = factory(App\Models\User::class, 10)
            ->create()
            ->each(function ($u) {
                $u->albums()->save(factory(App\Models\Album::class)->make());
            })
            ->each(function ($u) {
                $u->posts()->save(factory(\App\Models\Post::class)->make());
            });
    }
}
