<?php

namespace App\Http\Requests\Authentication;
use App\Http\Requests\Basic\AbstractFormRequest;
use App\Http\Requests\Definition\Authentication\AccessTokenGetDefinition;
use packages\domain\model\authentication\authorization\RefreshToken;

class AccessTokenGetRequest  extends AbstractFormRequest
{
    public function __construct(AccessTokenGetDefinition $definition = null)
    {
        parent::__construct($definition);
    }
    protected function transform(array $attrs): array
    {
        return $attrs;
    }
    
    public function toRefreshToken(){
        return new RefreshToken($this->refreshToken);
    }
}
