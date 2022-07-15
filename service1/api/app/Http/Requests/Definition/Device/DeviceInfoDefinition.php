<?php

namespace App\Http\Requests\Definition\Device;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class DeviceInfoDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //設備コード
    protected string $facilityCode = 'string';
    //ページ
    protected string $page = 'integer';
    //パーページ
    protected string $perPage = 'integer';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [

        ];
    }
}
