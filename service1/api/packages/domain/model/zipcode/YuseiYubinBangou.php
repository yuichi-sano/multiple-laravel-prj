<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

class YuseiYubinBangou
{
    private ZipCodeJis $jis;
    private ZipCodeOldPostalCode $zipCode5;
    private ZipCodePostalCode $zipCode;
    private string $prefecture;
    private string $prefectureKana;
    private int $prefectureCode;
    private string $city;
    private string $cityKana;
    private string $townArea;
    private string $townAreaKana;
    private string $isOneTownByMultiZipCode;
    private string $isNeedSmallAreaAddress;
    private string $isChoume;
    private string $isMultiTownByOnePostCode;
    private string $updated;
    private string $updateReason;
    private ?ZipCodeUserId $userId = null;
    private ?ZipCodeAuditDate $auditDate = null;

    public function __construct(
        ZipCodeJis $jis,
        ZipCodeOldPostalCode $zipCode5,
        ZipCodePostalCode $zipCode,
        string $prefectureKana,
        string $cityKana,
        string $townAreaKana,
        string $prefecture,
        int $prefectureCode,
        string $city,
        string $townArea,
        string $isOneTownByMultiZipCode,
        string $isNeedSmallAreaAddress,
        string $isChoume,
        string $isMultiTownByOnePostCode,
        string $updated,
        string $updateReason
    ) {
        $this->id = $id;
        $this->jis = $jis;
        $this->zipCode5 = $zipCode5;
        $this->zipCode = $zipCode;
        $this->prefectureKana = $prefectureKana;
        $this->cityKana = $cityKana;
        $this->townAreaKana = $townAreaKana;
        $this->prefecture = $prefecture;
        $this->prefectureCode = $prefectureCode;
        $this->city = $city;
        $this->townArea = $townArea;
        $this->isOneTownByMultiZipCode = $isOneTownByMultiZipCode;
        $this->isNeedSmallAreaAddress = $isNeedSmallAreaAddress;
        $this->isChoume = $isChoume;
        $this->isMultiTownByOnePostCode = $isMultiTownByOnePostCode;
        $this->updated = $updated;
        $this->updateReason = $updateReason;
    }

    public array $collectionKeys = ['id'];

    /* Getter */
    public function getJis(): ZipCodeJis
    {
        return $this->jis;
    }

    public function getZipCode5(): ZipCodeOldPostalCode
    {
        return $this->zipCode5;
    }

    public function getZipCode(): ZipCodePostalCode
    {
        return $this->zipCode;
    }

    public function getPrefecture(): string
    {
        return $this->prefecture;
    }

    public function getPrefectureCode(): int
    {
        return $this->prefectureCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getTownArea(): string
    {
        return $this->townArea;
    }

    public function getTownAreaKana(): string
    {
        return $this->townAreaKana;
    }

    /**
     * @return string
     */
    public function getPrefectureKana(): string
    {
        return $this->prefectureKana;
    }

    /**
     * @return string
     */
    public function getCityKana(): string
    {
        return $this->cityKana;
    }

    /**
     * @return string
     */
    public function getIsOneTownByMultiZipCode(): string
    {
        return $this->isOneTownByMultiZipCode;
    }

    /**
     * @return string
     */
    public function getIsNeedSmallAreaAddress(): string
    {
        return $this->isNeedSmallAreaAddress;
    }

    /**
     * @return string
     */
    public function getIsChoume(): string
    {
        return $this->isChoume;
    }

    /**
     * @return string
     */
    public function getIsMultiTownByOnePostCode(): string
    {
        return $this->isMultiTownByOnePostCode;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @return string
     */
    public function getUpdateReason(): string
    {
        return $this->updateReason;
    }

    /**
     * @return ZipCodeUserId|null
     */
    public function getUserId(): ?ZipCodeUserId
    {
        return $this->userId;
    }

    /**
     * @return ZipCodeAuditDate|null
     */
    public function getAuditDate(): ?ZipCodeAuditDate
    {
        return $this->auditDate;
    }

    /* Setter */
    public function setTownArea(string $townArea)
    {
        $this->townArea = $townArea;
    }

    public function setTownAreaKana(string $townAreaKana)
    {
        $this->townAreaKana = $townAreaKana;
    }
}
