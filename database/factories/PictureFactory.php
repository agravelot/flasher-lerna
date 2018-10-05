<?php

use App\Models\Picture;
use Faker\Generator as Faker;

$factory->define(Picture::class, function (Faker $faker) {
    return [
        'filePath' => 'albums/1/Cwl03d3WdNFBSQy4qgV5D7Nqxr2OHExQmsAAHYK6.jpeg',
        'mineType' => 'image/jpeg',
        'originalName' => 'UneImage.JPG',
        'size' => '4242',
        'extension' => 'jpg',
        'hashName' => 'Cwl03d3WdNFBSQy4qgV5D7Nqxr2OHExQmsAAHYK6'
    ];
});
