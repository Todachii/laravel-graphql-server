<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Favorite;
use App\Models\Timeline;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UnMarkFavoriteResolver
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
        $this->updateTimelineFavoriteId($account, $args['timeline_id']);
        /* @var Favorite $favorite */
        return $this->deleteFavorite($account, $args['tweet_id']);
    }

    /**
     * @param $timelineId
     *
     * @return bool
     */
    protected function updateTimelineFavoriteId(Account $account, $timelineId)
    {
        return Timeline::where([
            'id' => $timelineId,
            'account_id' => $account->id,
        ])->update(['favorite_id' => null]);
    }

    /**
     * @param $tweetId
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    protected function deleteFavorite(Account $account, $tweetId)
    {
        return Favorite::where([
            'tweet_id' => $tweetId,
            'account_id' => $account->id,
        ])->delete();
    }
}
