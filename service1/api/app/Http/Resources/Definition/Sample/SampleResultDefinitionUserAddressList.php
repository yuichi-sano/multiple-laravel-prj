<?php

namespace App\Http\Resources\Definition\Sample;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class SampleResultDefinitionUserAddressList extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //住所
    protected string $address;

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed address
     */
    public function setAddress(string $address): void
    {
        $this->address = (string)$address;
    }
}
