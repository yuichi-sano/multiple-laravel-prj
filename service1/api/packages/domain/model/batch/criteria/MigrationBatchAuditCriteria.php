<?php

declare(strict_types=1);

namespace packages\domain\model\batch;

use packages\domain\model\user\UserId;

class MigrationBatchAuditCriteria
{
    public string $targetTableName;
    public MigrationBatchAuditStatus $status;
    public MigrationBatchAuditApplyDate $applyDate;
    public UserId $userId;

    private function __construct(
        string $targetTableName,
        MigrationBatchAuditStatus $status,
        MigrationBatchAuditApplyDate $applyDate,
        UserId $userId
    ) {
        $this->targetTableName = $targetTableName;
        $this->status = $status;
        $this->applyDate = $applyDate;
        $this->userId = $userId;
    }

    public static function create(
        string $targetTableName,
        int $status,
        string $applyDate = null,
        string $userId = null
    ): MigrationBatchAuditCriteria {
        return new MigrationBatchAuditCriteria(
            $targetTableName,
            new MigrationBatchAuditStatus($status),
            MigrationBatchAuditApplyDate::create($applyDate),
            new UserId($userId)
        );
    }
}
