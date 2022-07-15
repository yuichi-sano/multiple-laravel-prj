<?php

declare(strict_types=1);

namespace packages\domain\model\authentication;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Arrayable;
use packages\domain\model\authentication\authorization\AccessToken;
use packages\domain\model\authentication\authorization\RefreshToken;
use packages\domain\model\user\UserId;
use packages\domain\model\user\UserPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;

/**
 * 本システムに関するアカウント情報
 * 認証に関するメンバ変数もそれぞれドメインオブジェクトにしたかったが、デフォルトでは認められず。
 * 大がかりになりすぎるので直指定しています。（tokenなどはドメインオブジェクトかできています
 * このドメインモデルは認証Repositoryとの依存関係が非常に高い作りになっています。
 * そのためこのオブジェクトは認証以外では使いません。
 * またメンバ変数をgetする際に適切なドメインオブジェクトに変換する事にしています。
 */
class Account implements Authenticatable, JWTSubject
{
    use AuthenticatableTrait;

    //認証アクセスID
    private string $userId;
    private AccessToken $accessToken;
    private RefreshToken $refreshToken;

    public function __construct(string $userId, AccessToken $accessToken, RefreshToken $refreshToken)
    {
        $this->userId = $userId;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    /**
     * 認証カラム名
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'user_id';
    }

    /**
     * @return string
     */
    public function getUserId(): UserId
    {
        return new UserId($this->userId);
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
        return config('jwt.secret');
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
            $this->userId,
            $accessToken,
            $refreshToken
        );
    }
}
