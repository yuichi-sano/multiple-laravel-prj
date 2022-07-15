<?php

namespace App\Http\Resources\Definition\Yusei;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class ZipCodeDeleteResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //返す値がない場合は空文字を返す
    protected string $empty = '';
}