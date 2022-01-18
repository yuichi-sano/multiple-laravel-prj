<?php

namespace App\Http\Controllers\Authentication;

use App\Exceptions\WebAPIException;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\Authentication\LoginResource;
use Illuminate\Support\Facades\Auth;
use packages\domain\model\authentication\authorization\AccessTokenFactory;
use packages\domain\model\authentication\authorization\RefreshTokenFactory;
use packages\service\authentication\AccountAuthenticationInterface;
use packages\service\UserGetInterface;

class LoginController extends BaseController
{
    private AccessTokenFactory $accessTokenFactory;
    private RefreshTokenFactory $refreshTokenFactory;
    private AccountAuthenticationInterface $accountAuthentication;

    public function __construct(AccessTokenFactory $accessTokenFactory,RefreshTokenFactory $refreshTokenFactory,AccountAuthenticationInterface $accountAuthentication)
    {
        $this->accessTokenFactory = $accessTokenFactory;
        $this->refreshTokenFactory = $refreshTokenFactory;
        $this->accountAuthentication = $accountAuthentication;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws WebAPIException
     */
    public function login(LoginRequest $request)
    {

        //Auth::guard('api')->getProvider()->setHasher(app('md5hash'));
        if (! Auth::attempt($request->validated())) {
            throw new WebAPIException('W_0000000',[],500);
        }
        $account = Auth::getLastAttempted();
        $authedAccount = $this->accountAuthentication->execute($account);

        return LoginResource::buildResult($authedAccount->getAccessToken(),$authedAccount->getRefreshToken());
    }


}
