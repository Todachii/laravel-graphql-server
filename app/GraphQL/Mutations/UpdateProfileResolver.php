<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateProfileResolver
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
        $updatedAccount = $this->updateAccount($account, $args);

        return $updatedAccount;
    }

    protected function updateAccount(Account $account, array $args)
    {
        $account->name = $args['name'] ?? $account->name;
        if (isset($args['avatar'])) {
            $exploded = explode(';base64,', $args['avatar']);
            $imageType = explode('image/', $exploded[0])[1];
            $imageName = str_random().".{$imageType}";
            \Storage::put('public/images/'.$imageName, base64_decode($exploded[1]));
            $account->avatar = $imageName;
        }
        $account->save();

        return $account;
    }
}
