<?php

declare(strict_types=1);

namespace packages\domain\model\facility\workplace;


use packages\domain\model\facility\device\DeviceList;

class Workplace
{
    protected WorkplaceId $workplaceId;
    protected WorkplaceName $workplaceName;


    public function __construct(
        WorkplaceId $workplaceId,
        WorkplaceName $workplaceName,
    ) {
        $this->workplaceId = $workplaceId;
        $this->workplaceName = $workplaceName;

    }

    /**
     * @return WorkplaceId
     */
    public function getWorkplaceId(): WorkplaceId
    {
        return $this->workplaceId;
    }

}
