<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'avatar'		=> '/tmp',
        'email'			=> $faker->safeEmail,
        'first_name'	=> $faker->name,
        'last_name'		=> $faker->name,
        'social'		=> $faker->url,
    ];
});