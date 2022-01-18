<?php

namespace App\Http\Resources\Authentication;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Authentication\LoginResultDefinition;
use packages\domain\model\authentication\authorization\AccessToken;
use packages\domain\model\authentication\authorization\RefreshToken;


class LoginResource  extends AbstractJsonResource
{
    public static function buildResult(AccessToken $token, RefreshToken $refreshToken): LoginResource
    {
        $definition = new LoginResultDefinition();
        $definition->setAccessToken($token->toString());
        $definition->setRefreshToken($refreshToken->toString());
        return new LoginResource($definition);
    }
}

