<?php

namespace App\Http\Requests\Definition\Sample;
use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class SampleChildDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */
    //HOGE
    protected string $fuga = 'required';


}
