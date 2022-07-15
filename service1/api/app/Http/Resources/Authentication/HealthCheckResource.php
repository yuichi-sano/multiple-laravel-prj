<?php

namespace App\Http\Resources\Authentication;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Authentication\HealthCheckResultDefinition;


class HealthCheckResource extends AbstractJsonResource
{
    public static function buildResult(): HealthCheckResource
    {
        $definition = new HealthCheckResultDefinition();
        return new HealthCheckResource($definition);
    }
}