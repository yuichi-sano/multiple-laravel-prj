<?php

namespace packages\service\jp;

use App\Exceptions\ErrorCodeConst;
use App\Exceptions\WebAPIException;
use App\Extension\Support\Facades\TransactionManager;
use Doctrine\ORM\NoResultException;
use Illuminate\Support\Facades\Log;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\batch\MigrationBatchAuditDiffCnt;
use packages\domain\model\batch\MigrationBatchAuditList;
use packages\domain\model\batch\MigrationBatchAuditRecordCnt;
use packages\domain\model\batch\MigrationBatchAuditRepository;
use packages\domain\model\batch\MigrationBatchAuditStatus;
use packages\domain\model\prefecture\Prefecture;
use packages\domain\model\prefecture\PrefectureList;
use packages\domain\model\prefecture\PrefectureRepository;
use packages\domain\model\user\UserId;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangouList;
use packages\domain\model\zipcode\MergeZipYuseiYubinBangouRepository;
use packages\domain\model\zipcode\YuseiYubinBangou;
use packages\domain\model\zipcode\YuseiYubinBangouList;
use packages\domain\model\zipcode\YuseiYubinBangouRepository;
use packages\domain\model\zipcode\ZipCode;
use packages\domain\model\zipcode\ZipCodeList;
use packages\domain\model\zipcode\ZipCodePostalCode;
use packages\domain\model\zipcode\ZipCodeRepository;
use packages\domain\model\zipcode\ZipCodeSourceRepository;
use packages\domain\model\zipcode\ZipCodeMigrationSourceRepository;

/**
 * 郵政郵便番号更新処理に関するサービス登録
 */
class ZipCodeGetService implements ZipCodeGetInterface
{
    private ZipCodeRepository $zipCodeRepository;
    private YuseiYubinBangouRepository $yubinBangouRepository;
    private MigrationBatchAuditRepository $migrationBatchAuditRepository;
    private MergeZipYuseiYubinBangouRepository $mergeZipYuseiYubinBangouRepository;

    public function __construct(
        ZipCodeRepository $zipCodeRepository,
        YuseiYubinBangouRepository $yubinBangouRepository,
        MigrationBatchAuditRepository $migrationBatchAuditRepository,
        MergeZipYuseiYubinBangouRepository $mergeZipYuseiYubinBangouRepository,
    ) {
        $this->zipCodeRepository = $zipCodeRepository;
        $this->yubinBangouRepository = $yubinBangouRepository;
        $this->migrationBatchAuditRepository = $migrationBatchAuditRepository;
        $this->mergeZipYuseiYubinBangouRepository = $mergeZipYuseiYubinBangouRepository;
    }

    public function findAddress(ZipCodePostalCode $zipcode): MergeZipYuseiYubinBangouList
    {
        //return $this->yubinBangouRepository->findAddress($zipcode);
        //return $this->zipCodeRepository->findAddress($zipcode);
        $mergeZipYuseiYubinBangouList = $this->mergeZipYuseiYubinBangouRepository->findAddress($zipcode);
        if ($mergeZipYuseiYubinBangouList->isEmpty()) {
            throw new WebAPIException(
                ErrorCodeConst::ERROR_204_NOCONTENT_ZIP,
                [],
                WebAPIException::HTTP_STATUS_NO_CONTENT
            );
        }
        return $mergeZipYuseiYubinBangouList;
    }

    public function findLatestMigration(): MigrationBatchAuditList
    {
        $latestList = new MigrationBatchAuditList();
        $zipcode = $this->zipCodeRepository->createMigrationCriteria(
            MigrationBatchAuditStatus::getDoneStatus()->toInteger()
        );
        $yusei = $this->yubinBangouRepository->createMigrationCriteria(
            MigrationBatchAuditStatus::getDoneStatus()->toInteger()
        );
        try {
            $latestList->add($this->migrationBatchAuditRepository->latestAchievement($zipcode->targetTableName));
            $latestList->add($this->migrationBatchAuditRepository->latestAchievement($yusei->targetTableName));
        } catch (NoResultException $e) {
            throw new WebAPIException('W_000000000');
        }
        return $latestList;
    }

    public function findLatestUpdate(): YuseiYubinBangouList
    {
        return $this->yubinBangouRepository->findLatestUpdate();
    }
}
