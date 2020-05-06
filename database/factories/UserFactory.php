<?php

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/* @var Factory $factory */

$factory->define(User::class, static function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'preferred_username' => $faker->unique()->userName,
        'email' => $faker->unique()->email,
        'password' => Hash::make('secret'),
        'notify_on_album_published' => true,
        'realm_access' => [
            'roles' => [],
        ],
        'resource_access' => [
            'account' => [
                'roles' => [
                    'manage-account',
                    'manage-account-links',
                    'view-profile',
                ],
            ],
        ],
    ];
});

$factory->state(User::class, 'admin', static function () {
    return [
        'realm_access' => [
            'roles' => [
                'admin',
            ],
        ],
    ];
});

$factory->state(User::class, 'user', static function () {
    return [
        'realm_access' => [
            'roles' => [],
        ],
    ];
});
