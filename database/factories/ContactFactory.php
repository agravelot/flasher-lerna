<?php

declare(strict_types=1);

use App\Models\Contact;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/* @var Factory $factory */

$factory->define(Contact::class, static function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'message' => $faker->paragraph,
        'sso_id' => $faker->boolean ? Str::uuid() : null,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
