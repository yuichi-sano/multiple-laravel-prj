<?php

namespace App\Http\Requests\Device;

use App\Http\Requests\Basic\AbstractFormRequest;
use App\Http\Requests\Definition\Device\DeviceHostRegisterDefinition;
use packages\domain\model\facility\device\Device;
use packages\domain\model\facility\device\DeviceId;
use packages\domain\model\facility\device\DeviceName;
use packages\domain\model\facility\device\DeviceUserId;
use packages\domain\model\facility\device\DeviceIpAddress;
use packages\domain\model\facility\device\DeviceList;
use packages\domain\model\facility\workplace\Workplace;
use packages\domain\model\facility\workplace\WorkplaceName;
use packages\domain\model\facility\workplace\WorkplaceId;

class DeviceRegisterRequest extends AbstractFormRequest
{
    public function __construct(DeviceHostRegisterDefinition $definition = null)
    {
        parent::__construct($definition);
    }

    protected function transform(array $attrs): array
    {
        return $attrs;
    }

    public function toDevice(): Device
    {
        $workplaceId = new WorkplaceId($this->workplaceId);

        $workplace = new Workplace(
            $workplaceId,
            new WorkplaceName(),
            new DeviceList(),
        );

        return new Device(
            new DeviceId(),
            new DeviceName($this->name),
            new DeviceUserId($this->getAuthedUser()->getUserId()->getValue()),
            new DeviceIpAddress($this->ip),
            $workplace,
        );
    }
}
