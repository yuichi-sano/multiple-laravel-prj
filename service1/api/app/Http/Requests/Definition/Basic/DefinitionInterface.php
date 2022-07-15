<?php

namespace App\Http\Requests\Definition\Basic;

interface DefinitionInterface
{
    public function buildValidateRules();

    public function transform(array $attrs);

    public function buildAttribute();
}
