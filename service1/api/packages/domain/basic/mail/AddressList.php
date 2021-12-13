<?php

namespace packages\domain\basic\mail;

use Ramsey\Collection\Collection;

class AddressList extends Collection
{
    /**
     * @return Address[]
     */
    public function __construct()
    {
        parent::__construct(Address::class);
    }

    /**
     * @return Address[]
     */
    public function getIterator(): \Traversable
    {
        return parent::getIterator();
    }

    /**
     * 連想配列にコンバートします
     * @return array
     */
    public function toArray(): array
    {
        $toArray=[];
        foreach ($this->data as $data){
            $toArray[$data->personal] = $data->address;
        }
        return $toArray;
    }

}


