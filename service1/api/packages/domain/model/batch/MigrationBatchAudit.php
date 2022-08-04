<?php

declare(strict_types=1);

namespace packages\domain\model\batch;

use DateTime;
use packages\domain\model\user\UserId;

class MigrationBatchAudit
{
    private MigrationBatchAuditId $id;
    private string $targetTableName;
    private MigrationBatchAuditRecordCnt $recordCnt;
    private MigrationBatchAuditBeforeRecordCnt $beforeRecordCnt;
    private MigrationBatchAuditDiffCnt $diffCnt;
    private MigrationBatchAuditStatus $status;
    private MigrationBatchAuditApplyDate $applyDate;
    private MigrationBatchAuditImplementationDate $implementationDate;
    private UserId $userId;
    private string $createDate;
    private string $auditDate;

    public function __construct(
        $targetTableName,
        $recordCnt,
        $beforeRecordCnt,
        $diffCnt,
        $status,
        $applyDate,
        $userId
    ) {
        $this->targetTableName = $targetTableName;
        $this->recordCnt = $recordCnt;
        $this->beforeRecordCnt = $beforeRecordCnt;
        $this->diffCnt = $diffCnt;
        $this->status = $status;
        $this->applyDate = $applyDate;
        $this->userId = $userId;
    }

    public function getTargetTableName(): string
    {
        return $this->targetTableName;
    }

    public function getRecordCnt(): MigrationBatchAuditRecordCnt
    {
        return $this->recordCnt;
    }

    public function getBeforeRecordCnt(): MigrationBatchAuditBeforeRecordCnt
    {
        return $this->beforeRecordCnt;
    }

    public function getDiffCnt(): MigrationBatchAuditDiffCnt
    {
        return $this->diffCnt;
    }

    public function getStatus(): MigrationBatchAuditStatus
    {
        return $this->status;
    }

    public function getApplyDate(): MigrationBatchAuditApplyDate
    {
        return $this->applyDate;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function updateForDone(): self
    {
        return new self(
            $this->targetTableName,
            $this->recordCnt,
            $this->beforeRecordCnt,
            $this->diffCnt,
            MigrationBatchAuditStatus::getDoneStatus(),
            $this->applyDate,
            $this->userId
        );
    }

    public array $collectionKeys = [
        'id'
    ];
}
