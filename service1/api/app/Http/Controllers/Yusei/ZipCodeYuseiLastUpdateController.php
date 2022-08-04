<?php

namespace App\Http\Controllers\Yusei;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\Yusei\YuseiLastUpdateResource;
use packages\service\jp\ZipCodeGetInterface;

class ZipCodeYuseiLastUpdateController extends BaseController
{
    private ZipCodeGetInterface $zipCodeGet;

    public function __construct(
        ZipCodeGetInterface $zipCodeGet
    ) {
        $this->zipCodeGet = $zipCodeGet;
    }

    /**
     * 郵政郵便番号前回更新情報取得
     * @param mixed
     */
    public function index(): YuseiLastUpdateResource
    {
        $latestMigration = $this->zipCodeGet->findLatestMigration();
        $latestUpdate = $this->zipCodeGet->findLatestUpdate();
        return YuseiLastUpdateResource::buildResult($latestMigration, $latestUpdate);
    }
}
