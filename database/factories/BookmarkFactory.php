<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Bookmark;
use Faker\Generator as Faker;

$factory->define(Bookmark::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'url' => $faker->url,
        'description' => $faker->paragraph,
        'user_id' => 1,
        'is_public' => $faker->boolean,
    ];
});