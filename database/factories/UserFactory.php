<?php

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
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

$factory->define(User::class, static function (Faker $faker) {
    return [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->email,
        'password' => Hash::make('secret'),
        'role' => 'user',
        'email_verified_at' => Carbon::now(),
        'remember_token' => Str::random(10),
        'created_at' => $faker->dateTimeThisDecade,
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(User::class, 'admin', static function () {
    return [
        'role' => 'admin',
    ];
});

$factory->state(User::class, 'user', static function () {
    return [
        'role' => 'user',
    ];
});
