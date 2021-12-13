<?php

namespace App\Http\Controllers;

use App\Http\Resources\Authentication\LoginResource;
use Illuminate\Routing\Controller as BaseController;
use packages\domain\model\authentication\authorization\RefreshToken;
use packages\service\authentication\AccessTokenGetInterface;
use packages\service\authentication\RefreshTokenUpdateInterface;

class AccessTokenGetController extends BaseController
{
    private AccessTokenGetInterface $accessTokenGet;
    private RefreshTokenUpdateInterface $refreshTokenUpdate;
    public function __construct(AccessTokenGetInterface $accessTokenGet,
                                RefreshTokenUpdateInterface $refreshTokenUpdate)
    {
        $this->accessTokenGet = $accessTokenGet;
        $this->refreshTokenUpdate = $refreshTokenUpdate;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( AccessTokenGetInterface $accessTokenGet): \Illuminate\Http\Response
    {

        $accessToken = $accessTokenGet->execute(new RefreshToken('fdfdfdfd'));
        $refreshToken = $this->refreshTokenUpdate->execute($accessToken);
        return LoginResource::buildResult($accessToken,$refreshToken);
    }

}
