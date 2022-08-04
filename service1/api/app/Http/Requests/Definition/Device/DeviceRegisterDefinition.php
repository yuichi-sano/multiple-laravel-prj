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
    protected string $ip = 'required|ip_address';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [

        ];
    }
}
