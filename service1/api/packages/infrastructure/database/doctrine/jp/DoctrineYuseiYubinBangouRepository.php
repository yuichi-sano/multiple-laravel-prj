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
use packages\domain\model\zipcode\YuseiYubinBangou;
use packages\domain\model\zipcode\YuseiYubinBangouList;
use packages\domain\model\zipcode\YuseiYubinBangouRepository;
use packages\domain\model\zipcode\ZipCode;
use packages\domain\model\zipcode\ZipCodePostalCode;
use packages\domain\model\zipcode\ZipCodeRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineYuseiYubinBangouRepository extends DoctrineRepository implements YuseiYubinBangouRepository
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
            $target = 'yuseiyubinbangous';
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
                ->from($this->getClassMetadata()->getName(), 'yuseiyubinbangous')
                ->where("1=1")
                ->getQuery()
                ->execute();
            $this->deleteAudit();
            $this->getEntityManager()->clear();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    private function deleteAudit()
    {
        $rsm = new ResultSetMapping();
        $sql = $this->readNativeQueryFile('deleteYuseiAuditMigration');
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
        $sql = $this->readNativeQueryFile('countAllYusei');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function findAddress(ZipCodePostalCode $zipCode): YuseiYubinBangouList
    {
        $sql = $this->readNativeQueryFile('findYubinbangou');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters([
            'zipCode' => $zipCode->getValue(),
        ]);

        try {
            return new YuseiYubinBangouList($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function findLatestUpdate(): YuseiYubinBangouList
    {
        $sql = $this->readNativeQueryFile('latestUpdate');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        try {
            return new YuseiYubinBangouList($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function add(ZipCode $zipCode): void
    {
        $sql = $this->readNativeQueryFile('insertYusei');
        $query = $this->getEntityManager()->createNativeQuery($sql, new ResultSetMapping());
        $query->setParameters([
            'jis' => $zipCode->getJis()->getValue(),
            'zipCodeOld' => $zipCode->getZipCode()->toMostOldStr(),
            'zipCode' => $zipCode->getZipCode()->getValue(),
            'prefNameKana' => $zipCode->getPrefectureKana(),
            'cityNameKana' => $zipCode->getCityKana(),
            'townNameKana' => $zipCode->getTownAreaKana(),
            'prefName' => $zipCode->getPrefecture(),
            'cityName' => $zipCode->getCity(),
            'townName' => $zipCode->getTownArea(),
            'isOneTownByMultiZipCode' => $zipCode->getIsOneTownByMultiZipCode(),
            'isNeedSmallAreaAddress' => $zipCode->getIsNeedSmallAreaAddress(),
            'isChoume' => $zipCode->getIsChoume(),
            'isMultiTownByOnePostCode' => $zipCode->getIsMultiTownByOnePostCode(),
            'updated' => $zipCode->getUpdated(),
            'updateReason' => $zipCode->getUpdateReason(),
            'UserId' => $zipCode->getUserId()->getValue()
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * 郵政郵便番号個別更新
     * ※更新キー用の更新前のzipsレコードの引き渡しが必須
     * @param ZipCode $zipCode
     * @param ZipCode $lastUpdateZipCode 更新キー用の更新前のzipsレコード
     * @throws BindingResolutionException
     * @throws NoResultException
     */
    public function update(ZipCode $zipCode, ZipCode $lastUpdateZipCode): void
    {
        $sql = $this->readNativeQueryFile('updateYusei');
        $query = $this->getEntityManager()->createNativeQuery($sql, new ResultSetMapping());
        $query->setParameters([
            'jis' => $zipCode->getJis()->getValue(),
            'zipCodeOld' => $zipCode->getZipCode()->toMostOldStr(),
            'zipCode' => $zipCode->getZipCode()->getValue(),
            'prefNameKana' => $zipCode->getPrefectureKana(),
            'cityNameKana' => $zipCode->getCityKana(),
            'townNameKana' => $zipCode->getTownAreaKana(),
            'prefName' => $zipCode->getPrefecture(),
            'cityName' => $zipCode->getCity(),
            'townName' => $zipCode->getTownArea(),
            'UserId' => $zipCode->getUserId(),
            'lastUpdateJis' => $lastUpdateZipCode->getJis(),
            'lastUpdateZipCode' => $lastUpdateZipCode->getZipCode(),
            'lastUpdatePrefName' => $lastUpdateZipCode->getPrefecture(),
            'lastUpdateCityName' => $lastUpdateZipCode->getCity(),
            'lastUpdateTownName' => $lastUpdateZipCode->getTownArea()
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * @param ZipCode $zipCode
     * @return void
     */
    public function delete(MergeZipYuseiYubinBangou $zipCode): void
    {
        $sql = $this->readNativeQueryFile('deleteYusei');
        $query = $this->getEntityManager()->createNativeQuery($sql, new ResultSetMapping());
        $query->setParameters([
            'zipCode' => $zipCode->getZipCode()->getValue(),
            'jis' => $zipCode->getJis()->getValue(),
            'prefName' => $zipCode->getPrefecture(),
            'cityName' => $zipCode->getCity(),
            'townName' => $zipCode->getTownArea(),
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
