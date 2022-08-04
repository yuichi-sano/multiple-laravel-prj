<?php

namespace packages\service\jp;

use packages\domain\model\batch\MigrationBatchAuditList;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangouList;
use packages\domain\model\zipcode\YuseiYubinBangouList;
use packages\domain\model\zipcode\ZipCode;
use packages\domain\model\zipcode\ZipCodeList;
use packages\domain\model\zipcode\ZipCodePostalCode;

interface ZipCodeGetInterface
{
    public function findAddress(ZipCodePostalCode $zipcode): MergeZipYuseiYubinBangouList;

    public function findLatestMigration(): MigrationBatchAuditList;

    public function findLatestUpdate(): YuseiYubinBangouList;
}
