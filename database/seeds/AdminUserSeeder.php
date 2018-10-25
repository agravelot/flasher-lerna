<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
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
                'email' => 'admin@picblog.com',
                'password' => bcrypt('secret'),
                'role' => 'admin',
            ]);
    }
}
