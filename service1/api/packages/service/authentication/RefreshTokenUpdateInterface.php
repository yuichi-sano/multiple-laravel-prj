<?php

namespace packages\service\authentication;

use packages\domain\model\authentication\authorization\RefreshToken;

interface RefreshTokenUpdateInterface
{
    public function execute (RefreshToken $refreshToken): void;
}
