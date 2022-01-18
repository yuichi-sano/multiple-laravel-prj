<?php

declare(strict_types=1);

namespace packages\domain\model\User;

class Address
{

    private string $prefCode;

    private string $zip;

    private string $address;

    public function getAddress(){
        return $this->address;
    }

    public  array $collectionKeys = [
        'zip','prefCode','address'
    ];

}
