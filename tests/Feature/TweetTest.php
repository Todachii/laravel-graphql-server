<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        $this->authHeaders += [$this->getAuthorizedHeader(Account::find(1)->email, 'xxxxxx')];
    }

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function mutation_Tweet_ツイートを登録できることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }
}
