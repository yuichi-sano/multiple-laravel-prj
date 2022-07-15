<?php

namespace packages\domain\model\authentication\authorization;

use Illuminate\Support\Carbon;
use packages\domain\model\authentication\Account;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class RefreshTokenFactory
{
    protected Carbon $carbon;
    protected Carbon $now;
    protected RefreshTokenRepository $refreshTokenRepository;

    public function __construct(Carbon $carbon = null, RefreshTokenRepository $refreshTokenRepository)
    {
        $this->carbon = $carbon ?? new Carbon();
        $this->now = $this->carbon->now();
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function create(Account $account): AuthenticationRefreshToken
    {
        $customClaims = $this->getJWTCustomClaims($account);
        $payload = JWTFactory::make($customClaims);
        $token = new RefreshToken(JWTAuth::encode($payload)->get());
        $refreshTokenExpiresAt = new RefreshTokenExpiresAt($this->now->copy()->addMinutes(config('jwt.refresh_ttl')));
        return AuthenticationRefreshToken::create(
            $token,
            $account->getUserId(),
            $refreshTokenExpiresAt
        );
    }

    /**
     * @caution DB上で管理するためトークンの再生成はしていません
     * @param RefreshToken $refreshToken
     * @return RefreshToken
     */
    public function update(RefreshToken $refreshToken): AuthenticationRefreshToken
    {
        $origin = $this->refreshTokenRepository->findByToken($refreshToken);
        $period = new RefreshTokenExpiresAt($this->now->copy()->addMinutes(config('jwt.refresh_ttl')));
        return $origin->update($period);
    }

    /**
     * アクセストークン用CustomClaimsを返却
     *
     * @return object
     */
    public function getJWTCustomClaims(Account $account): object
    {
        $data = [
            'sub' => $account->getUserId()->getValue(),
            'iat' => $this->now->timestamp,
            'exp' => $this->now->copy()->addMinutes(config('jwt.refresh_ttl'))->timestamp
        ];

        return JWTFactory::customClaims($data);
    }
}
