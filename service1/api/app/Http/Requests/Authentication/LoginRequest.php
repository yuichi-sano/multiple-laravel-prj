<?php

namespace App\Http\Requests\Authentication;
use App\Http\Requests\Basic\AbstractFormRequest;
use App\Http\Requests\Definition\Authentication\LoginDefinition;

class LoginRequest  extends AbstractFormRequest
{
    public function __construct(LoginDefinition $definition = null)
    {
        parent::__construct($definition);
    }
    protected function transform(array $attrs): array
    {
        return $attrs;
    }
}
