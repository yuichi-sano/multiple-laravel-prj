<?php

namespace App\Http\Resources\Yusei;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Yusei\PrefectureResultDefinition;
use App\Http\Resources\Definition\Yusei\PrefectureResultDefinitionPrefectureList;
use packages\domain\model\prefecture\PrefectureList;

class PrefectureResource extends AbstractJsonResource
{
    public static function buildResult(PrefectureList $prefectureList): PrefectureResource
    {
        $definition = new PrefectureResultDefinition();
        $prefectureListArray = array();
        foreach ($prefectureList as $prefecture) {
            $prefectureResult = new PrefectureResultDefinitionPrefectureList();
            $prefectureResult->setPrefectureId($prefecture->getId()->getValue());
            $prefectureResult->setPrefectureName($prefecture->getPrefectureName()->getValue());

            $prefectureListArray[] = $prefectureResult;
        }
        $definition->setPrefectureList($prefectureListArray);

        return new PrefectureResource($definition);
    }
}
