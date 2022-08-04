<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Device\WorkplaceListResultDefinition;
use packages\domain\model\facility\workplace\WorkplaceList;

class WorkplaceResource extends AbstractJsonResource
{
    public static function buildResult(
        WorkplaceList $workplaceList,
    ): WorkplaceResource {
        $definition = new WorkplaceListResultDefinition();
        $definition->setWorkplaceList($workplaceList);


        return new WorkplaceResource($definition);
    }
}
