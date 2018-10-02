<?php

use App\Models\Picture;
use Faker\Generator as Faker;

$factory->define(Picture::class, function (Faker $faker) {
    return [
        'filename' => 'albums/1/JcbnPrYPaIrHGztY4WE8uIuxbBRzXxZS5cfaxRCK.png'
    ];
});
