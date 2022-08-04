<?php

namespace App\Http\Requests\Yusei;

use App\Http\Requests\Basic\AbstractFormRequest;
use App\Http\Requests\Definition\Yusei\ZipCodeDefinition;
use packages\domain\model\zipcode\ZipCodePostalCode;

class ZipCodeSearchReques extends AbstractFormRequest
{
    public function __construct(ZipCodeDefinition $definition = null)
    {
        parent::__construct($definition);
    }

    protected function transform(array $attrs): array
    {
        return $attrs;
    }

    public function toZipCode(): ZipCodePostalCode
    {
        return new ZipCodePostalCode($this->zipCode);
    }
}
