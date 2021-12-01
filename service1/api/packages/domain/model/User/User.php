<?php

declare(strict_types=1);

namespace packages\Domain\Model\User;

class User
{
    private UserId $id;
    private string $name;
    private Address $address;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id->value();
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
    public function getAddress(): Address
    {
        return $this->address;
    }

    public function hasGrants(): boolean
    {
        return $this->id == 1;
    }
}
