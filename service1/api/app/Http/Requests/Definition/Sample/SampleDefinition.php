<?php

namespace App\Http\Requests\Definition\Sample;
use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class SampleDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */
    //HOGE
    /*
    protected string $hoge = 'required|string';
    protected string $child='object';
    protected string $arraychild='collectionObject';
    protected string $arraychild2='collectionObject|required';
    */

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [
            'child'=> new SampleChildDefinition(),
            'arraychild'=>[new SampleArrayChildDefinition()],
            'arraychild2'=>[new SampleArrayChildDefinition()]
        ];
    }

}
