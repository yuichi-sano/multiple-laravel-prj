<?php

namespace App\Http\Requests\Definition\Yusei;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class YuseiZipCodeUpdateDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //更新適用日時
    protected string $applyDate = 'string';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [

        ];
    }
}
