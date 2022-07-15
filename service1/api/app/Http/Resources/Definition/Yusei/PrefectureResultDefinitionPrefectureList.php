<?php

namespace App\Http\Resources\Definition\Yusei;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class PrefectureResultDefinitionPrefectureList extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //都道府県ID
    protected string $prefectureId;
    //都道府県名
    protected string $prefectureName;

    /**
     * @return mixed
     */
    public function getPrefectureId()
    {
        return $this->prefectureId;
    }

    /**
     * @return mixed
     */
    public function getPrefectureName()
    {
        return $this->prefectureName;
    }

    /**
     * @param mixed prefectureId
     */
    public function setPrefectureId(string $prefectureId): void
    {
        $this->prefectureId = (string)$prefectureId;
    }

    /**
     * @param mixed prefectureName
     */
    public function setPrefectureName(string $prefectureName): void
    {
        $this->prefectureName = (string)$prefectureName;
    }
}
