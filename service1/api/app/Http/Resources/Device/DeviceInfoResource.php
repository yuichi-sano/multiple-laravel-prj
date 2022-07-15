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
            $deviceHostInfoResult->setHostId($device->getDeviceId()->getValue());
            $deviceHostInfoResult->setHostName($device->getDeviceName()->getValue());
            $deviceHostInfoResult->setHostIp($device->getDeviceIpAddress()->getValue());
            $deviceHostInfoResult->setWorkplaceName(
                $device->getWorkplace()->getWorkplaceName()->getValue()
            );
            $deviceHostInfoResult->setFacilityCode(
                $device->getWorkplace()->getWorkplaceCode()->getValue()
            );

            $deviceHostInfoResult->setLocation($device->getDeviceLocation()->getValue());

            foreach ($device->getDeviceList() as $hTDevice) {
                if ($hTDevice->getDeviceId()->getValue()) {//エラーガード
                    $deviceResult = new DeviceInfoResultDefinitionDeviceListAddressList();
                    $deviceResult->setDeviceId($hTDevice->getDeviceId()->getValue());
                    $deviceResult->setDeviceIp($hTDevice->getDeviceIpAddress()->getValue());
                    $deviceResult->setLocation($hTDevice->getDeviceLocationMemo()->getValue());
                    $deviceResult->setSlipType($hTDevice->getDeviceType()->getValue());

                    $deviceHostInfoResult->addDeviceList($deviceResult);
                }
            }

            $definition->addDeviceList($deviceHostInfoResult);
        }

        $pageableResult = new PageableResultDefinition($pageable, $count);
        $definition->setPage($pageableResult);

        return new DeviceInfoResource($definition);
    }
}
