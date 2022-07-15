<?php

namespace packages\domain\model\facility\device;

use packages\domain\model\facility\device\criteria\DeviceCriteria;

interface DeviceRepository
{
    public function list(DeviceCriteria $criteria): DeviceList;

    public function listCount(DeviceCriteria $criteria): int;

    public function add(Device $device): DeviceId;

    public function update(Device $device): DeviceId;

    public function detail(DeviceId $deviceId): Device;

    public function isDuplicate(DeviceIpAddress $ipAddress): bool;

    public function existIpAddress(DeviceId $deviceId, DeviceIpAddress $ipAddress): bool;
}
