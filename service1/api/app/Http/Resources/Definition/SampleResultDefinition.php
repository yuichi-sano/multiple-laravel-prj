<?php

namespace App\Http\Resources\Definition;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;

class SampleResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{

    //住所候補リスト
    protected array $addresses = [];

    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param mixed addresses
     */
    public function addAddresses( $addresses): void
    {
        $this->addresses[] = $addresses;
    }

    /**
     * @param mixed addresses
     */
    public function setAddresses(array $addresses): void
    {
        foreach($addresses as $unit){
           $this->addAddresses($unit);
        }
    }





}
