<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use packages\domain\model\authentication\authorization\AccessTokenFactory;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ErrorCodeConst;
use App\Exceptions\WebAPIException;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\Authentication\LoginResource;
use packages\domain\model\authentication\authorization\RefreshTokenFactory;
use packages\service\authentication\AccountAuthenticationInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createAccessToken() : string
    {

        if (! Auth::attempt([
            'UserId' => '140',
            'password' => '140140',
        ])) {
            throw new WebAPIException(ErrorCodeConst::ERROR_401_UNAUTHORIZED, [], 401);
        }
        $account = Auth::getLastAttempted();
//        $account = $this->accountAuthentication->execute($account);

        //ログインしてtoken生成してリターンする処理
        $factory = new AccessTokenFactory();
        $token = $factory->create($account);
        return $token->getValue();
    }
}
