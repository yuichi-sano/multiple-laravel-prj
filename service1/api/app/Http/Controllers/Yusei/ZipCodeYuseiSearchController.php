<?php

namespace App\Http\Controllers\Yusei;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\Yusei\ZipCodeSearchResource;
use App\Http\Requests\Yusei\ZipCodeSearchReques;
use packages\domain\model\zipcode\ZipCodePostalCode;
use packages\service\jp\ZipCodeGetInterface;

class ZipCodeYuseiSearchController extends BaseController
{
    private ZipCodeGetInterface $zipCodeGet;

    public function __construct(
        ZipCodeGetInterface $zipCodeGet
    ) {
        $this->zipCodeGet = $zipCodeGet;
    }

    /**
     * 郵政郵便番号検索
     * @param mixed
     */
    public function index(ZipCodeSearchReques $request): ZipCodeSearchResource
    {
        $zipcode = $request->toZipCode();
        $response = $this->zipCodeGet->findAddress($zipcode);
        return ZipCodeSearchResource::buildResult($response);
    }
}
