<?php

namespace App\Http\Resources\Definition\Yusei;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class ZipCodeInfoResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //件数の差分
    protected int $differenceNumber;
    //リスト
    protected array $zipCodeList;
    protected array $yuseiList;

    /**
     * @return mixed
     */
    public function getDifferenceNumber()
    {
        return $this->differenceNumber;
    }

    /**
     * @return mixed
     */
    public function getZipCodeList()
    {
        return $this->zipCodeList;
    }

    /**
     * @param mixed differenceNumber
     */
    public function setDifferenceNumber(int $differenceNumber): void
    {
        $this->differenceNumber = (int)$differenceNumber;
    }

    /**
     * @param mixed zipCodeList
     */
    public function addZipCodeList(ZipCodeInfoResultDefinitionZipCodeList $zipCodeList): void
    {
        $this->zipCodeList[] = $zipCodeList;
    }

    /**
     * @param mixed zipCodeList
     */
    public function addYuseiList(ZipCodeInfoResultDefinitionZipCodeList $zipCodeList): void
    {
        $this->yuseiList[] = $zipCodeList;
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

    /**
     * @param mixed zipCodeList
     */
    public function setYuseiCodeList(array $zipCodeList): void
    {
        foreach ($zipCodeList as $unit) {
            $this->addYuseiList($unit);
        }
    }
}
