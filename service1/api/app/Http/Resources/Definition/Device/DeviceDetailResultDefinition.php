<?php

namespace App\Http\Resources\Definition\Device;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class DeviceDetailResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //システム端末ホストID
    protected int $id;
    //システム端末ホスト名
    protected string $name;
    //IPアドレス
    protected ?string $ip;
    //工場
    protected string $workplaceId;
    protected ?string $workplaceName;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getIp()
    {
        return $this->ip;
    }


    /**
     * @param mixed name
     */
    public function setId(int $id): void
    {
        $this->id = (int)$id;
    }

    /**
     * @param mixed name
     */
    public function setName(string $name): void
    {
        $this->name = (string)$name;
    }

    /**
     * @param mixed ip
     */
    public function setIp(?string $ip): void
    {
        $this->ip = (string)$ip;
    }

    /**
     * @return string
     */
    public function getWorkplaceId(): string
    {
        return $this->workplaceId;
    }

    /**
     * @param string $workplaceId
     */
    public function setWorkplaceId(string $workplaceId): void
    {
        $this->workplaceId = $workplaceId;
    }

    /**
     * @return string
     */
    public function getWorkplaceName(): string
    {
        return $this->workplaceName;
    }

    /**
     * @param string $workplaceName
     */
    public function setWorkplaceName(?string $workplaceName): void
    {
        $this->workplaceName = $workplaceName;
    }
}
