<?php

namespace App\Http\Resources\Yusei;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Yusei\ZipCodeYuseiBulkResultDefinition;


class ZipCodeYuseiBulkResource extends AbstractJsonResource
{
    public static function buildResult(): ZipCodeyuseiBulkResource
    {
        $definition = new ZipCodeYuseiBulkResultDefinition();
        return new ZipCodeYuseiBulkResource($definition);
    }
}