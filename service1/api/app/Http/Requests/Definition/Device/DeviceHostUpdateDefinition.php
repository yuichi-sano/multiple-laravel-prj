<?php

namespace App\Http\Requests\Definition\Device;

use App\Http\Requests\Definition\Basic\DefinitionInterface;
use App\Http\Requests\Definition\Basic\AbstractRequestDefinition;

class DeviceHostUpdateDefinition extends AbstractRequestDefinition implements DefinitionInterface
{
    /**
     * HttpRequestParameter
     * @var string
     */

    //ホスト端末名
    protected string $hostName = 'required|string';
    //IPアドレス
    protected string $hostIp = 'required|ip_address';
    //設備コード
    protected string $facilityCode = 'required|string';
    //設置場所
    protected string $location = 'string';
    //子機端末リスト
    protected string $deviceList = 'required|collectionObject';

    /** オーバーライド */
    public function childDefinition(): array
    {
        return [
            'deviceList' => [new DeviceUpdateDefinition()],
        ];
    }
}
