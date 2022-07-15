<?php

namespace App\Http\Resources\Definition\Device;

use App\Http\Resources\Definition\Basic\PageableResultDefinition;
use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class DeviceInfoResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //端末情報リスト
    protected array $deviceList;
    //ページネーション
    protected PageableResultDefinition $page;

    /**
     * @return mixed
     */
    public function getDeviceList()
    {
        return $this->deviceList;
    }

    /**
     * @param mixed deviceList
     */
    public function addDeviceList(DeviceInfoResultDefinitionDeviceList $deviceList): void
    {
        $this->deviceList[] = $deviceList;
    }

    /**
     * @param mixed deviceList
     */
    public function setDeviceList(array $deviceList): void
    {
        foreach ($deviceList as $unit) {
            $this->addDeviceList($unit);
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
