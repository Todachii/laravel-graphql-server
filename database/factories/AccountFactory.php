<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'id' => 1,
        'twitter_id' => 'foo1_twitter',
        'name' => 'foo1',
        'email' => 'foo1@example.com',
        'email_verified_at' => now(),
        'password' => '$2y$10$9uck3X3rP0VrsRhRkMEhPuNDD7jkjHlFf4faQ8pGHZu5xhyilcut2', // secret
        'logged_in_at' => now(),
        'signed_up_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->state(Account::class, 'account2', [
    'id' => 2,
    'twitter_id' => 'foo2_twitter',
    'name' => 'foo2',
    'email' => 'foo2@example.com',
]);
