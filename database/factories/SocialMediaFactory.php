<?php

declare(strict_types=1);

use App\Models\SocialMedia;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

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
