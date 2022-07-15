<?php

namespace packages\service\jp;

use App\Exceptions\ErrorCodeConst;
use App\Exceptions\WebAPIException;
use App\Extension\Support\Facades\TransactionManager;
use Exception;
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
use packages\domain\model\zipcode\MergeZipYuseiYubinBangou;
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
class ZipCodeUpdateService
{
    private ZipCodeRepository $zipCodeRepository;
    private YuseiYubinBangouRepository $yubinBangouRepository;
    private MergeZipYuseiYubinBangouRepository $mergeZipYuseiYubinBangou;

    public function __construct(
        ZipCodeRepository $zipCodeRepository,
        YuseiYubinBangouRepository $yubinBangouRepository,
        MergeZipYuseiYubinBangouRepository $mergeZipYuseiYubinBangou,
    ) {
        $this->zipCodeRepository = $zipCodeRepository;
        $this->yubinBangouRepository = $yubinBangouRepository;
        $this->mergeZipYuseiYubinBangou = $mergeZipYuseiYubinBangou;
    }

    /**
     * ZipCode,YuseiYubinbangou更新
     * @param MergeZipYuseiYubinBangou $zipcode
     * @return void
     * @throws Exception
     */
    public function execute(MergeZipYuseiYubinBangou $zipcode)
    {
        TransactionManager::startTransaction();
        $before = $this->mergeZipYuseiYubinBangou->findAddressById($zipcode->getId());
        try {
            $this->zipCodeRepository->update($zipcode->transZipCode());
            $this->yubinBangouRepository->update($zipcode->transZipCode(), $before->transZipCode());
        } catch (Exception $e) {
            TransactionManager::rollback();
            throw  $e;
            Log::error($e->getMessage());
            throw new WebAPIException(
                ErrorCodeConst::ERROR_500_UPDATE,
                [],
                WebAPIException::HTTP_STATUS_INTERNAL_SERVER_ERROR
            );
        }
        TransactionManager::commit();
    }
}
