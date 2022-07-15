<?php

namespace App\Http\Resources\Definition\Yusei;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class ZipCodeSearchResultDefinitionZipCodeList extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //管理ID
    protected int $id;
    //更新郵便番号
    protected string $zipCode;
    //都道府県
    protected string $kenmei;
    protected string $kenmeiKana;
    //都道府県コード
    protected int $kenCode;
    //市区町村
    protected string $sikumei;
    protected string $sikumeiKana;
    protected string $sikuika;
    protected string $sikuikaKana;
    //市区町村コード
    protected int $sikuCode;

    /**
     * @param string $kenmeiKana
     */
    public function setKenmeiKana(string $kenmeiKana): void
    {
        $this->kenmeiKana = $kenmeiKana;
    }

    /**
     * @param string $sikumeiKana
     */
    public function setSikumeiKana(string $sikumeiKana): void
    {
        $this->sikumeiKana = $sikumeiKana;
    }

    /**
     * @param string $sikuika
     */
    public function setSikuika(string $sikuika): void
    {
        $this->sikuika = $sikuika;
    }

    /**
     * @param string $sikuikaKana
     */
    public function setSikuikaKana(string $sikuikaKana): void
    {
        $this->sikuikaKana = $sikuikaKana;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return mixed
     */
    public function getKenmei()
    {
        return $this->kenmei;
    }

    /**
     * @return mixed
     */
    public function getKenCode()
    {
        return $this->kenCode;
    }

    /**
     * @return mixed
     */
    public function getSikumei()
    {
        return $this->sikumei;
    }

    /**
     * @return mixed
     */
    public function getSikuCode()
    {
        return $this->sikuCode;
    }

    /**
     * @param mixed id
     */
    public function setId(int $id): void
    {
        $this->id = (int)$id;
    }

    /**
     * @param mixed zipCode
     */
    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = (string)$zipCode;
    }

    /**
     * @param mixed kenmei
     */
    public function setKenmei(string $kenmei): void
    {
        $this->kenmei = (string)$kenmei;
    }

    /**
     * @param mixed kenCode
     */
    public function setKenCode(int $kenCode): void
    {
        $this->kenCode = (int)$kenCode;
    }

    /**
     * @param mixed sikumei
     */
    public function setSikumei(string $sikumei): void
    {
        $this->sikumei = (string)$sikumei;
    }

    /**
     * @param mixed sikuCode
     */
    public function setSikuCode(int $sikuCode): void
    {
        $this->sikuCode = (int)$sikuCode;
    }
}
