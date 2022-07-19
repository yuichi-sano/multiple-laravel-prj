<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

class YuseiLargeBusinessYubinBangou
{
    private ZipCodeJis $jis;
    private ZipCodeOldPostalCode $zipCode5;
    private ZipCodePostalCode $zipCode;
    private string $businessName;
    private string $businessNameKana;
    private string $prefecture;
    private string $city;
    private string $townArea;
    private string $address;
    private string $postalOfficeName;
    private string $kbn;
    private string $hasMultipleKbn;
    private string $fixKbn;
    private ?ZipCodeUserId $userId = null;

    public function __construct(
        ZipCodeJis $jis,
        ZipCodeOldPostalCode $zipCode5,
        ZipCodePostalCode $zipCode,
        string $businessName,
        string $businessNameKana,
        string $prefecture,
        string $city,
        string $townArea,
        string $address,
        string $postalOfficeName,
        string $kbn,
        string $hasMultipleKbn,
        string $fixKbn
    ) {
        $this->jis = $jis;
        $this->zipCode5 = $zipCode5;
        $this->zipCode = $zipCode;
        $this->businessName = $businessName;
        $this->businessNameKana = $businessNameKana;
        $this->prefecture = $prefecture;
        $this->city = $city;
        $this->townArea = $townArea;
        $this->address = $address;
        $this->postalOfficeName = $postalOfficeName;
        $this->kbn = $kbn;
        $this->hasMultipleKbn = $hasMultipleKbn;
        $this->fixKbn = $fixKbn;
    }

    public array $collectionKeys = ['jis', 'zip_code'];

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

    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getBusinessName(): string
    {
        return $this->businessName;
    }

    /**
     * @return string
     */
    public function getBusinessNameKana(): string
    {
        return $this->businessNameKana;
    }

    /**
     * @return string
     */
    public function getTownArea(): string
    {
        return $this->townArea;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPostalOfficeName(): string
    {
        return $this->postalOfficeName;
    }

    /**
     * @return string
     */
    public function getKbn(): string
    {
        return $this->kbn;
    }

    /**
     * @return string
     */
    public function getHasMultipleKbn(): string
    {
        return $this->hasMultipleKbn;
    }

    /**
     * @return string
     */
    public function getFixKbn(): string
    {
        return $this->fixKbn;
    }

    /**
     * @return ZipCodeUserId|null
     */
    public function getUserId(): ?ZipCodeUserId
    {
        return $this->userId;
    }
}
