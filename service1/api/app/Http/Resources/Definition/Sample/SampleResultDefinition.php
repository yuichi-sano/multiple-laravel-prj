<?php

namespace App\Http\Resources\Definition\Sample;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class SampleResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{

    
    //アクセストークン
    protected string $accessToken;
       

    //リフレッシュトークン
    protected string $refreshToken;
       

    //リフレッシュトークン
    protected SampleResultDefinitionUser $user;
       

    
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
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
    

    /**
     * @param mixed user
     */
    public function setUser(SampleResultDefinitionUser $user): void
    {
        $this->user =  $user;
    }
    

}
