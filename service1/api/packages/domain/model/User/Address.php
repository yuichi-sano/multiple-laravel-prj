<?php

declare(strict_types=1);

namespace packages\Domain\Model\User;

class Address
{
    private string $street;

    private string $postalCode;

    private string $city;

    private string $country;

    public function __construct()
    {
        $this->street='';
        $this->postalCode='';
        $this->city='';
        $this->country='';
    }
}
