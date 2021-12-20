<?php

declare(strict_types=1);

namespace packages\domain\model\User;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    private UserId $userId;
    private string $name;
    private Addresses $addresses;


    /**
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId->toInteger();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Address
     */
    public function getAddresses(): Addresses
    {
        return $this->addresses;
    }

    public function hasGrants(): boolean
    {
        return $this->id == 1;
    }

    public  array $collectionKeys = [
        'userId','name'
    ];

}
