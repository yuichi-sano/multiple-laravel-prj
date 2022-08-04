<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\jp;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use packages\domain\model\batch\MigrationBatchAuditCriteria;
use packages\domain\model\user\UserId;
use packages\domain\model\batch\MigrationBatchAuditBeforeRecordCnt;
use packages\domain\model\batch\MigrationBatchAuditDiffCnt;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\batch\MigrationBatchAudit;
use packages\domain\model\batch\MigrationBatchAuditStatus;
use packages\domain\model\batch\MigrationBatchAuditRecordCnt;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangou;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangouList;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangouRepository;
use packages\domain\model\zipcode\YuseiYubinBangou;
use packages\domain\model\zipcode\YuseiYubinBangouList;
use packages\domain\model\zipcode\YuseiYubinBangouRepository;
use packages\domain\model\zipcode\ZipCode;
use packages\domain\model\zipcode\ZipCodePostalCode;
use packages\domain\model\zipcode\ZipCodeRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;

class DoctrineMergeZipYuseiYubinBangouRepository extends DoctrineRepository implements
    MergeZipYuseiYubinBangouRepository
{
    protected function getParentDir(): string
    {
        return parent_dir(dirname(__FILE__));
    }

    public function findAddress(ZipCodePostalCode $zipCode): MergeZipYuseiYubinBangouList
    {
        $exactSql = $this->readNativeQueryFile('findExactMatchZipYubinbangou');
        $exactQuery = $this->getEntityManager()->createNativeQuery($exactSql, $this->getDefaultRSM());
        $exactQuery->setParameters([
            'zipCode' => $zipCode->getValue(),
        ]);

        $sql = $this->readNativeQueryFile('findPartialMatchZipYubinbangou');
        $query = $this->getEntityManager()->createNativeQuery($sql, $this->getDefaultRSM());
        $query->setParameters([
            'zipCode' => $zipCode->getValue(),
        ]);

        try {
            if ($exactQuery->getResult()) {
                $result = $exactQuery->getResult();
            } else {
                $result = $query->getResult();
            }
            return new MergeZipYuseiYubinBangouList($result);
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

}
