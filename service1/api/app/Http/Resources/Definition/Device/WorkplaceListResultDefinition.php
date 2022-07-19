<?php

namespace App\Http\Resources\Definition\Workplace;

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
    public function setWorkplaceList(array $workplaceList): void
    {
        foreach ($workplaceList as $unit) {
            $this->addWorkplaceList($unit);
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
