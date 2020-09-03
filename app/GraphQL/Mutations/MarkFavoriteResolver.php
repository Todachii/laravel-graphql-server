<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Favorite;
use App\Models\Timeline;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MarkFavoriteResolver
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
        $favorite = null;
        /** @var Favorite $favorite */
        $favorite = $this->createFavorite($account, $args['tweet_id']); // 1
        $this->updateTimelineFavoriteId($account, $args['timeline_id'], $favorite->id);
        $this->addTweetToFollowersTimeline($account, $args['tweet_id'], $favorite->id);

        return (bool) $favorite;
    }

    protected function createFavorite(Account $account, $tweetId)
    {
        return Favorite::create([
            'account_id' => $account->id,
            'tweet_id' => $tweetId,
            'favorite_at' => Carbon::now(),
        ]);
    }

    protected function updateTimelineFavoriteId(Account $account, $timelineId, $favoriteId)
    {
        return Timeline::where([
            'id' => $timelineId,
            'account_id' => $account->id,
        ])->update(['favorite_id' => $favoriteId]);
    }

    protected function addTweetToFollowersTimeline(Account $account, $tweetId, $favoriteId)
    {
        foreach ($account->followers as $follower) {
            Timeline::create([
                'account_id' => $follower->follower_account_id,
                'tweet_id' => $tweetId,
                'original_favorite_id' => $favoriteId,
            ]);
        }
    }
}
