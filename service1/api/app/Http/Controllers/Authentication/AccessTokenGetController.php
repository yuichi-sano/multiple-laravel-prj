<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Requests\Authentication\AccessTokenGetRequest;
use App\Http\Resources\Authentication\AccessTokenGetResource;
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
    public function index(AccessTokenGetRequest $request, AccessTokenGetInterface $accessTokenGet): AccessTokenGetResource
    {

        $accessToken = $accessTokenGet->execute($request->toRefreshToken());
        $refreshToken = $this->refreshTokenUpdate->execute($request->toRefreshToken());
        return AccessTokenGetResource::buildResult($accessToken,$refreshToken);
    }

}
