<?php

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Timeline;
use App\Models\Tweet;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        factory(Account::class)->create()->each(function ($account) {
            factory(Tweet::class, 5)->make(['account_id' => $account->id])->each(
                function ($tweet) use ($account) {
                    factory(Timeline::class)->make([
                        'tweet_id' => $tweet->id,
                        'account_id' => $account->id
                    ]);
                }
            );
        });

        factory(Account::class)->states('account2')->create()->each(function ($account) {
            factory(Tweet::class, 5)->create(['account_id' => $account->id])->each(
                function ($tweet) use ($account) {
                    factory(Timeline::class)->create([
                        'tweet_id' => $tweet->id,
                        'account_id' => $account->id
                    ]);
                }
            );
        });
    }
}
