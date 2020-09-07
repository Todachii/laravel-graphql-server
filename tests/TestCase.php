<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Auth\AuthManager;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $authHeaders = [];

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Execute a query as if it was sent as a request to the server.
     *
     * @param  string  $query  The GraphQL query to send
     * @param  array<string, mixed>  $variables  The variables to include in the query
     * @param  array<string, mixed>  $extraParams  Extra parameters to add to the JSON payload
     * @return \Illuminate\Testing\TestResponse
     */
    protected function graphQL(string $query, array $variables = [], array $extraParams = [])
    {
        $params = ['query' => $query];

        if ($variables) {
            $params += ['variables' => $variables];
        }

        $params += $extraParams;

        return $this->postGraphQL($params, $this->authHeaders);
    }

    /**
     * Execute a POST to the GraphQL endpoint.
     *
     * Use this over graphQL() when you need more control or want to
     * test how your server behaves on incorrect inputs.
     *
     * @param  array<mixed, mixed>  $data
     * @param  array<string, string>  $headers
     * @return \Illuminate\Testing\TestResponse
     */
    protected function postGraphQL(array $data, array $headers = [])
    {
        return $this->postJson(
            '/graphql',
            $data,
            $headers
        );
    }

    /**
     * Return the full URL to the GraphQL endpoint.
     */
    protected function graphQLEndpointUrl(): string
    {
        return route(config('lighthouse.route.name'));
    }

    /**
     * JWTの認証ヘッダ生成
     *
     * @param  string  $email
     * @param  string  $password
     * @return array $header
     */
    protected function getAuthorizedHeader($email, $password)
    {
        $authManager = app(AuthManager::class);
        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = $authManager->guard('api');
        $token = $guard->attempt([
            'email' => $email,
            'password' => $password,
        ]);
        return ['Authorization' => "Bearer $token"];
    }
}
