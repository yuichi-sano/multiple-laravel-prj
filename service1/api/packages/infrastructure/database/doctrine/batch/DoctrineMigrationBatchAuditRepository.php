<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\batch;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use packages\domain\model\batch\MigrationBatchAudit;
use packages\domain\model\batch\MigrationBatchAuditCriteria;
use packages\domain\model\batch\MigrationBatchAuditList;
use packages\domain\model\batch\MigrationBatchAuditRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;
use packages\infrastructure\database\sql\SqlBladeParams;

class DoctrineMigrationBatchAuditRepository extends DoctrineRepository implements MigrationBatchAuditRepository
{
    protected function getParentDir(): string
    {
        return parent_dir(dirname(__FILE__));
    }

    /**
     * バッチ登録要ドメインエンティティ作成
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findAllMigration(): MigrationBatchAuditList
    {
        $sql = $this->readNativeQueryFile('findAll');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());

        try {
            return new MigrationBatchAuditList($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * バッチ登録要ドメインエンティティ作成
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findMigration(MigrationBatchAuditCriteria $criteria): MigrationBatchAudit
    {
        $blade = new SqlBladeParams($criteria);
        $sql = $this->readNativeQueryFile('select', $blade->toFormat());
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * バッチ登録要ドメインエンティティ作成
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function migrationTarget(MigrationBatchAuditCriteria $criteria): MigrationBatchAudit
    {
        $sql = $this->readNativeQueryFile('migrationTarget');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters(['target_table_name' => $criteria->targetTableName]);
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * migrationバッチ登録
     * @param MigrationBatchAudit $migrationBatchAudit
     * @return void
     * @throws NoResultException
     * @throws BindingResolutionException
     */
    public function applyMigration(MigrationBatchAudit $migrationBatchAudit): void
    {
        $rsm = new ResultSetMapping();
        $sql = $this->readNativeQueryFile('applyMigration');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'target_table_name' => $migrationBatchAudit->getTargetTableName(),
            'record_cnt' => $migrationBatchAudit->getRecordCnt()->toInteger(),
            'before_record_cnt' => $migrationBatchAudit->getBeforeRecordCnt()->toInteger(),
            'diff_cnt' => $migrationBatchAudit->getDiffCnt()->toInteger(),
            'status' => $migrationBatchAudit->getStatus()->toInteger(),
            'apply_date' => $migrationBatchAudit->getApplyDate()->toLocalDateTime(),
            'user_id' => $migrationBatchAudit->getUserId()->toString()
        ]);

        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * migrationバッチキャンセル
     * @param MigrationBatchAudit $migrationBatchAudit
     * @return void
     * @throws NoResultException
     * @throws BindingResolutionException
     */
    public function cancelMigration(MigrationBatchAudit $migrationBatchAudit): void
    {
        $rsm = new ResultSetMapping();
        $sql = $this->readNativeQueryFile('cancelMigration');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'target_table_name' => $migrationBatchAudit->getTargetTableName(),
            'status' => $migrationBatchAudit->getStatus()->toInteger(),
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * migration実行完了
     * @param MigrationBatchAudit $migrationBatchAudit
     * @return void
     * @throws NoResultException
     * @throws BindingResolutionException
     */
    public function doneMigration(MigrationBatchAudit $migrationBatchAudit): void
    {
        $rsm = new ResultSetMapping();
        $sql = $this->readNativeQueryFile('doneMigration');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'target_table_name' => $migrationBatchAudit->getTargetTableName(),
            'status' => $migrationBatchAudit->getStatus()->toInteger(),
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * 直近の実績取得
     * @param string $tableName
     * @return MigrationBatchAudit
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws BindingResolutionException
     */
    public function latestAchievement(string $tableName): MigrationBatchAudit
    {
        $sql = $this->readNativeQueryFile('latestAchievement');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters([
            'target_table_name' => $tableName,
        ]);
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * migration実行完了
     * @param MigrationBatchAudit $migrationBatchAudit
     * @return void
     * @throws NoResultException
     * @throws BindingResolutionException
     */
    public function fail(MigrationBatchAudit $migrationBatchAudit): void
    {
        $rsm = new ResultSetMapping();
        $sql = $this->readNativeQueryFile('fail');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'target_table_name' => $migrationBatchAudit->getTargetTableName(),
            'status' => $migrationBatchAudit->getStatus()->toInteger(),
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
