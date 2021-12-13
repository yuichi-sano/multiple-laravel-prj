<?php

namespace App\Http\Resources\Sample;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Sample\SampleResultDefinition;


class SampleResource  extends AbstractJsonResource
{
    public static function buildResult(): SampleResource
    {
        $definition = new SampleResultDefinition();
        return new SampleResource($definition);
    }
}
