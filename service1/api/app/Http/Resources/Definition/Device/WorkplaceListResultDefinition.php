<?php

namespace App\Http\Resources\Definition\Device;

use App\Http\Resources\Definition\Basic\PageableResultDefinition;
use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class WorkplaceListResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
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

    /**
     * @param mixed workplaceList
     */
    public function addWorkplace(WorkplaceResultDefinition $workplace): void
    {
        $this->workplaceList[] = $workplace;
    }

    /**
     * @param mixed workplaceList
     */
    public function setWorkplaceList($workplaceList): void
    {
        foreach ($workplaceList as $unit) {
            $workPlace = new WorkplaceResultDefinition();
            $workPlace->setWorkplaceId($unit->getWorkplaceId()->getValue());
            $workPlace->setWorkplaceName($unit->getWorkplaceName()->getValue());
            $this->addWorkplace($workPlace);
        }
    }

    /**
     * @param PageableResultDefinition $page
     * @return void
     */
    public function setPage(PageableResultDefinition $page): void
    {
        $this->page = $page;
    }
}
