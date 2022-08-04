<?php

namespace App\Http\Resources\Yusei;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Yusei\ZipCodeSearchResultDefinition;
use App\Http\Resources\Definition\Yusei\ZipCodeSearchResultDefinitionZipCodeList;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangouList;
use packages\domain\model\zipcode\YuseiYubinBangouList;


class ZipCodeSearchResource extends AbstractJsonResource
{
    public static function buildResult(MergeZipYuseiYubinBangouList $yubinBangouList): ZipCodeSearchResource
    {
        $definition = new ZipCodeSearchResultDefinition();
        $zipCodeArr = [];
        foreach ($yubinBangouList as $yubinBangou) {
            $zipCode = new ZipCodeSearchResultDefinitionZipCodeList();
            $zipCode->setId($yubinBangou->getId()->getValue());
            $zipCode->setZipCode($yubinBangou->getZipCode()->getValue());
            $zipCode->setKenCode($yubinBangou->getPrefectureCode());
            $zipCode->setKenmei($yubinBangou->getPrefecture());
            $zipCode->setKenmeiKana($yubinBangou->getPrefectureKana());
            $zipCode->setSikuCode($yubinBangou->getJis()->getValue());
            $zipCode->setSikumei($yubinBangou->getCity());
            $zipCode->setSikumeiKana($yubinBangou->getCityKana());
            $zipCode->setSikuika($yubinBangou->getTownArea());
            $zipCode->setSikuikaKana($yubinBangou->getTownAreaKana());
            $zipCodeArr[] = $zipCode;
        }
        $definition->setZipCodeList($zipCodeArr);
        return new ZipCodeSearchResource($definition);
    }
}
