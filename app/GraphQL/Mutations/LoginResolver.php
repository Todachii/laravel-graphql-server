<?php

namespace App\GraphQL\Mutations;

use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\AuthManager;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LoginResolver
{
    /**
     * Return a value for the field.
     *
     * @param null                                                $rootValue   Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[]                                             $args        the arguments that were passed into the field
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context     arbitrary data that is shared between all fields of a single query
     * @param \GraphQL\Type\Definition\ResolveInfo                $resolveInfo information about the query itself, such as the execution state, the field name, path to the field from the root, and more
     *
     * @return mixed
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $authManager = app(AuthManager::class);
        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = $authManager->guard('api');
        $token = $guard->attempt([
            'email' => $args['email'],
            'password' => $args['password'],
        ]);
        if ($token) {
            /** @var \App\Models\Account $account */
            $account = auth()->user();
            $account->logged_in_at = Carbon::now();
            $account->save();
        }

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()->getTTL() * 60,
        ];
    }
}
