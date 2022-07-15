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
use packages\domain\model\zipcode\ZipCode;
use packages\domain\model\zipcode\ZipCodeId;
use packages\domain\model\zipcode\ZipCodeList;
use packages\domain\model\zipcode\ZipCodePostalCode;
use packages\domain\model\zipcode\ZipCodeRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineZipCodeRepository extends DoctrineRepository implements ZipCodeRepository
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
            $target = 'zips';
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
                ->from($this->getClassMetadata()->getName(), 'zips')
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
        $sql = $this->readNativeQueryFile('deleteZipAuditMigration');
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
        $sql = $this->readNativeQueryFile('countAllZip');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * 郵便番号住所検索
     * @param ZipCodePostalCode $zipCode
     * @return ZipCodeList
     * @throws BindingResolutionException
     * @throws NoResultException
     */
    public function findAddress(ZipCodePostalCode $zipCode): ZipCodeList
    {
        $sql = $this->readNativeQueryFile('findZip');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters([
            'zipCode' => $zipCode->getValue(),
        ]);
        try {
            return new ZipCodeList($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * 郵便番号住所検索
     * @param ZipCodeId $id
     * @return ZipCode
     * @throws BindingResolutionException
     * @throws NoResultException
     */
    public function findAddressById(ZipCodeId $id): ZipCode
    {
        $sql = $this->readNativeQueryFile('findZipById');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters([
            'id' => $id->getValue(),
        ]);
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * zipsレコード追加
     * @param ZipCode $zipCode
     * @return void
     * @throws BindingResolutionException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function add(ZipCode $zipCode): void
    {
        $sql = $this->readNativeQueryFile('insertZips');
        $query = $this->getEntityManager()->createNativeQuery($sql, new ResultSetMapping());
        $query->setParameters([
            'id' => $this->nextZipCodeId(),
            'postalCode' => $zipCode->getZipCode()->getValue(),
            'prefName' => $zipCode->getPrefecture(),
            'city' => $zipCode->getCity(),
            'town' => $zipCode->getTownArea(),
            'prefCode' => $zipCode->getPrefectureCode(),
            'UserId' => $zipCode->getUserId()->getValue()
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * zipsテーブルId値のnext値を取得
     * @CAUTION ロックしていません
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function nextZipCodeId(): int
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $sql = $this->readNativeQueryFile('nextZipCodeId');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * 郵政郵便番号個別更新
     * @param ZipCode $zipCode
     * @throws BindingResolutionException
     * @throws NoResultException
     */
    public function update(ZipCode $zipCode): void
    {
        $sql = $this->readNativeQueryFile('updateZips');
        $query = $this->getEntityManager()->createNativeQuery($sql, new ResultSetMapping());
        $query->setParameters([
            'id' => $zipCode->getId()->toInteger(),
            'postalCode' => $zipCode->getZipCode()->getValue(),
            'prefName' => $zipCode->getPrefecture(),
            'city' => $zipCode->getCity(),
            'town' => $zipCode->getTownArea(),
            'prefCode' => $zipCode->getPrefectureCode(),
            'UserId' => $zipCode->getUserId()
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * 郵便番号住所検索
     * @param ZipCodeId $id
     * @return ZipCode
     * @throws BindingResolutionException
     * @throws NoResultException
     */
    public function delete(ZipCodeId $id): void
    {
        $sql = $this->readNativeQueryFile('deleteZips');
        $query = $this->getEntityManager()->createNativeQuery($sql, new ResultSetMapping());
        $query->setParameters([
            'id' => $id->getValue(),
        ]);
        try {
            $query->execute();
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
