<?php

namespace packages\service\jp;

use App\Extension\Support\Facades\TransactionManager;
use Exception;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\batch\MigrationBatchAuditDiffCnt;
use packages\domain\model\batch\MigrationBatchAuditList;
use packages\domain\model\batch\MigrationBatchAuditRecordCnt;
use packages\domain\model\batch\MigrationBatchAuditRepository;
use packages\domain\model\prefecture\Prefecture;
use packages\domain\model\prefecture\PrefectureList;
use packages\domain\model\prefecture\PrefectureRepository;
use packages\domain\model\user\UserId;
use packages\domain\model\zipcode\YuseiLargeBusinessYubinBangouList;
use packages\domain\model\zipcode\YuseiLargeBusinessYubinBangouRepository;
use packages\domain\model\zipcode\YuseiLargeBusinessZipCodeSourceRepository;
use packages\domain\model\zipcode\YuseiYubinBangouRepository;
use packages\domain\model\zipcode\YuseiZipDiffList;
use packages\domain\model\zipcode\ZipCodeList;
use packages\domain\model\zipcode\ZipCodeRepository;
use packages\domain\model\zipcode\ZipCodeSourceRepository;
use packages\domain\model\zipcode\ZipCodeMigrationSourceRepository;

/**
 * 郵政郵便番号更新処理に関するサービス登録
 */
class ZipCodeMigrationApplyService implements ZipCodeMigrationApplyInterface
{
    private ZipCodeSourceRepository $zipCodeSourceRepository;
    private YuseiLargeBusinessZipCodeSourceRepository $yuseiLargeBusinessZipCodeSourceRepository;
    private ZipCodeMigrationSourceRepository $zipCodeMigrationSourceRepository;
    private YuseiYubinBangouRepository $yubinBangouRepository;
    private YuseiLargeBusinessYubinBangouRepository $yuseiLargeBusinessYubinBangouRepository;
    private PrefectureRepository $prefectureRepository;
    private MigrationBatchAuditRepository $migrationBatchAuditRepository;

    public function __construct(
        ZipCodeSourceRepository $zipCodeSourceRepository,
        YuseiLargeBusinessZipCodeSourceRepository $yuseiLargeBusinessZipCodeSourceRepository,
        ZipCodeMigrationSourceRepository $zipCodeMigrationSourceRepository,
        YuseiYubinBangouRepository $yubinBangouRepository,
        YuseiLargeBusinessYubinBangouRepository $yuseiLargeBusinessYubinBangouRepository,
        MigrationBatchAuditRepository $migrationBatchAuditRepository,
        PrefectureRepository $prefectureRepository
    ) {
        $this->zipCodeSourceRepository = $zipCodeSourceRepository;
        $this->yuseiLargeBusinessZipCodeSourceRepository = $yuseiLargeBusinessZipCodeSourceRepository;
        $this->zipCodeMigrationSourceRepository = $zipCodeMigrationSourceRepository;
        $this->yubinBangouRepository = $yubinBangouRepository;
        $this->yuseiLargeBusinessYubinBangouRepository = $yuseiLargeBusinessYubinBangouRepository;
        $this->migrationBatchAuditRepository = $migrationBatchAuditRepository;
        $this->prefectureRepository = $prefectureRepository;
    }

    public function apply(
        UserId $userId,
        MigrationBatchAuditApplyDate $applyDate,
        bool $isDone = false
    ): MigrationBatchAuditList {
        $zipCodeSources = $this->zipCodeSourceRepository->get();
        $zipCodeList = ZipCodeList::createForCsv($zipCodeSources->toFile());
        $largeZipMasterSources = $this->yuseiLargeBusinessZipCodeSourceRepository->get();
        $yuseiLargeBusinessYubinBangouList = YuseiLargeBusinessYubinBangouList::createForCsv(
            $largeZipMasterSources->toFile()
        );

        $yuseiYubinBangouDiff = $this->zipCodeMigrationSourceRepository->yuseiYubinBangouDiff();
        $yuseiLargeBusinessYubinBangouDiff = $this->zipCodeMigrationSourceRepository->yuseiLargeBusinessYubinBangouDiff(
        );

        $migrationList = new MigrationBatchAuditList();

        TransactionManager::startTransaction();

        $migrationList->add(
            $this->yubinBangouRepository->createMigrationBatch(
                new MigrationBatchAuditRecordCnt($zipCodeList->count()),
                new MigrationBatchAuditDiffCnt($yuseiYubinBangouDiff->count()),
                $applyDate,
                $userId
            )
        );

        $migrationList->add(
            $this->yuseiLargeBusinessYubinBangouRepository->createMigrationBatch(
                new MigrationBatchAuditRecordCnt($yuseiLargeBusinessYubinBangouList->count()),
                new MigrationBatchAuditDiffCnt($yuseiLargeBusinessYubinBangouDiff->count()),
                $applyDate,
                $userId
            )
        );

        try {
            foreach ($migrationList as $migrationBatchAudit) {
                $this->migrationBatchAuditRepository->cancelMigration($migrationBatchAudit);
                if ($isDone) {
                    $this->migrationBatchAuditRepository->applyMigration($migrationBatchAudit->updateForDone());
                } else {
                    $this->migrationBatchAuditRepository->applyMigration($migrationBatchAudit);
                }
            }
        } catch (Exception $e) {
            TransactionManager::rollback();
        }
        TransactionManager::commit();

        return $migrationList;
    }

    /**
     * マスターデータを取得しSQL、差分情報を生成
     * @return array
     */
    public function getSource(): YuseiZipDiffList
    {
        $yuseiZipDiff = new YuseiZipDiffList();
        //郵便番号取得
        $masterSources = $this->zipCodeSourceRepository->get();
        $zipCodeList = ZipCodeList::createForCsv($masterSources->toFile());
        //大口郵便番号情報取得
        $largeZipMasterSources = $this->yuseiLargeBusinessZipCodeSourceRepository->get();
        $yuseiLargeBusinessYubinBangouList = YuseiLargeBusinessYubinBangouList::createForCsv(
            $largeZipMasterSources->toFile()
        );

        $this->zipCodeMigrationSourceRepository->yuseiYubinBangouPut($zipCodeList);
        $this->zipCodeMigrationSourceRepository->yuseiLargeBusinessYubinBangouPut($yuseiLargeBusinessYubinBangouList);

        $yuseiZipDiff->add($this->zipCodeMigrationSourceRepository->yuseiYubinBangouDiff());
        //FIXME  一旦事業所差分は無視させてください
        //$yuseiZipDiff->add($this->zipCodeMigrationSourceRepository->yuseiLargeBusinessYubinBangouDiff());

        return $yuseiZipDiff;
    }

    public function getPrefectureList(): PrefectureList
    {
        return $this->prefectureRepository->list();
    }
}
