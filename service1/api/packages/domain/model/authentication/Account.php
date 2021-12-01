<?php

declare(strict_types=1);

namespace packages\Domain\Model\Authentication;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Arrayable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;

class Account implements  Authenticatable,JWTSubject
{
    use AuthenticatableTrait;
    private int $id;
    private string $access_id;

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

}
