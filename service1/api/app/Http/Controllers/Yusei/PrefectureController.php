<?php

namespace App\Http\Controllers\Yusei;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\Yusei\PrefectureResource;
use packages\service\jp\ZipCodeMigrationApplyService;

class PrefectureController extends BaseController
{
    private ZipCodeMigrationApplyService $zipCodeMigrationApplyService;

    public function __construct(ZipCodeMigrationApplyService $zipCodeMigrationApplyService)
    {
        $this->zipCodeMigrationApplyService = $zipCodeMigrationApplyService;
    }

    /**
     * 都道府県データ
     * @param mixed
     */
    public function index(): PrefectureResource
    {
        $response = $this->zipCodeMigrationApplyService->getPrefectureList();
        return PrefectureResource::buildResult($response);
    }
}
