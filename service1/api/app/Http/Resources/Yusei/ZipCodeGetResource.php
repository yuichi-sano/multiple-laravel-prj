<?php

namespace App\Http\Resources\Yusei;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Yusei\ZipCodeInfoResultDefinition;
use App\Http\Resources\Definition\Yusei\ZipCodeInfoResultDefinitionZipCodeList;
use Illuminate\Support\Facades\Log;
use packages\domain\model\zipcode\YuseiZipDiffList;


class ZipCodeGetResource extends AbstractJsonResource
{
    public static function buildResult(YuseiZipDiffList $yuseiZipDiffList): ZipCodeGetResource
    {
        $definition = new ZipCodeInfoResultDefinition();
        foreach ($yuseiZipDiffList as $diffList) {
            $definition->setDifferenceNumber($diffList->count());
            foreach ($diffList as $diff) {
                $diffResult = new ZipCodeInfoResultDefinitionZipCodeList();
                $diffResult->setNew($diff->getNew());
                $diffResult->setOld($diff->getOld());
                if (mb_ereg("[ｱ-ﾝ]", $diff->getNew()) || mb_ereg("[ｱ-ﾝ]", $diff->getOld())) {
                    $definition->addYuseiList($diffResult);
                } else {
                    $definition->addZipCodeList($diffResult);
                }
            }
        }

        return new ZipCodeGetResource($definition);
    }
}
