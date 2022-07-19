<?php

namespace App\Http\Controllers\Device;

use App\Http\Resources\Workplace\WorkplaceResource;
use Illuminate\Routing\Controller as BaseController;
use packages\service\facility\FacilityGetService;

class WorkplaceController extends BaseController
{
    private FacilityGetService $facilityGetService;

    public function __construct(
        FacilityGetService $facilityGetService,
    ) {
        $this->facilityGetService = $facilityGetService;
    }

    /**
     * 端末情報取得
     * @param mixed
     */
    public function index(): WorkplaceResource
    {
        $response = $this->facilityGetService->getWorkplaceList();
        return WorkplaceResource::buildResult($response);
    }

}
