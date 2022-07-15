<?php

namespace App\Http\Resources\Definition\Yusei;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class PrefectureResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //都道府県リスト
    protected array $prefectureList;

    /**
     * @return mixed
     */
    public function getPrefectureList()
    {
        return $this->prefectureList;
    }

    /**
     * @param mixed prefectureList
     */
    public function addPrefectureList(PrefectureResultDefinitionPrefectureList $prefectureList): void
    {
        $this->prefectureList[] = $prefectureList;
    }

    /**
     * @param mixed prefectureList
     */
    public function setPrefectureList(array $prefectureList): void
    {
        foreach ($prefectureList as $unit) {
            $this->addPrefectureList($unit);
        }
    }
}
