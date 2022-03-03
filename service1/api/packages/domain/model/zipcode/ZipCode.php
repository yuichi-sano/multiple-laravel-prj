<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

class ZipCode
{
    private string $jis;
    private string $zipCode5;
    private string $zipCode;
    private string $prefectureKana;
    private string $cityKana;
    private string $townAreaKana;
    private string $prefecture;
    private string $city;
    private string $townArea;
    private string $isOneTownByMultiZipCode;
    private string $isNeedSmallAreaAddress;
    private string $isChoume;
    private string $isMultiTownByOnePostCode;
    private string $updated;
    private string $updateReason;

    public function __construct($jis,
                                $zipCode5,
                                $zipCode,
                                $prefectureKana,
                                $cityKana,
                                $townAreaKana,
                                $prefecture,
                                $city,
                                $townArea,
                                $isOneTownByMultiZipCode,
                                $isNeedSmallAreaAddress,
                                $isChoume,
                                $isMultiTownByOnePostCode,
                                $updated,
                                $updateReason) {

        $this->jis                      = $jis;
        $this->zipCode5                 = $zipCode5;
        $this->zipCode                  = $zipCode;
        $this->prefectureKana           = $prefectureKana;
        $this->cityKana                 = $cityKana;
        $this->townAreaKana             = $townAreaKana;
        $this->prefecture               = $prefecture;
        $this->city                     = $city;
        $this->townArea                 = $townArea;
        $this->isOneTownByMultiZipCode  = $isOneTownByMultiZipCode;
        $this->isNeedSmallAreaAddress   = $isNeedSmallAreaAddress;
        $this->isChoume                 = $isChoume;
        $this->isMultiTownByOnePostCode = $isMultiTownByOnePostCode;
        $this->updated                  = $updated;
        $this->updateReason             = $updateReason;
    }

    public  array $collectionKeys = [];

    /* Getter */
    public function getTownArea(): string
    {
        return $this->townArea;
    }

    public function getTownAreaKana(): string
    {
        return $this->townAreaKana;
    }

    /* Setter */
    public function setTownArea(string $townArea) {
        $this->townArea = $townArea;
    }

    public function setTownAreaKana(string $townAreaKana) {
        $this->townAreaKana = $townAreaKana;
    }

}
