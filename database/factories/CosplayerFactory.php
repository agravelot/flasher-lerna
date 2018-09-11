<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Cosplayer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
