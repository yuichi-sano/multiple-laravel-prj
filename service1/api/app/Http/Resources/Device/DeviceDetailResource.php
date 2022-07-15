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

        //$definition->addDeviceList($deviceHostInfoResult);


        return new DeviceInfoResource($deviceHostInfoResult);
    }
}
