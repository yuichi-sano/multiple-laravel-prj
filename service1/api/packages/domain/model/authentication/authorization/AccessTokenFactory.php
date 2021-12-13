<?php

namespace packages\domain\model\authentication\authorization;

use Illuminate\Support\Carbon;
use packages\domain\basic\type\StringType;
use packages\domain\model\authentication\Account;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AccessTokenFactory implements StringType
{
    protected Carbon $carbon;
    protected $now;

    public function __construct(Carbon $carbon = null)
    {
        $this->carbon = $carbon ?? new Carbon();
        $this->now = $this->carbon->now();
    }

    public function create(Account $account): AccessToken{
        $customClaims = $this->getJWTCustomClaims($account);
        $payload = JWTFactory::make($customClaims);
        return new AccessToken(JWTAuth::encode($payload)->get());
    }

    public function createForRefreshToken(RefreshToken $refreshToken): AccessToken{
        //refreshtokenからDBレコードひっぱる
        $account = new Account();

        $customClaims = $this->getJWTCustomClaims($account);
        $payload = JWTFactory::make($customClaims);
        return new AccessToken(JWTAuth::encode($payload)->get());
    }

    /**
     * アクセストークン用CustomClaimsを返却
     *
     * @return object
     */
    public function getJWTCustomClaims(Account $account) :object
    {
        $data = [
            'sub' => $account->getId(),
            'iat' => $this->now->timestamp,
            'exp' => $this->now->addMinute(config('jwt.ttl'))->timestamp
        ];

        return JWTFactory::customClaims($data);
    }
}
