<?php

namespace App\Http\Resources\Yusei;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Yusei\ZipCodeRegistResultDefinition;


class ZipCodeRegistResource extends AbstractJsonResource
{
    public static function buildResult(): ZipCodeRegistResource
    {
        $definition = new ZipCodeRegistResultDefinition();
        return new ZipCodeRegistResource($definition);
    }
}