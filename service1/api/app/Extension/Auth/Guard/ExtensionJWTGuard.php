<?php

namespace App\Extension\Auth\Guard;

use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\JWTGuard;

/**
 * 本来認証機能もpackages/services層に実装するのが綺麗であるとは思うが、<br>
 * laravelの認証機構にできるだけより沿うことを目的に<br>
 * 認証に関する関心事はすべてGuardにて完結させる
 * @note tokenの発行等はdomain層のtokenFactoryにて処理します
 */
class ExtensionJWTGuard extends JWTGuard
{
    /**
     * JWTGuardではtokenの発行まで行っているが、責務を分離したいので認証のみ実施する。
     *
     * @param array $credentials
     * @param bool $login
     *
     * @return bool
     */
    public function attempt(array $credentials = [], $login = true): bool
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);
        return $this->hasValidCredentials($user, $credentials);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if (
            $this->jwt->setRequest($this->request)->getToken() &&
            ($payload = $this->jwt->check(true)) &&
            $this->validateSubject()
        ) {
            return $this->user = $this->provider->retrieveById($payload['sub']);
        }
        return null;
    }
}
