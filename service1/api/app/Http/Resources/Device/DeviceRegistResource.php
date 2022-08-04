<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Device\DeviceRegistResultDefinition;


class DeviceRegistResource extends AbstractJsonResource
{
    public static function buildResult(): DeviceRegistResource
    {
        $definition = new DeviceRegistResultDefinition();
        return new DeviceRegistResource($definition);
    }
}
