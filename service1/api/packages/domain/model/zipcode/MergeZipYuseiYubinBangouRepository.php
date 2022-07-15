<?php

namespace packages\domain\model\zipcode;

use packages\domain\model\user\UserId;

interface MergeZipYuseiYubinBangouRepository
{
    public function findAddress(ZipCodePostalCode $zipCode): MergeZipYuseiYubinBangouList;

    public function findAddressById(ZipCodeId $zipCodeId): MergeZipYuseiYubinBangou;
}
