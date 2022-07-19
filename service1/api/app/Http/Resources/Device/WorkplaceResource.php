<?php

namespace App\Http\Resources\Workplace;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Workplace\WorkplaceListResultDefinition;
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
