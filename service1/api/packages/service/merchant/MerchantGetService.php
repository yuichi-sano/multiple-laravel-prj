<?php

namespace packages\service\merchant;

use App\Exceptions\ErrorCodeConst;
use App\Exceptions\WebAPIException;
use App\Extension\Support\Facades\TransactionManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NoResultException;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use packages\domain\model\batch\MigrationBatchAudit;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\batch\MigrationBatchAuditCriteria;
use packages\domain\model\batch\MigrationBatchAuditDiffCnt;
use packages\domain\model\batch\MigrationBatchAuditRecordCnt;
use packages\domain\model\batch\MigrationBatchAuditRepository;
use packages\domain\model\merchant\Merchant;
use packages\domain\model\merchant\MerchantRepository;
use packages\domain\model\user\UserId;
use packages\domain\model\yamato\YamatoGyouseiCodeList;
use packages\domain\model\yamato\YamatoGyouseiCodeRepository;
use packages\domain\model\yamato\YamatoGyouseiCodeSource;
use packages\domain\model\yamato\YamatoMasterSourceRepository;
use packages\domain\model\yamato\YamatoMigrationSourceRepository;
use packages\domain\model\yamato\YamatoZipCodeList;
use packages\domain\model\yamato\YamatoZipCodeRepository;
use packages\domain\model\zipcode\ZipCodeFactory;
use packages\domain\model\zipcode\ZipCodeList;
use packages\domain\model\zipcode\ZipCodeSourceRepository;
use packages\service\helper\TransactionManagerInterface;

class MerchantGetService implements MerchantGetInterface
{
    private MerchantRepository $merchantRepository;
    private ZipCodeSourceRepository $zipCodeSourceRepository;
    private YamatoMasterSourceRepository $yamatoMasterSourceRepository;
    private YamatoMigrationSourceRepository $yamatoMigrationSourceRepository;
    private YamatoZipCodeRepository $yamatoZipCodeRepository;
    private YamatoGyouseiCodeRepository $yamatoGyouseiCodeRepository;
    private MigrationBatchAuditRepository $migrationBatchAuditRepository;

    public function __construct(
        MerchantRepository $merchantRepository,
        ZipCodeSourceRepository $zipCodeSourceRepository,
        YamatoMasterSourceRepository $yamatoMasterSourceRepository,
        YamatoMigrationSourceRepository $yamatoMigrationSourceRepository,
        YamatoZipCodeRepository $yamatoZipCodeRepository,
        YamatoGyouseiCodeRepository $yamatoGyouseiCodeRepository,
        MigrationBatchAuditRepository $migrationBatchAuditRepository
    ) {
        $this->merchantRepository = $merchantRepository;
        $this->zipCodeSourceRepository = $zipCodeSourceRepository;
        $this->yamatoMasterSourceRepository = $yamatoMasterSourceRepository;
        $this->yamatoMigrationSourceRepository = $yamatoMigrationSourceRepository;
        $this->yamatoZipCodeRepository = $yamatoZipCodeRepository;
        $this->yamatoGyouseiCodeRepository = $yamatoGyouseiCodeRepository;
        $this->migrationBatchAuditRepository = $migrationBatchAuditRepository;
    }

    public function execute(int $merchantId): MigrationBatchAudit
    {
        $userId = new UserId('140');
        $applyDate = MigrationBatchAuditApplyDate::create('2022-12-22 12:00:00');

        $yamatoMasterSources = $this->yamatoMasterSourceRepository->get();
        $yamatoZipCodeList = YamatoZipCodeList::createForDAT($yamatoMasterSources->getYamatoZipCodeSource()->toFile());
        $yamatoGyouseiCodeList = YamatoGyouseiCodeList::createForDAT(
            $yamatoMasterSources->getYamatoGyouseiCodeSource()->toFile()
        );

        $this->yamatoMigrationSourceRepository->yamatoZipPut($yamatoZipCodeList);
        $this->yamatoMigrationSourceRepository->yamatoGyouseiPut($yamatoGyouseiCodeList);
        $yamatoDiff = $this->yamatoMigrationSourceRepository->yamatoZipDiff();
        $gyouseiDiff = $this->yamatoMigrationSourceRepository->yamatoGyouseiDiff();

        TransactionManager::startTransaction();
        $yamatoZipMigration = $this->yamatoZipCodeRepository->createMigrationBatch(
            new MigrationBatchAuditRecordCnt($yamatoZipCodeList->count()),
            new MigrationBatchAuditDiffCnt(1),
            $applyDate,
            $userId
        );
        $yamatoGyouseiMigration = $this->yamatoGyouseiCodeRepository->createMigrationBatch(
            new MigrationBatchAuditRecordCnt($yamatoGyouseiCodeList->count()),
            new MigrationBatchAuditDiffCnt(1),
            $applyDate,
            $userId
        );
        try {
            $this->migrationBatchAuditRepository->cancelMigration($yamatoZipMigration);
            $this->migrationBatchAuditRepository->applyMigration($yamatoZipMigration);
            $this->migrationBatchAuditRepository->cancelMigration($yamatoGyouseiMigration);
            $this->migrationBatchAuditRepository->applyMigration($yamatoGyouseiMigration);
        } catch (ORMException $e) {
            TransactionManager::rollback();
            Log::error($e->getMessage());
            throw new WebAPIException(
                ErrorCodeConst::ERROR_404_NOTEXISTS_YAMATO_MASTER,
                [],
                WebAPIException::HTTP_STATUS_INTERNAL_SERVER_ERROR
            );
        }
        TransactionManager::commit();

        return $this->migrationBatchAuditRepository->findMigration(
            $this->yamatoZipCodeRepository->createMigrationCriteria(
                1
            )
        );
    }

    public function getList(): array
    {
        //$this->transaction->startTransaction('hoge');
        //$zipCodeSource = $this->zipCodeSourceRepository->get();
        //$file= $zipCodeSource->toFile();
        //$list = ZipCodeList::createForCsv($file);

        return $this->merchantRepository->list();
    }
}
