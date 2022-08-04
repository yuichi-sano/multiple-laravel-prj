<?php

namespace packages\service\authentication;

use App\Exceptions\WebAPIException;
use Illuminate\Support\Facades\Auth;
use packages\domain\model\authentication\Account;
use packages\domain\model\authentication\authorization\AccessToken;
use packages\domain\model\authentication\authorization\AccessTokenFactory;
use packages\domain\model\authentication\authorization\RefreshToken;
use packages\domain\model\authentication\authorization\RefreshTokenFactory;
use packages\domain\model\authentication\authorization\RefreshTokenRepository;

class AccountAuthenticationService implements AccountAuthenticationInterface
{
    protected AccessTokenFactory $accessTokenFactory;
    protected RefreshTokenFactory $refreshTokenFactory;
    protected RefreshTokenRepository $refreshTokenRepository;

    public function __construct(
        AccessTokenFactory $accessTokenFactory,
        RefreshTokenFactory $refreshTokenFactory,
        RefreshTokenRepository $refreshTokenRepository
    ) {
        $this->accessTokenFactory = $accessTokenFactory;
        $this->refreshTokenFactory = $refreshTokenFactory;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function execute(Account $account): Account
    {
        $token = $this->accessTokenFactory->create($account);
        $authenticationRefreshToken = $this->refreshTokenFactory->create($account);
        $this->refreshTokenRepository->save($authenticationRefreshToken);
        return $account->authenticationAccount($token, $authenticationRefreshToken->getRefreshToken());
    }
}
