<?php

namespace packages\service\jp;

use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\batch\MigrationBatchAuditList;
use packages\domain\model\prefecture\PrefectureList;
use packages\domain\model\user\UserId;
use packages\domain\model\zipcode\YuseiZipDiffList;

interface ZipCodeMigrationApplyInterface
{
    /**
     * @param UserId $userId
     * @param MigrationBatchAuditApplyDate $applyDate
     * @return mixed
     */
    public function apply(
        UserId $userId,
        MigrationBatchAuditApplyDate $applyDate,
        bool $isDone = false
    ): MigrationBatchAuditList;

    public function getSource(): YuseiZipDiffList;

    public function getPrefectureList(): PrefectureList;
}
