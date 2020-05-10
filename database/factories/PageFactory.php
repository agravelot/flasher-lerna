<?php

declare(strict_types=1);

use App\Models\Page;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(Page::class, static function (Faker $faker) {
    return [
        'name' => $faker->word,
        'title' => $faker->sentence,
        'description' => $faker->text,
    ];
});
