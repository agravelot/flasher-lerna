<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(\App\Models\Contact::class, static function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'message' => $faker->paragraph,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
