<?php

namespace packages\service\authentication;

use packages\domain\model\authentication\authorization\RefreshToken;
use packages\domain\model\authentication\authorization\RefreshTokenFactory;
use packages\domain\model\authentication\authorization\RefreshTokenRepository;


class RefreshTokenUpdateService implements RefreshTokenUpdateInterface
{
    protected RefreshTokenFactory $refreshTokenFactory;
    protected RefreshTokenRepository $refreshTokenRepository;

    public function __construct(RefreshTokenFactory  $refreshTokenFactory, RefreshTokenRepository $refreshTokenRepository)
    {
        $this->refreshTokenFactory = $refreshTokenFactory;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }
    public function execute (RefreshToken $refreshToken): RefreshToken{
        $authenticationRefreshToken = $this->refreshTokenFactory->update($refreshToken);
        $this->refreshTokenRepository->save($authenticationRefreshToken);
        return $authenticationRefreshToken->getRefreshToken();
    }

}
