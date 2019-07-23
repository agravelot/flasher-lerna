<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Carbon\Carbon;
use App\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$withCover = false;

$factory->define(Category::class, function (Faker $faker) use (&$withCover) {
    $withCover = false;

    return [
        'name' => $faker->unique()->sentence,
        'description' => $faker->paragraph,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});

//TODO Fix not working
$factory->afterMaking(Category::class, function (Category $category, Faker $faker) use (&$withCover) {
    if ($withCover) {
        $category->setCover(UploadedFile::fake()->image('fake.jpg'));
    }
});

$factory->state(Category::class, 'withCover', function () use (&$withMedias) {
    $withMedias = true;

    return [];
});
