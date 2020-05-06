<?php

use App\Services\Keycloak\Credential;
use App\Services\Keycloak\UserRepresentation;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(0, 20) as $i) {
            Keycloak::users()->create(UserRepresentation::factory());
        }
    }
}
