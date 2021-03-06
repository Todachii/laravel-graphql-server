<?php

namespace App\Models;

use DB;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'twitter_id',
        'email',
        'password',
        'logged_in_at',
        'signed_up_at',
    ];
    /*
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function followers()
    {
        return $this->hasMany(Follower::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function follower()
    {
        return $this->hasOne(Follower::class)->where('follower_account_id', auth()->user()->id);
    }

    /**
     * @param $root
     * @param $context
     */
    public function accountList($root, array $args, $context, ResolveInfo $resolveInfo): Builder
    {
        $accounts = $this::select([
            'accounts.id',
            'accounts.twitter_id',
            'accounts.name',
            'accounts.avatar',
            DB::raw('!(followers.id <=> NULL) AS is_following_account'), ])
                ->leftJoin('followers', function ($join) {
                    $join->on('accounts.id', '=', 'followers.account_id')
                    ->where('followers.follower_account_id', '=', auth()->user()->id);
                })->where('accounts.id', '<>', auth()->user()->id);

        return $accounts;
    }
}
