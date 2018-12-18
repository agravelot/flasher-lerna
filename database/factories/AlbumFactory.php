<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Album::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph($faker->numberBetween(42, 420)),
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(\App\Models\Album::class, 'published', function (Faker $faker) {
    return [
        'published_at' => $faker->dateTime,
    ];
});

$factory->state(\App\Models\Album::class, 'unpublished', function () {
    return [
        'published_at' => null,
    ];
});

$factory->state(\App\Models\Album::class, 'password', function () {
    return [
        'password' => 'secret',
    ];
});

$factory->state(\App\Models\Album::class, 'passwordLess', function () {
    return [
        'password' => null,
    ];
});

$factory->state(\App\Models\Album::class, 'withUser', function () {
    return [
        'user_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
