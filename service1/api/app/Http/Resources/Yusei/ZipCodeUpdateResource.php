<?php

namespace App\Http\Resources\Yusei;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Yusei\ZipCodeUpdateResultDefinition;


class ZipCodeUpdateResource extends AbstractJsonResource
{
    public static function buildResult(): ZipCodeUpdateResource
    {
        $definition = new ZipCodeUpdateResultDefinition();
        return new ZipCodeUpdateResource($definition);
    }
}