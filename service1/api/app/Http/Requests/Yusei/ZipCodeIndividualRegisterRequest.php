<?php

namespace App\Http\Requests\Yusei;

use App\Http\Requests\Basic\AbstractFormRequest;
use App\Http\Requests\Definition\Yusei\ZipCodeIndividualRegisterDefinition;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangou;
use packages\domain\model\zipcode\ZipCode;
use packages\domain\model\zipcode\ZipCodeId;
use packages\domain\model\zipcode\ZipCodeJis;
use packages\domain\model\zipcode\ZipCodeOldPostalCode;
use packages\domain\model\zipcode\ZipCodePostalCode;

class ZipCodeIndividualRegisterRequest extends AbstractFormRequest
{
    public function __construct(ZipCodeIndividualRegisterDefinition $definition = null)
    {
        parent::__construct($definition);
    }

    protected function transform(array $attrs): array
    {
        return $attrs;
    }

    /**
     * リクエスト値をドメインモデルに整形
     * @return ZipCode
     */
    public function toZipCode(): ZipCode
    {
        $postalCode = new ZipCodePostalCode($this->zipCode);
        return new ZipCode(
            new ZipCodeId(),
            new ZipCodeJis($this->sikuCode),
            new ZipCodeOldPostalCode($postalCode->toMostOldStr()),
            $postalCode,
            $this->kenmeiKana,
            $this->sikumeiKana,
            $this->townKana,
            $this->kenmei,
            $this->kenCode,
            $this->sikumei,
            $this->town,
            0,
            0,
            0,
            0,
            0,
            0
        );
    }

    /**
     * リクエスト値をドメインモデルに整形
     * @return ZipCode
     */
    public function toMergeZipYuseiYubinBangou(int $id): MergeZipYuseiYubinBangou
    {
        $postalCode = new ZipCodePostalCode($this->zipCode);
        return new MergeZipYuseiYubinBangou(
            new ZipCodeId($id),
            new ZipCodeJis($this->sikuCode),
            new ZipCodeOldPostalCode($postalCode->toMostOldStr()),
            $postalCode,
            $this->kenmeiKana,
            $this->sikumeiKana,
            $this->townKana,
            $this->kenmei,
            $this->kenCode,
            $this->sikumei,
            $this->town,
            0,
            0,
            0,
            0,
            0,
            0
        );
    }
}
