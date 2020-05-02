<?php

use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

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
        'preferred_username' => $faker->unique()->userName,
        'email' => $faker->unique()->email,
        'password' => Hash::make('secret'),
        'groups' => ['user'],
        'email_verified_at' => Carbon::now(),
        'remember_token' => Str::random(10),
        'created_at' => $faker->dateTimeThisDecade,
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(User::class, 'admin', static function () {
    return [
        'groups' => ['admin'],
    ];
});

$factory->state(User::class, 'user', static function () {
    return [
        'groups' => ['user'],
    ];
});
