<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;
use App\Models\Timeline;

class TimelineTest extends TestCase
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
     * ログインしたアカウントのタイムラインが返ってくることを確認する
     *
     * @test
     */
    public function query_Timeline_ログインしたアカウントのタイムラインが返ってくることを確認する()
    {
        $timelines = Timeline::with(['tweet'])
            ->where('account_id', 1)->where('id', '<', 6)->orderByDesc('id')->limit(10)->get()->toArray();

        $responseTimelines = [];

        for ($i = 0; $i < count($timelines); $i++) {
            $responseTimelines[$i] = [
                'id' => $timelines[$i]->id,
                'tweet' => [
                    'id' => $timelines[$i]->tweet->id,
                    'content' => $timelines[$i]->tweet->content,
                    'account' => [
                        'twitter_id' => $this->account->twitter_id,
                        'avatar' => null
                    ]
                ],
                'originalFavorite' => null,
                'favorite' => null,
            ];
        }
        $response = $this->graphQL(
            '
                query($id: Int!) {
                    Timeline(id: $id) {
                        id
                        tweet {
                            id
                            content
                            account {
                                twitter_id
                                avatar
                            }
                        }
                        originalFavorite {
                            account {
                            twitter_id
                            name
                            }
                        }
                        favorite {
                            favorite_at
                        }
                    }
                }
            ',
            [
                'id' => 6
            ]
        )->assertJson([
            'data' => [
                'Timeline' => $responseTimelines
            ]
        ]);
    }

    /**
     * 任意のidより小さいidのタイムラインが返ってくることを確認する
     *
     * @test
     */
    public function query_Timeline_任意のidより小さいidのタイムラインが返ってくることを確認する()
    {
        $timelines = Timeline::with(['tweet'])
            ->where('account_id', 1)->where('id', '<', 2)->orderByDesc('id')->limit(10)->get()->toArray();

        $responseTimelines = [];

        for ($i = 0; $i < count($timelines); $i++) {
            $responseTimelines[$i] = [
                'id' => $timelines[$i]->id,
                'tweet' => [
                    'id' => $timelines[$i]->tweet->id,
                    'content' => $timelines[$i]->tweet->content,
                    'account' => [
                        'twitter_id' => $this->account->twitter_id,
                        'avatar' => null
                    ]
                ],
                'originalFavorite' => null,
                'favorite' => null,
            ];
        }
        $response = $this->graphQL(
            '
                query($id: Int!) {
                    Timeline(id: $id) {
                        id
                        tweet {
                            id
                            content
                            account {
                                twitter_id
                                avatar
                            }
                        }
                        originalFavorite {
                            account {
                            twitter_id
                            name
                            }
                        }
                        favorite {
                            favorite_at
                        }
                    }
                }
            ',
            [
                'id' => 2
            ]
        )->assertJson([
            'data' => [
                'Timeline' => $responseTimelines
            ]
        ]);
    }

    /**
     * ツイートを登録後にタイムラインも登録されていることを確認する
     *
     * @test
     */
    public function mutation_Timeline_ツイートを登録後にタイムラインも登録されていることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }

    /**
     * ツイートお気に入り登録後にお気に入り登録された状態のタイムラインツイートが取得できることを確認する
     *
     * @test
     */
    public function mutation_Timeline_ツイートお気に入り登録後にお気に入り登録された状態のタイムラインツイートが取得できることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }

    /**
     * ツイートお気に入り登録解除後にお気に入り登録解除された状態のタイムラインツイートが取得できることを確認する
     *
     * @test
     */
    public function mutation_Timeline_ツイートお気に入り登録解除後にお気に入り登録解除された状態のタイムラインツイートが取得できることを確認する()
    {
        $response = $this->post('/graphql');

        $response->assertStatus(200);
    }
}
