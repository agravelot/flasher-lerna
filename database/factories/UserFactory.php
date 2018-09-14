<?php

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
        'password' => bcrypt('secret'),
        'role' => 'user',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        'email_verified_at' => Carbon::now(),
        'remember_token' => str_random(10),
    ];
});
