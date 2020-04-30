<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (factory(User::class, 10)->make() as $user) {
            Keycloak::users()->create($user);
        }
    }
}
