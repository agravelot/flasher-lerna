<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Carbon\Carbon;
use Faker\Generator as Faker;

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

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->email,
        'password' => Hash::make('secret'),
        'role' => 'user',
        'email_verified_at' => Carbon::now(),
        'remember_token' => str_random(10),
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(\App\Models\User::class, 'admin', function () {
    return [
        'role' => 'admin',
    ];
});

$factory->state(\App\Models\User::class, 'user', function () {
    return [
        'role' => 'user',
    ];
});
