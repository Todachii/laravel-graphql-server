<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Models\Tweet;
use App\Models\Timeline;
use Faker\Generator as Faker;

$factory->define(Timeline::class, function (Faker $faker) {
    return [

        //'account_id' => 1,
        //'tweet_id' => Tweet::where('account_id', 1)->first()->id,
        //'favorite_id',
        //'original_favorite_id',
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
