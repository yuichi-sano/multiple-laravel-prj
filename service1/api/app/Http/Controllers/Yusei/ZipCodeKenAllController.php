<?php

namespace App\Http\Controllers\Yusei;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\Yusei\ZipCodeGetResource;
use packages\service\jp\ZipCodeMigrationApplyInterface;

class ZipCodeKenAllController extends BaseController
{
    private ZipCodeMigrationApplyInterface $zipCodeMigrationApply;

    public function __construct(
        ZipCodeMigrationApplyInterface $zipCodeMigrationApply
    ) {
        $this->zipCodeMigrationApply = $zipCodeMigrationApply;
    }

    /**
     * ken_allのデータを取得し、前回更新時との差分を検査し、郵便番号データの差分をクライアントに返す。
     * @param mixed
     */
    public function index(): ZipCodeGetResource
    {
        $response = $this->zipCodeMigrationApply->getSource();

        return ZipCodeGetResource::buildResult($response);
    }
}
