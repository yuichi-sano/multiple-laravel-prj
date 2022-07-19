<?php

namespace App\Http\Resources\Definition\Device;

use App\Http\Resources\Definition\Basic\PageableResultDefinition;
use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class WorkplaceResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //端末情報
    protected int $workplaceId;
    protected string $workplaceName;

    /**
     * @return int
     */
    public function getWorkplaceId(): int
    {
        return $this->workplaceId;
    }

    /**
     * @param int $workplaceId
     */
    public function setWorkplaceId(int $workplaceId): void
    {
        $this->workplaceId = $workplaceId;
    }

    /**
     * @return string
     */
    public function getWorkplaceName(): string
    {
        return $this->workplaceName;
    }

    /**
     * @param string $workplaceName
     */
    public function setWorkplaceName(string $workplaceName): void
    {
        $this->workplaceName = $workplaceName;
    }


}
