<?php

namespace App\Http\Requests\Sample;
use App\Http\Requests\Basic\AbstractFormRequest;
use App\Http\Requests\Definition\Sample\SampleDefinition;

class SampleRequest  extends AbstractFormRequest
{
    public function __construct(SampleDefinition $definition = null)
    {
        parent::__construct($definition);
    }
    protected function transform(array $attrs): array
    {
        return $attrs;
    }
    public function getHoge(){
        return $this->hoge;
    }
}
