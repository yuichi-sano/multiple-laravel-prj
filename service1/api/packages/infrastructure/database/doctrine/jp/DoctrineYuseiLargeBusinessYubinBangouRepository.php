<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\jp;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Storage;
use packages\domain\model\batch\MigrationBatchAuditCriteria;
use packages\domain\model\user\UserId;
use packages\domain\model\batch\MigrationBatchAuditBeforeRecordCnt;
use packages\domain\model\batch\MigrationBatchAuditDiffCnt;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\batch\MigrationBatchAudit;
use packages\domain\model\batch\MigrationBatchAuditStatus;
use packages\domain\model\batch\MigrationBatchAuditRecordCnt;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangou;
use packages\domain\model\zipcode\YuseiLargeBusinessYubinBangouList;
use packages\domain\model\zipcode\YuseiLargeBusinessYubinBangouRepository;
use packages\domain\model\zipcode\YuseiYubinBangou;
use packages\domain\model\zipcode\YuseiYubinBangouList;
use packages\domain\model\zipcode\YuseiYubinBangouRepository;
use packages\domain\model\zipcode\ZipCode;
use packages\domain\model\zipcode\ZipCodePostalCode;
use packages\domain\model\zipcode\ZipCodeRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineYuseiLargeBusinessYubinBangouRepository extends DoctrineRepository implements
    YuseiLargeBusinessYubinBangouRepository
{
    protected function getParentDir(): string
    {
        return parent_dir(dirname(__FILE__));
    }

    /**
     * マイグレーションバッチ要ドメイン生成
     * @return MigrationBatchAudit
     * @throws \Exception
     */
    public function createMigrationBatch(
        MigrationBatchAuditRecordCnt $recordCnt,
        MigrationBatchAuditDiffCnt $diffCnt,
        MigrationBatchAuditApplyDate $applyDate,
        UserId $userId
    ): MigrationBatchAudit {
        try {
            $beforeCnt = $this->countAll();
        } catch (NoResultException|NonUniqueResultException $e) {
            throw new \Exception($e->getMessage());
        }
        return new MigrationBatchAudit(
            $this->getClassMetadata()->getTableName(),
            $recordCnt,
            new MigrationBatchAuditBeforeRecordCnt($beforeCnt),
            $diffCnt,
            MigrationBatchAuditStatus::getWaitingStatus(),
            $applyDate,
            $userId
        );
    }

    /**
     * マイグレーションバッチ登録状況確認
     * @param int $status
     * @param string|null $applyDate
     * @param string|null $userId
     * @return MigrationBatchAuditCriteria
     */
    public function createMigrationCriteria(
        int $status,
        string $applyDate = null,
        string $userId = null
    ): MigrationBatchAuditCriteria {
        return MigrationBatchAuditCriteria::create(
            $this->getClassMetadata()->getTableName(),
            $status,
            $applyDate,
            $userId
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function migration()
    {
        try {
            $target = 'yuseiooguchijigyoushoyubinbangous';
            $list = Storage::disk('data_migrations')->files('jp/' . $target . '/');
            $sqlList = [];
            foreach ($list as $item) {
                $itemPath = pathinfo($item);
                $migrationName = '/jp/' . $target . '/' . $itemPath["filename"];
                $sqlList[] = $this->readMigrationFile($migrationName);
            }
        } catch (BindingResolutionException $e) {
            throw $e;
            //TODO Exception
        }
        $this->allDelete();
        try {
            foreach ($sqlList as $sql) {
                $this->getEntityManager()->getConnection()->executeStatement($sql);
                $this->getEntityManager()->clear();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws NoResultException
     */
    private function allDelete()
    {
        try {
            $this->getEntityManager()->createQueryBuilder()->delete()
                ->from($this->getClassMetadata()->getName(), 'yuseiooguchijigyoushoyubinbangous')
                ->where("1=1")
                ->getQuery()
                ->execute();
            $this->deleteAudit();
            $this->getEntityManager()->clear();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws BindingResolutionException
     * @throws NoResultException
     */
    private function deleteAudit()
    {
        return;
        $rsm = new ResultSetMapping();
        $sql = $this->readNativeQueryFile('deleteYuseiLargeBusinessAuditMigration');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        try {
            return $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * 総件数取得
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function countAll(): int
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count', 'count');
        $sql = $this->readNativeQueryFile('countAllLargeBusiness');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function findAddress(ZipCodePostalCode $zipCode): YuseiLargeBusinessYubinBangouList
    {
        $sql = $this->readNativeQueryFile('findYuseiLargeBusiness');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters([
            'zipCode' => $zipCode->getValue(),
        ]);

        try {
            return new YuseiLargeBusinessYubinBangouList($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * @deplicated
     * @return YuseiLargeBusinessYubinBangouList
     * @throws BindingResolutionException
     * @throws NoResultException
     */
    public function findLatestUpdate(): YuseiLargeBusinessYubinBangouList
    {
        $sql = $this->readNativeQueryFile('latestUpdateLargeBusiness');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        try {
            return new YuseiLargeBusinessYubinBangouList($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
