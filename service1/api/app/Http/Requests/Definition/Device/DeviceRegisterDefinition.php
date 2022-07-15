<?php

namespace App\Http\Requests\Definition\Device;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class DeviceRegisterDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //端末IP
    protected string $deviceIp = 'required|ip_address';
    //設置場所
    protected string $location = 'required|string';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [

        ];
    }
}
