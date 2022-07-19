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
    protected string $workplaceId = 'integer';
    //ページ
    protected string $page = 'integer';
    //パーページ
    protected string $perPage = 'integer';

}
