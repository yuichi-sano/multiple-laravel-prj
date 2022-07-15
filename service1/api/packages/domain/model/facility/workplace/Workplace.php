<?php

declare(strict_types=1);

namespace packages\domain\model\facility\workplace;


use packages\domain\model\facility\device\DeviceList;

class Workplace
{
    protected WorkplaceId $workplaceId;
    protected WorkplaceName $workplaceName;
    protected DeviceList $deviceList;

    public function __construct(
        WorkplaceId $workplaceId,
        WorkplaceName $workplaceName,
        DeviceList $deviceList,
    ) {
        $this->workplaceId = $workplaceId;
        $this->workplaceName = $workplaceName;
        $this->deviceList = $deviceList;
    }

    /**
     * @return WorkplaceId
     */
    public function getWorkplaceId(): WorkplaceId
    {
        return $this->workplaceId;
    }

    public function getDevicelList(): DeviceList
    {
        return $this->deviceList;
    }
}
