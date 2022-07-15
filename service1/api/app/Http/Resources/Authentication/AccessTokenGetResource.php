<?php

namespace App\Http\Resources\Authentication;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Authentication\AccessTokenGetResultDefinition;
use packages\domain\model\authentication\authorization\AccessToken;
use packages\domain\model\authentication\authorization\RefreshToken;


class AccessTokenGetResource extends AbstractJsonResource
{
    public static function buildResult(AccessToken $token, RefreshToken $refreshToken): AccessTokenGetResource
    {
        $definition = new AccessTokenGetResultDefinition();
        $definition->setAccessToken($token->toString());
        $definition->setRefreshToken($refreshToken->toString());
        return new AccessTokenGetResource($definition);
    }
}

