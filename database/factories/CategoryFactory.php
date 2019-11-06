<?php

use Carbon\Carbon;
use App\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$withCover = false;

$factory->define(Category::class, static function (Faker $faker) use (&$withCover) {
    $withCover = false;

    return [
        'name' => $faker->unique()->sentence,
        'description' => $faker->paragraph,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});

//TODO Fix not working
$factory->afterMaking(Category::class, static function (Category $category, Faker $faker) use (&$withCover) {
    if ($withCover) {
        $category->setCover(UploadedFile::fake()->image('fake.jpg'));
    }
});

$factory->state(Category::class, 'withCover', static function () use (&$withMedias) {
    $withMedias = true;

    return [];
});
