<?php

declare(strict_types=1);

use App\Models\Category;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;

/* @var Factory $factory */

$withCover = false;

$factory->define(Category::class, static function (Faker $faker) use (&$withCover) {
    $withCover = false;

    return [
        'name' => $faker->unique()->word,
        'meta_description' => $faker->text(150),
        'description' => $faker->randomHtml(3),
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
