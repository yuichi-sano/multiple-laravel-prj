<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Device\DeviceDetailResultDefinition;
use App\Http\Resources\Definition\Device\DeviceInfoResultDefinitionDeviceList;
use App\Http\Resources\Definition\Device\DeviceInfoResultDefinitionDeviceListAddressList;
use packages\domain\model\facility\device\Device;
use packages\domain\model\facility\device\DeviceList;

class DeviceDetailResource extends AbstractJsonResource
{
    public static function buildResult(Device $device): DeviceInfoResource
    {
        //$definition = new DeviceDetailResultDefinition();

        $deviceHostInfoResult = new DeviceDetailResultDefinition();
        $deviceHostInfoResult->setId($device->getDeviceId()->getValue());
        $deviceHostInfoResult->setName($device->getDeviceName()->getValue());
        $deviceHostInfoResult->setIp($device->getDeviceIpAddress()->getValue());
        $deviceHostInfoResult->setWorkplaceName(
            $device->getWorkplace()->getWorkplaceName()->getValue()
        );
        $deviceHostInfoResult->setWorkplaceId(
            $device->getWorkplace()->getWorkplaceId()->getValue()
        );

        return new DeviceInfoResource($deviceHostInfoResult);
    }
}
