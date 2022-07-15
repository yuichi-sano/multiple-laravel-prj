<?php

namespace App\Http\Requests\Definition\Yusei;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class ZipCodeIndividualUpdateDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //更新郵便番号
    protected string $zipCode = 'string';
    //都道府県
    protected string $kenmei = 'required|string';
    //都道府県コード
    protected string $kenCode = 'integer';
    //市区町村
    protected string $sikumei = 'required|string';
    //市区町村コード
    protected string $sikuCode = 'integer';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [

        ];
    }
}
