<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        $this->authHeaders += [$this->getAuthorizedHeader(Account::find(1)->email, 'xxxxxx')];
    }

    /**
     * アカウントフォローできることを確認する
     *
     * @test
     */
    public function mutation_Follow_アカウントフォローできることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }

    /**
     * アカウントフォロー解除できることを確認する
     *
     * @test
     */
    public function mutation_Follow_アカウントフォロー解除できることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }
}
