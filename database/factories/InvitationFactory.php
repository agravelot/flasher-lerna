<?php

use App\Models\Cosplayer;
use App\Models\Invitation;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/* @var Factory $factory */

$factory->define(Invitation::class, static function (Faker $faker) {
    return [
        'email' => $faker->safeEmail,
        'token' => Str::random(60),
        'cosplayer_id' => factory(Cosplayer::class)->create()->id,
        'message' => $faker->paragraph,
        'confirmed_at' => $faker->boolean ? $faker->dateTime : null,
        'created_at' => $faker->dateTimeBetween('-60 days', 'now'),
    ];
});

$factory->state(Invitation::class, 'confirmed', static function (Faker $faker) {
    return [
        'confirmed_at' => $faker->dateTime,
    ];
});

$factory->state(Invitation::class, 'unconfirmed', static function () {
    return [
        'confirmed_at' => null,
    ];
});
