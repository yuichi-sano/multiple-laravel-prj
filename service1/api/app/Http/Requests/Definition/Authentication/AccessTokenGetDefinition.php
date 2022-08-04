<?php

namespace App\Http\Requests\Definition\Authentication;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class AccessTokenGetDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //refreshToken
    protected string $refreshToken = 'required|string';
}
