<?php

/** @var Factory $factory */
use App\Models\Cosplayer;
use App\Models\Invitation;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Invitation::class, static function (Faker $faker) {
    return [
        'email' => $faker->email,
        'token' => Str::random(60),
        'cosplayer_id' => factory(Cosplayer::class)->create()->id,
        'message' => $faker->paragraph,
    ];
});

$factory->state(Cosplayer::class, 'confirmed', static function (Faker $faker) {
    return [
        'confirmed_at' => $faker->dateTime,
    ];
});

$factory->state(Cosplayer::class, 'unconfirmed', static function () {
    return [
        'confirmed_at' => null,
    ];
});
