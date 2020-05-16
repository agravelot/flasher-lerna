<?php

declare(strict_types=1);

use App\Enums\SettingType;
use App\Models\Setting;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;

/* @var Factory $factory */

$factory->define(Setting::class, static function (Faker $faker) {
    $randomType = SettingType::getRandomValue();

    return [
        'title' => $faker->word,
        'name' => $faker->word,
        'type' => $randomType, // Need be before 'value'
        'value' => static function () use ($faker, $randomType) {
            if (in_array($randomType, [SettingType::String, SettingType::TextArea], true)) {
                return $faker->sentence;
            }

            if ($randomType === SettingType::Numeric) {
                return $faker->randomNumber();
            }

            if ($randomType === SettingType::Boolean) {
                return $faker->boolean;
            }

            throw new LogicException('Setting must have a valid type defined!');
        },
    ];
});

$factory->state(Setting::class, SettingType::String, static function (Faker $faker) {
    return [
        'type' => SettingType::String,
        'value' => $faker->sentence,
    ];
});

$factory->state(Setting::class, SettingType::TextArea, static function (Faker $faker) {
    return [
        'type' => SettingType::TextArea,
        'value' => $faker->sentences($faker->numberBetween(1, 2), true),
    ];
});

$factory->state(Setting::class, SettingType::Numeric, static function (Faker $faker) {
    return [
        'type' => SettingType::Numeric,
        'value' => $faker->numberBetween(0, 10),
    ];
});

$factory->state(Setting::class, SettingType::Boolean, static function (Faker $faker) {
    return [
        'type' => SettingType::Boolean,
        'value' => $faker->boolean,
    ];
});

$factory->state(Setting::class, SettingType::Media, static function (Faker $faker) {
    return [
        'type' => SettingType::Media,
        'value' => UploadedFile::fake()->image('fake.png'),
    ];
});

$factory->state(Setting::class, SettingType::Email, static function (Faker $faker) {
    return [
        'type' => SettingType::Email,
        'value' => $faker->email,
    ];
});
