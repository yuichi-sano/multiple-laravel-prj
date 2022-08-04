<?php

namespace App\Http\Resources\Definition\Yusei;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class UpdateInfoResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //前回一括更新日
    protected string $bulkUpdateDate;
    //前回一括更新者
    protected string $bulkUser;
    //前回個別登録日
    protected string $addDate;
    //前回個別登録者
    protected ?string $user = null;
    //前回個別登録郵便番号
    protected string $zipCode;
    //前回個別登録都道府県
    protected string $kenmei;
    //前回個別登録都道府県コード
    protected int $kenCode;
    //前回個別登録市区町村
    protected string $sikumei;
    //前回個別登録市区町村コード
    protected int $sikuCode;

    /**
     * @return mixed
     */
    public function getBulkUpdateDate()
    {
        return $this->bulkUpdateDate;
    }

    /**
     * @return mixed
     */
    public function getBulkUser()
    {
        return $this->bulkUser;
    }

    /**
     * @return mixed
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
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
     * @param mixed bulkUpdateDate
     */
    public function setBulkUpdateDate(string $bulkUpdateDate): void
    {
        $this->bulkUpdateDate = (string)$bulkUpdateDate;
    }

    /**
     * @param mixed bulkUser
     */
    public function setBulkUser(string $bulkUser): void
    {
        $this->bulkUser = (string)$bulkUser;
    }

    /**
     * @param mixed addDate
     */
    public function setAddDate(string $addDate): void
    {
        $this->addDate = (string)$addDate;
    }

    /**
     * @param mixed User
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
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
