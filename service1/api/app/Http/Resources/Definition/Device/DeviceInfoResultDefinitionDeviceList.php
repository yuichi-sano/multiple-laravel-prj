<?php

namespace App\Http\Resources\Definition\Device;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class DeviceInfoResultDefinitionDeviceList extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //端末ID
    protected int $id;
    //端末名
    protected string $name;
    //IPアドレス
    protected string $ip;
    //拠点ID
    protected string $workplaceId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
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


}
