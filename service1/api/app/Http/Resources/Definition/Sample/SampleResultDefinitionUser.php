<?php

namespace App\Http\Resources\Definition\Sample;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class SampleResultDefinitionUser extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //ユーザーID
    protected int $userId;
    //名前
    protected string $name;
    //登録済み住所
    protected array $addressList;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAddressList()
    {
        return $this->addressList;
    }

    /**
     * @param mixed userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = (int)$userId;
    }

    /**
     * @param mixed name
     */
    public function setName(string $name): void
    {
        $this->name = (string)$name;
    }

    /**
     * @param mixed addressList
     */
    public function addAddressList(SampleResultDefinitionUserAddressList $addressList): void
    {
        $this->addressList[] = $addressList;
    }

    /**
     * @param mixed addressList
     */
    public function setAddressList(array $addressList): void
    {
        foreach ($addressList as $unit) {
            $this->addAddressList($unit);
        }
    }
}
