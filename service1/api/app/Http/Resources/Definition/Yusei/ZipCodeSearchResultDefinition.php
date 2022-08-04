<?php

namespace App\Http\Resources\Definition\Yusei;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class ZipCodeSearchResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //リスト
    protected array $zipCodeList;

    /**
     * @return mixed
     */
    public function getZipCodeList()
    {
        return $this->zipCodeList;
    }

    /**
     * @param mixed zipCodeList
     */
    public function addZipCodeList(ZipCodeSearchResultDefinitionZipCodeList $zipCodeList): void
    {
        $this->zipCodeList[] = $zipCodeList;
    }

    /**
     * @param mixed zipCodeList
     */
    public function setZipCodeList(array $zipCodeList): void
    {
        foreach ($zipCodeList as $unit) {
            $this->addZipCodeList($unit);
        }
    }
}
