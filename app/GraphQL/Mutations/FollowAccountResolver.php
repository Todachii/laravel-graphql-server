<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Follow;
use App\Models\Follower;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FollowAccountResolver
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
        /** @var \App\Models\Account $account */
        $account = auth()->user();

        $follow = $this->followAccount($account, $args);

        $this->addToFollowers($account, $args);

        return $follow;
    }

    /**
     * @return \App\Models\Follow
     */
    protected function followAccount(Account $account, array $data)
    {
        return Follow::create([
            'account_id' => $account->id,
            'follow_account_id' => $data['id'],
        ]);
    }

    /**
     * @return \App\Models\Follower
     */
    protected function addToFollowers(Account $account, array $data)
    {
        return Follower::create([
            'account_id' => $data['id'],
            'follower_account_id' => $account->id,
        ]);
    }
}
