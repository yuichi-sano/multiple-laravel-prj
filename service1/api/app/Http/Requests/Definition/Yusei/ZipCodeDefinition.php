<?php

namespace App\Http\Requests\Definition\Yusei;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class ZipCodeDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //郵便番号
    protected string $zipCode = 'required|string|zipcode';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [

        ];
    }
}
