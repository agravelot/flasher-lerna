<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use App\Models\SocialMedia;
use Faker\Generator as Faker;

$factory->define(SocialMedia::class, static function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence(4),
        'url' => $faker->url,
        'icon' => 'instagram',
        'color' => $faker->hexColor,
        'active' => $faker->boolean,
    ];
});

$factory->state(SocialMedia::class, 'active', static function () {
    return [
        'active' => true,
    ];
});

$factory->state(SocialMedia::class, 'non-active', static function () {
    return [
        'active' => false,
    ];
});
