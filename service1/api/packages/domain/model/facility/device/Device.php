<?php

declare(strict_types=1);

namespace packages\domain\model\facility\device;

use packages\domain\model\facility\workplace\Workplace;

class Device
{
    protected DeviceId $deviceId;
    protected DeviceName $deviceName;
    protected DeviceUserId $userId;
    protected DeviceIpAddress $deviceIpAddress;
    protected Workplace $workplace;


    public function __construct(
        DeviceId $deviceId,
        DeviceName $deviceName,
        DeviceUserId $userId,
        DeviceIpAddress $deviceIpAddress,
        Workplace $workplace,
    ) {
        $this->deviceId = $deviceId;
        $this->deviceName = $deviceName;
        $this->userId = $userId;
        $this->deviceIpAddress = $deviceIpAddress;
        $this->workplace = $workplace;
    }

    /**
     * @return DeviceId
     */
    public function getDeviceId(): DeviceId
    {
        return $this->deviceId;
    }

    /**
     * @return DeviceName
     */
    public function getDeviceName(): DeviceName
    {
        return $this->deviceName;
    }

    /**
     * @return DeviceIpAddress
     */
    public function getDeviceIpAddress(): DeviceIpAddress
    {
        return $this->deviceIpAddress;
    }


    /**
     * @return DeviceUserId|null
     */
    public function getUserId(): ?DeviceUserId
    {
        return $this->userId;
    }

    /**
     * @param DeviceUserId|null $userId
     */
    public function setUserId(?DeviceUserId $userId): void
    {
        $this->userId = $userId;
    }

    public array $collectionKeys = [
        'deviceId'
    ];

    /**
     * @return Workplace
     */
    public function getWorkplace(): Workplace
    {
        return $this->workplace;
    }
}
