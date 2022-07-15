<?php

namespace App\Http\Requests\Definition\Yusei;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class ZipCodeIndividualRegisterDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //更新郵便番号
    protected string $zipCode = 'required|string|zipcode';
    //都道府県
    protected string $kenmei = 'required|string';
    //都道府県カナ
    protected string $kenmeiKana = 'required|string|half_kana';
    //都道府県コード
    protected string $kenCode = 'required|integer';
    //市区町村
    protected string $sikumei = 'required|string';
    protected string $sikumeiKana = 'required|string|half_kana';
    //市区町村コード
    protected string $sikuCode = 'required|integer|digits:5';
    protected string $town = 'required|string';
    protected string $townKana = 'required|string|half_kana';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [

        ];
    }
}
