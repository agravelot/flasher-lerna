<?php

use App\Adapters\Keycloak\Credential;
use App\Adapters\Keycloak\UserRepresentation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new UserRepresentation();
        $user->email = 'admin@flasher.com';
        $user->username = 'admin';
        $user->addCredential(new Credential(Hash::make('secret')));
        $user->addGroup('admin');
        $user->emailVerified = true;

        Keycloak::users()->create($user);
    }
}
