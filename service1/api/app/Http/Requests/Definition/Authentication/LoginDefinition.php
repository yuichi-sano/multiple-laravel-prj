<?php

namespace App\Http\Requests\Definition\Authentication;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class LoginDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //ログインID
    protected string $userId = 'required|string';
    //ログインPASS
    protected string $password = 'required|string';
}
