<?php

namespace App\Http\Resources\Definition\Device;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class DeviceRegistResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //返す値がない場合は空文字を返す
    protected string $empty = '';
}