<?php

namespace App\Http\Requests\Definition\Device;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class DeviceUpdateDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //端末Id
    protected string $deviceId = 'integer';
    //端末種別
    protected string $deviceType = 'integer';
    //端末IP
    protected string $deviceIp = 'string';
    //設置場所
    protected string $location = 'required|string';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [

        ];
    }
}
