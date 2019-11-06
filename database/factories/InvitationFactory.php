<?php

use App\Models\Cosplayer;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(Invitation::class, static function (Faker $faker) {
    return [
        'email' => $faker->email,
        'token' => Str::random(60),
        'cosplayer_id' => factory(Cosplayer::class)->create()->id,
        'message' => $faker->paragraph,
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
