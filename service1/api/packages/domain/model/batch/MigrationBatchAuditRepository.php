<?php

namespace packages\domain\model\batch;

interface MigrationBatchAuditRepository
{
    public function findAllMigration(): MigrationBatchAuditList;

    public function findMigration(MigrationBatchAuditCriteria $criteria): MigrationBatchAudit;

    public function migrationTarget(MigrationBatchAuditCriteria $criteria): MigrationBatchAudit;

    public function applyMigration(MigrationBatchAudit $migration): void;

    public function cancelMigration(MigrationBatchAudit $migration): void;

    public function doneMigration(MigrationBatchAudit $migration): void;

    public function fail(MigrationBatchAudit $migration): void;

    public function latestAchievement(string $tableName): MigrationBatchAudit;
}
