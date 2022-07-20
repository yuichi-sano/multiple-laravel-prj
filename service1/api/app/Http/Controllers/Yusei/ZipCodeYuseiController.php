<?php

namespace App\Http\Controllers\Yusei;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\Yusei\ZipCodeRegistResource;
use App\Http\Resources\Yusei\ZipCodeUpdateResource;
use App\Http\Resources\Yusei\ZipCodeDeleteResource;
use App\Http\Requests\Yusei\ZipCodeIndividualRegisterRequest;
use Illuminate\Support\Facades\Auth;
use packages\domain\model\zipcode\YuseiYubinBangou;
use packages\domain\model\zipcode\ZipCode;
use packages\domain\model\zipcode\ZipCodeUserId;
use packages\service\jp\ZipCodeDeleteService;
use packages\service\jp\ZipCodeRegisterService;
use packages\service\jp\ZipCodeUpdateService;

class ZipCodeYuseiController extends BaseController
{
    private ZipCodeRegisterService $zipCodeRegisterService;
    private ZipCodeUpdateService $zipCodeUpdateService;
    private ZipCodeDeleteService $zipCodeDeleteService;

    public function __construct(
        ZipCodeRegisterService $zipCodeRegisterService,
        ZipCodeUpdateService $zipCodeUpdateService,
        ZipCodeDeleteService $zipCodeDeleteService,
    ) {
        $this->zipCodeRegisterService = $zipCodeRegisterService;
        $this->zipCodeUpdateService = $zipCodeUpdateService;
        $this->zipCodeDeleteService = $zipCodeDeleteService;
    }

    /**
     * 郵政郵便番号個別登録
     * @param mixed
     */
    public function store(ZipCodeIndividualRegisterRequest $request): ZipCodeRegistResource
    {
        $authedAccount = $request->getAuthedUser();
        $zipCode = $request->toZipCode();
        $zipCode->setUserId(new ZipCodeUserId($authedAccount->getUserId()->getValue()));
        $this->zipCodeRegisterService->execute($zipCode);
        return ZipCodeRegistResource::buildResult();
    }

    /**
     * 郵政郵便番号個別更新
     * @param mixed
     */
    public function update(int $id, ZipCodeIndividualRegisterRequest $request): ZipCodeUpdateResource
    {
        $mergeZipYusei = $request->toMergeZipYuseiYubinBangou($id);
        $this->zipCodeUpdateService->execute($mergeZipYusei);
        return ZipCodeUpdateResource::buildResult();
    }

    /**
     * 郵政郵便番号個別削除
     * @param mixed
     */
    public function destroy(int $id): ZipCodeDeleteResource
    {
        $this->zipCodeDeleteService->execute(new YuseiYubinBangou());
        return ZipCodeDeleteResource::buildResult();
    }
}
