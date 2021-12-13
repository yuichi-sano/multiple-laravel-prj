<?php

namespace packages\service\authentication;


use packages\domain\model\authentication\Account;
use packages\domain\model\authentication\authorization\AccessToken;
use packages\domain\model\authentication\authorization\RefreshToken;

interface AccessTokenGetInterface
{
    public function execute (RefreshToken $refreshToken): AccessToken;
}
