<?php

namespace App\Http\Requests\Definition\Device;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class DeviceHostRegisterDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //ホスト端末名
    protected string $name = 'required|string';
    //IPアドレス
    protected string $ip = 'required|ip_address';
    //設備コード
    protected string $workplaceId = 'required|string';

}
