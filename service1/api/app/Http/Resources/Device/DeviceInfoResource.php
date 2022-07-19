<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Basic\PageableResultDefinition;
use App\Http\Resources\Definition\Device\DeviceInfoResultDefinition;
use App\Http\Resources\Definition\Device\DeviceInfoResultDefinitionDeviceList;
use App\Http\Resources\Definition\Device\DeviceInfoResultDefinitionDeviceListAddressList;
use packages\domain\model\facility\device\DeviceList;
use packages\domain\basic\page\Pageable;

class DeviceInfoResource extends AbstractJsonResource
{
    public static function buildResult(
        DeviceList $deviceList,
        int $count,
        Pageable $pageable
    ): DeviceInfoResource {
        $definition = new DeviceInfoResultDefinition();
        foreach ($deviceList as $device) {
            $deviceHostInfoResult = new DeviceInfoResultDefinitionDeviceList();
            $deviceHostInfoResult->setId($device->getDeviceId()->getValue());
            $deviceHostInfoResult->setName($device->getDeviceName()->getValue());
            $deviceHostInfoResult->setIp($device->getDeviceIpAddress()->getValue());
            $deviceHostInfoResult->setWorkplaceId(
                $device->getWorkplace()->getWorkplaceId()->getValue()
            );

            $definition->addDeviceList($deviceHostInfoResult);
        }

        $pageableResult = new PageableResultDefinition($pageable, $count);
        $definition->setPage($pageableResult);

        return new DeviceInfoResource($definition);
    }
}
