<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Modules\Core\Entities\Setting;
use Modules\Core\Enums\SettingType;

$factory->define(Setting::class, function (Faker $faker) {
    $randomType = SettingType::getRandomValue();

    return [
        'title' => $faker->word,
        'name' => $faker->word,
        'type' => $randomType, // Need be before 'value'
        'value' => function () use ($faker, $randomType) {
            if (in_array($randomType, [SettingType::String, SettingType::TextArea])) {
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

$factory->state(Setting::class, SettingType::String, function (Faker $faker) {
    return [
        'type' => SettingType::String,
        'value' => $faker->sentence,
    ];
});

$factory->state(Setting::class, SettingType::TextArea, function (Faker $faker) {
    return [
        'type' => SettingType::TextArea,
        'value' => $faker->sentences($faker->numberBetween(1, 2), true),
    ];
});

$factory->state(Setting::class, SettingType::Numeric, function (Faker $faker) {
    return [
        'type' => SettingType::Numeric,
        'value' => $faker->numberBetween(0, 10),
    ];
});

$factory->state(Setting::class, SettingType::Boolean, function (Faker $faker) {
    return [
        'type' => SettingType::Boolean,
        'value' => $faker->boolean,
    ];
});

$factory->state(Setting::class, SettingType::Media, function (Faker $faker) {
    return [
        'type' => SettingType::Media,
        'value' => UploadedFile::fake()->image('fake.png'),
    ];
});

$factory->state(Setting::class, SettingType::Email, function (Faker $faker) {
    return [
        'type' => SettingType::Email,
        'value' => $faker->email,
    ];
});
