<?php

namespace App\Http\Resources\Definition\Device;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class DeviceDetailResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //システム端末ホストID
    protected int $hostId;
    //システム端末ホスト名
    protected string $hostName;
    //IPアドレス
    protected ?string $hostIp;
    //工場
    protected string $facilityCode;
    protected string $workplaceName;
    //設置場所
    protected ?string $location;
    //システム子機リスト
    protected array $deviceList;

    /**
     * @return mixed
     */
    public function getHostId()
    {
        return $this->hostId;
    }

    /**
     * @return mixed
     */
    public function getHostName()
    {
        return $this->hostName;
    }

    /**
     * @return mixed
     */
    public function getHostIp()
    {
        return $this->hostIp;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getDeviceList()
    {
        return $this->deviceList;
    }

    /**
     * @param mixed hostName
     */
    public function setHostId(int $hostId): void
    {
        $this->hostId = (int)$hostId;
    }

    /**
     * @param mixed hostName
     */
    public function setHostName(string $hostName): void
    {
        $this->hostName = (string)$hostName;
    }

    /**
     * @param mixed hostIp
     */
    public function setHostIp(?string $hostIp): void
    {
        $this->hostIp = (string)$hostIp;
    }

    /**
     * @return string
     */
    public function getFacilityCode(): string
    {
        return $this->facilityCode;
    }

    /**
     * @param string $facilityCode
     */
    public function setFacilityCode(string $facilityCode): void
    {
        $this->facilityCode = $facilityCode;
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
    public function setWorkplaceName(string $workplaceName): void
    {
        $this->workplaceName = $workplaceName;
    }

    /**
     * @param mixed location
     */
    public function setLocation(?string $location): void
    {
        $this->location = (string)$location;
    }

    /**
     * @param mixed deviceList
     */
    public function addDeviceList(DeviceInfoResultDefinitionDeviceListAddressList $deviceList): void
    {
        $this->deviceList[] = $deviceList;
    }

    /**
     * @param mixed deviceList
     */
    public function setDeviceList(array $deviceList): void
    {
        foreach ($deviceList as $unit) {
            $this->addDeviceList($unit);
        }
    }
}
