<?php

declare(strict_types=1);

namespace packages\domain\model\facility\workplace;


use packages\domain\model\facility\device\DeviceList;

class WorkplaceDevices
{
    protected Workplace $workplace;
    protected DeviceList $deviceList;


    public function __construct(
        Workplace $workplace,
        DeviceList $deviceList
    ) {
        $this->workplace = $workplace;
        $this->deviceList = $deviceList;

    }


    public array $collectionKeys = [
        'workplace'
    ];

}
