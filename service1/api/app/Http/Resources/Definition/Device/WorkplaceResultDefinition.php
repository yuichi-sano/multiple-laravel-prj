<?php

namespace App\Http\Resources\Definition\Workplace;

use App\Http\Resources\Definition\Basic\PageableResultDefinition;
use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class WorkplaceResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //端末情報リスト
    protected array $workplaceList;

    /**
     * @return mixed
     */
    public function getWorkplaceList()
    {
        return $this->workplaceList;
    }


}
