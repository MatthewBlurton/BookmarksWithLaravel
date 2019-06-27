<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'avatar'		=> '/tmp',
        'first_name'	=> $faker->name,
        'family_name'	=> $faker->name,
        'social'		=> $faker->url,
    ];
});