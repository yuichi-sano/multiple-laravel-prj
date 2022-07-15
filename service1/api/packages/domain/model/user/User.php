<?php

declare(strict_types=1);

namespace packages\domain\model\user;

class User
{
    private UserId $userId;

    private Addresses $addresses;

    /**
     *
     * @return int
     */
    public function getUserId(): string
    {
        return $this->userId->getValue();
    }

    /**
     * @return Address
     */
    public function getAddresses(): Addresses
    {
        return $this->addresses;
    }

    public function hasGrants(): bool
    {
        return $this->userId == 1;
    }

    public array $collectionKeys = [
        'UserId'
    ];
}
