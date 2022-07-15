<?php

namespace packages\domain\model\zipcode;

use packages\domain\model\batch\MigrationBatchAudit;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\batch\MigrationBatchAuditCriteria;
use packages\domain\model\batch\MigrationBatchAuditDiffCnt;
use packages\domain\model\batch\MigrationBatchAuditRecordCnt;
use packages\domain\model\user\UserId;

interface ZipCodeRepository
{
    public function createMigrationBatch(
        MigrationBatchAuditRecordCnt $recordCnt,
        MigrationBatchAuditDiffCnt $diffCnt,
        MigrationBatchAuditApplyDate $applyDate,
        UserId $userId
    ): MigrationBatchAudit;

    public function createMigrationCriteria(
        int $status,
        string $applyDate = null,
        string $userId = null
    ): MigrationBatchAuditCriteria;

    public function migration();

    public function findAddress(ZipCodePostalCode $zipCode): ZipCodeList;

    public function findAddressById(ZipCodeId $id): ZipCode;

    public function add(ZipCode $zipCode): void;

    public function update(ZipCode $zipCode): void;

    public function delete(ZipCodeId $zipCodeId): void;
}
