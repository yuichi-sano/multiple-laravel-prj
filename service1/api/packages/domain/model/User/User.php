<?php

declare(strict_types=1);

namespace packages\domain\model\User;

class User
{
    private UserId $userId;
    private UserProfile $userProfile;
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
     * @return Address
     */
    public function getUserProfile(): UserProfile
    {
        return $this->userProfile;
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
        'userId'
    ];

}
