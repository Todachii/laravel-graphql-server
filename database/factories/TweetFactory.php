<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tweet;
use Faker\Generator as Faker;

$factory->define(Tweet::class, function (Faker $faker) {
    return [
        //'account_id' => 1,
        'content' => mb_substr($faker->paragraph, 0, 130),
        'tweeted_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
