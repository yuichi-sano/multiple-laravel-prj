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
class ZipCodeDeleteService
{
    private YuseiYubinBangouRepository $yubinBangouRepository;
    private MergeZipYuseiYubinBangouRepository $mergeZipYuseiYubinBangouRepository;

    public function __construct(
        YuseiYubinBangouRepository $yubinBangouRepository,
        MergeZipYuseiYubinBangouRepository $mergeZipYuseiYubinBangouRepository
    ) {
        $this->yubinBangouRepository = $yubinBangouRepository;
        $this->mergeZipYuseiYubinBangouRepository = $mergeZipYuseiYubinBangouRepository;
    }

    /**
     * ZipCode削除
     * @param ZipCodePostalCode $zipcode
     * @return void
     * @throws WebAPIException
     */
    public function execute(ZipCodePostalCode $zipcode)
    {
        TransactionManager::startTransaction();
        $mergeZipYusei = $this->mergeZipYuseiYubinBangouRepository->findAddressById($zipcode);
        try {
            $this->yubinBangouRepository->delete($mergeZipYusei);
        } catch (Exception $e) {
            TransactionManager::rollback();
            Log::erroe($e->getMessage());
            throw new WebAPIException(
                ErrorCodeConst::ERROR_500_DELETE,
                [],
                WebAPIException::HTTP_STATUS_INTERNAL_SERVER_ERROR
            );
        }
        TransactionManager::commit();
    }
}
