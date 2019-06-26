<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Bookmark;
use Faker\Generator as Faker;

$factory->define(Bookmark::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(5),
        'url' => $faker->url,
        'description' => $faker->text(),
    ];
});
