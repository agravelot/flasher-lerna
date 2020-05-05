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
        $faker = Factory::create();

        foreach (range(0, 20) as $i) {
            $user = new UserRepresentation();
            $user->email = $faker->email;
            $user->username = $faker->userName;
            $user->addCredential(new Credential(Hash::make('secret')));
            $user->emailVerified = true;

            Keycloak::users()->create($user);
        }
    }
}
