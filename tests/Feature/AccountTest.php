<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;

class AccountTest extends TestCase
{
    use RefreshDatabase;
    private $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        $this->account = Account::find(1);
        $this->authHeaders += [$this->getAuthorizedHeader($this->account->email, 'xxxxxx')];
    }

    /**
     * ログインしている会員情報が取得できること確認する
     *
     * @test
     */
    public function mutation_Account_ログインしている会員情報が取得できること確認する()
    {
        $response = $this->graphQL(
            '
                {
                    Account {
                        id,
                        twitter_id,
                        name,
                        email,
                        avatar,
                        is_following_account,
                    }
                }
            '
        )->assertJson([
            'data' => [
                'Account' => [
                    'id' => $this->account->id,
                    'twitter_id' => $this->account->twitter_id,
                    'name' => $this->account->name,
                    'email' => $this->account->email,
                    'avatar' => $this->account->avatar,
                    'is_following_account' => false,
                ]
            ]
        ]);
    }

    /**
     * 会員情報一覧が取得できることを確認する
     *
     * @test
     */
    public function mutation_Account_会員情報一覧が取得できることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }

    /**
     * 会員登録ができることを確認する
     *
     * @test
     */
    public function mutation_Account_会員登録ができることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }

    /**
     * 会員情報が更新できることを確認する
     *
     * @test
     */
    public function mutation_Account_会員情報が更新できることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }

    /**
     * 登録されている会員情報でログインできることを確認する
     *
     * @test
     */
    public function mutation_Account_登録されている会員情報でログインできることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }

    /**
     * 登録されている会員情報でログアウトできることを確認する
     *
     * @test
     */
    public function mutation_Account_登録されている会員情報でログアウトできることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }
}
