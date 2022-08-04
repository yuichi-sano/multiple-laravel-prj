<?php

namespace packages\service\batch;

use App\Extension\Support\Facades\TransactionManager;
use Exception;
use packages\domain\model\batch\MigrationBatchAuditList;
use packages\domain\model\batch\MigrationBatchAuditRepository;
use packages\domain\model\batch\MigrationBatchAuditStatus;
use packages\domain\model\zipcode\YuseiLargeBusinessYubinBangouRepository;
use packages\domain\model\zipcode\YuseiYubinBangouRepository;


class YuseiMigrationService
{

    private YuseiYubinBangouRepository $yubinBangouRepository;
    private YuseiLargeBusinessYubinBangouRepository $yuseiLargeBusinessYubinBangouRepository;
    private MigrationBatchAuditRepository $migrationBatchAuditRepository;

    public function __construct(

        YuseiYubinBangouRepository $yubinBangouRepository,
        YuseiLargeBusinessYubinBangouRepository $yuseiLargeBusinessYubinBangouRepository,
        MigrationBatchAuditRepository $migrationBatchAuditRepository
    ) {

        $this->yubinBangouRepository = $yubinBangouRepository;
        $this->yuseiLargeBusinessYubinBangouRepository = $yuseiLargeBusinessYubinBangouRepository;
        $this->migrationBatchAuditRepository = $migrationBatchAuditRepository;
    }

    /**
     * $migrationList ['zips' => "\packages\domain\model\batch\MigrationBatchAudit"]
     * @param array $migrationList
     * @return void
     * @throws Exception
     */
    public function execute(MigrationBatchAuditList $migrationList): void
    {
        try {
            foreach ($migrationList as $migration) {
                TransactionManager::startTransaction();
                if ($migration->getTargetTableName() == 'yuseiyubinbangous') {
                    $this->yubinBangouRepository->migration();
                } else {
                    $this->yuseiLargeBusinessYubinBangouRepository->migration();
                }
                $this->migrationBatchAuditRepository->doneMigration($migration);
                TransactionManager::commit();
                TransactionManager::reConnect();
            }
        } catch (Exception $e) {
            TransactionManager::rollback();
            throw $e;
        }
    }

    public function getMigrationList(): MigrationBatchAuditList
    {
        $migrationList = new MigrationBatchAuditList();
        $migrationList->add(
            $this->migrationBatchAuditRepository->migrationTarget(
                $this->yubinBangouRepository->createMigrationCriteria(
                    MigrationBatchAuditStatus::getWaitingStatus()->toInteger()
                )
            )
        );
        $migrationList->add(
            $this->migrationBatchAuditRepository->migrationTarget(
                $this->yuseiLargeBusinessYubinBangouRepository->createMigrationCriteria(
                    MigrationBatchAuditStatus::getWaitingStatus()->toInteger()
                )
            )
        );


        return $migrationList;
    }

    /**
     * @param $migrationList ['zips' => "\packages\domain\model\batch\MigrationBatchAudit"]
     * @return void
     */
    public function fail(MigrationBatchAuditList $migrationList): void
    {
        TransactionManager::startTransaction();
        try {
            foreach ($migrationList as $migration) {
                $this->migrationBatchAuditRepository->fail($migration);
            }
        } catch (Exception $e) {
            TransactionManager::rollback();
            throw $e;
        }
        TransactionManager::commit();
    }
}
