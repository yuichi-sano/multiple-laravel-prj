<?php

declare(strict_types=1);

namespace packages\domain\model\authentication;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Arrayable;
use packages\domain\model\authentication\authorization\AccessToken;
use packages\domain\model\authentication\authorization\RefreshToken;
use Tymon\JWTAuth\Contracts\JWTSubject;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;

class Account implements  Authenticatable,JWTSubject
{
    use AuthenticatableTrait;
    private int $id;
    private string $accessId;
    private AccessToken $accessToken;
    private RefreshToken $refreshToken;

    public function __construct(int $id, string $accessId, AccessToken $accessToken, RefreshToken $refreshToken)
    {
        $this->id=$id;
        $this->accessId=$accessId;
        $this->accessToken = $accessToken;
        $this->refreshToken  = $refreshToken;
    }

    public function getAuthIdentifierName(): string
    {
        return 'access_id';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    // JWT の sub に含める値。主キーを使う
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // JWT のクレームに追加する値。今回は特になし
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    // JWT の sub に含める値。主キーを使う
    public function getKey()
    {
        return env('JWT_SECRET');
    }

    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }
    public function getRefreshToken(): RefreshToken
    {
        return $this->refreshToken;
    }
    /**
     * @param AccessToken $accessToken
     * @param RefreshToken $refreshToken
     * @return Account
     */
    public function authenticationAccount(AccessToken $accessToken, RefreshToken $refreshToken): Account
    {
        return new Account(
            $this->id,
            $this->accessId,
            $accessToken,
            $refreshToken
        );
    }

}
