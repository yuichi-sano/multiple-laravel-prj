<?php

namespace packages\service\facility;

use App\Exceptions\ErrorCodeConst;
use App\Exceptions\WebAPIException;
use App\Extension\Support\Facades\TransactionManager;
use packages\domain\model\facility\device\criteria\DeviceCriteria;
use packages\domain\model\facility\device\Device;
use packages\domain\model\facility\device\DeviceId;
use packages\domain\model\facility\device\DeviceList;
use packages\domain\model\facility\device\DeviceRepository;
use packages\domain\model\facility\device\HTDevice;
use packages\domain\model\facility\device\HTDeviceRepository;
use packages\domain\model\facility\device\HTDeviceType;
use packages\domain\model\facility\device\HTDeviceTypeList;
use packages\domain\model\user\UserId;

class DeviceGetService
{
    private DeviceRepository $deviceRepository;
    private HTDeviceRepository $HTDeviceRepository;

    public function __construct(
        DeviceRepository $deviceRepository,
        HTDeviceRepository $HTDeviceRepository,
    ) {
        $this->deviceRepository = $deviceRepository;
        $this->HTDeviceRepository = $HTDeviceRepository;
    }

    /**
     * 配送デバイス一覧取得
     * @return DeviceList
     */
    public function getDeviceList(DeviceCriteria $criteria): DeviceList
    {
        $deviceList = $this->deviceRepository->list($criteria);
        if ($deviceList->isEmpty()) {
            throw new WebAPIException(
                ErrorCodeConst::ERROR_204_NOCONTENT_DELIVERY_DEVICE,
                [],
                WebAPIException::HTTP_STATUS_NO_CONTENT
            );
        }
        return $deviceList;
    }

    /**
     * 配送デバイス一覧取得
     * @return DeviceList
     */
    public function getDeviceListCount(DeviceCriteria $criteria): int
    {
        return $this->deviceRepository->listCount($criteria);
    }

    /**
     * ハンディ端末個別追加
     * @return void
     */
    public function addHTDevice(HTDevice $HTDevice): HTDevice
    {
        return $this->hTDeviceRepository->add($HTDevice);
    }

    /**
     * @return HTDeviceTypeList
     */
    public function getHTDeviceTypeList(): HTDeviceTypeList
    {
        $htDeviceTypeList = new HTDeviceTypeList();
        foreach (HTDeviceType::HTDeviceTypeValues as $value) {
            $htDeviceTypeList->add(HTDeviceType::create($value));
        }
        return $htDeviceTypeList;
    }

    /**
     * 配送デバイス詳細取得
     * @return Device
     */
    public function getDeviceDetail(DeviceId $deviceId): Device
    {
        return $this->deviceRepository->detail($deviceId);
    }
}
