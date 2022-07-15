<?php

namespace App\Http\Controllers\Device;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\Device\DeviceInfoResource;
use App\Http\Resources\Device\DeviceRegistResource;
use App\Http\Resources\Device\DeviceUpdateResource;
use App\Http\Requests\Device\DevicGetRequest;
use App\Http\Requests\Device\DeviceRegisterRequest;
use App\Http\Requests\Device\DeviceUpdateRequest;
use App\Http\Resources\Device\DeviceDetailResource;
use packages\domain\model\facility\device\DeviceId;
use packages\service\facility\DeviceAddService;
use packages\service\facility\DeviceGetService;
use packages\service\facility\FacilityGetService;

class DeviceController extends BaseController
{
    private FacilityGetService $facilityGetService;
    private DeviceGetService $deviceGetService;
    private DeviceAddService $deviceAddService;

    public function __construct(
        FacilityGetService $facilityGetService,
        DeviceGetService $deviceGetService,
        DeviceAddService $deviceAddService
    ) {
        $this->facilityGetService = $facilityGetService;
        $this->deviceGetService = $deviceGetService;
        $this->deviceAddService = $deviceAddService;
    }

    /**
     * 端末情報取得
     * @param mixed
     */
    public function index(DevicGetRequest $request): DeviceInfoResource
    {
        $response = $this->deviceGetService->getDeviceList($request->toDeviceCriteria());
        $total = $this->deviceGetService->getDeviceListCount($request->toDeviceCriteria());
        return DeviceInfoResource::buildResult($response, $total, $request->toDeviceCriteria()->pageable);
    }

    /**
     * 端末情報登録
     * @param mixed
     */
    public function store(DeviceRegisterRequest $request): DeviceRegistResource
    {
        $device = $request->toDevice();
        $this->deviceAddService->checkDuplicateForRegister($device);
        $this->deviceAddService->addDevice($device);
        return DeviceRegistResource::buildResult();
    }

    /**
     * 端末情報更新
     * @param mixed
     */
    public function update(int $id, DeviceUpdateRequest $request): DeviceUpdateResource
    {
        $authedAccount = $request->getAuthedUser();
        $device = $request->toDevice($id);
        $this->deviceAddService->checkDuplicateForUpdate($device);
        $this->deviceAddService->updateDevice($device);
        return DeviceUpdateResource::buildResult();
    }

    /**
     * 端末詳細情報取得
     * @param mixed
     */
    public function detail(int $id): DeviceInfoResource
    {
        $response = $this->deviceGetService->getDeviceDetail(new DeviceId($id));
        return DeviceDetailResource::buildResult($response);
    }
}
