<?php

namespace App\Http\Resources\Definition\Authentication;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class LoginResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{

    
    //アクセストークン
    protected string $accessToken;
       

    //リフレッシュトークン
    protected string $refreshToken;
       

    
    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    

    /**
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
    

    
    /**
     * @param mixed accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = (string) $accessToken;
    }
    

    /**
     * @param mixed refreshToken
     */
    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = (string) $refreshToken;
    }
    

}
