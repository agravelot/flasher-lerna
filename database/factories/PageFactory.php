<?php

use App\Models\Page;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'title' => $faker->sentence,
        'description' => $faker->text,
    ];
});
