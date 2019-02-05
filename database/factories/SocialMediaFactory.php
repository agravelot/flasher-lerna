<?php

use Faker\Generator as Faker;

$factory->define(App\SocialMedia::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'url' => $faker->url,
        'icon' => 'icon',
        'active' => $faker->boolean,
    ];
});
