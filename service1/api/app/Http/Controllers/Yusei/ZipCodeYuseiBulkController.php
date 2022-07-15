<?php

namespace App\Http\Controllers\Yusei;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\Yusei\ZipCodeYuseiBulkResource;
use App\Http\Requests\Yusei\ZipCodeUpdateRequest;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\service\batch\YuseiMigrationService;
use packages\service\jp\ZipCodeMigrationApplyService;

class ZipCodeYuseiBulkController extends BaseController
{
    private ZipCodeMigrationApplyService $zipCodeMigrationApplyService;
    private YuseiMigrationService $yuseiMigrationService;

    public function __construct(
        ZipCodeMigrationApplyService $zipCodeMigrationApplyService,
        YuseiMigrationService $yuseiMigrationService
    ) {
        $this->zipCodeMigrationApplyService = $zipCodeMigrationApplyService;
        $this->yuseiMigrationService = $yuseiMigrationService;
    }

    /**
     * 郵政郵便番号更新
     * @param mixed
     */
    public function update(ZipCodeUpdateRequest $request): ZipCodeYuseiBulkResource
    {
        $applyDate = $request->toMigrationBatchAuditApplyDate();
        $userId = $request->toUserId();

        //即時実行の場合
        if ($applyDate->isEmpty()) {
            $date = date("Y-m-d H:i:s");
            $migrationApplyDate = MigrationBatchAuditApplyDate::create($date);
            $request = $this->zipCodeMigrationApplyService->apply($userId, $migrationApplyDate, true);
            $this->yuseiMigrationService->execute($request);

            return ZipCodeYuseiBulkResource::buildResult();
        }

        $this->zipCodeMigrationApplyService->apply($userId, $applyDate);
        return ZipCodeYuseiBulkResource::buildResult();
    }
}
