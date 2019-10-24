<?php

/** @var Factory $factory */
use App\Models\Cosplayer;
use App\Models\Invitation;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Invitation::class, static function (Faker $faker) {
    return [
        'email' => $faker->email,
        'cosplayer_id' => factory(Cosplayer::class)->create()->id,
        'message' => $faker->paragraph,
    ];
});
