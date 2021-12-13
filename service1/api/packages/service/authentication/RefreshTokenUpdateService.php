<?php

namespace packages\service\authentication;

use packages\domain\model\authentication\authorization\RefreshToken;
use packages\domain\model\authentication\authorization\RefreshTokenFactory;


class RefreshTokenUpdateService implements RefreshTokenUpdateInterface
{
    protected RefreshTokenFactory $refreshTokenFactory;

    public function __construct(RefreshTokenFactory  $refreshTokenFactory)
    {
        $this->refreshTokenFactory = $refreshTokenFactory;
    }
    public function execute (RefreshToken $refreshToken): void{
        $this->refreshTokenFactory->update($refreshToken);
    }

}
