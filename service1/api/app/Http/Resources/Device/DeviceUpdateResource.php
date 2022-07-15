<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Device\DeviceUpdateResultDefinition;


class DeviceUpdateResource extends AbstractJsonResource
{
    public static function buildResult(): DeviceUpdateResource
    {
        $definition = new DeviceUpdateResultDefinition();
        return new DeviceUpdateResource($definition);
    }
}
