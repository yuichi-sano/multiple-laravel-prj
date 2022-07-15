<?php

namespace App\Http\Resources\Yusei;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Yusei\ZipCodeDeleteResultDefinition;


class ZipCodeDeleteResource extends AbstractJsonResource
{
    public static function buildResult(): ZipCodeDeleteResource
    {
        $definition = new ZipCodeDeleteResultDefinition();
        return new ZipCodeDeleteResource($definition);
    }
}