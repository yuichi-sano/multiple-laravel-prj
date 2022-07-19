<?php

namespace packages\service\facility;

use App\Exceptions\ErrorCodeConst;
use App\Exceptions\WebAPIException;
use App\Extension\Support\Facades\TransactionManager;
use Doctrine\ORM\Exception\ORMException;
use Illuminate\Support\Facades\Log;
use packages\domain\model\facility\device\Device;
use packages\domain\model\facility\device\DeviceList;
use packages\domain\model\facility\device\DeviceRepository;
use packages\domain\model\user\UserId;

class DeviceAddService
{
    private DeviceRepository $deviceRepository;

    public function __construct(
        DeviceRepository $deviceRepository,
    ) {
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * 配送デバイス、ハンディ端末登録
     * @return void
     * @throws WebAPIException
     */
    public function addDevice(Device $device): void
    {
        TransactionManager::startTransaction();
        try {
            $deviceId = $this->deviceRepository->add($device);
        } catch (ORMException $e) {
            TransactionManager::rollback();
            Log::error($e->getMessage());
            throw new WebAPIException(
                ErrorCodeConst::ERROR_500_REGISTER,
                [],
                WebAPIException::HTTP_STATUS_INTERNAL_SERVER_ERROR
            );
        }
        TransactionManager::commit();
    }

    /**
     * 配送デバイス、ハンディ端末更新
     * @return void
     * @throws WebAPIException
     */
    public function updateDevice(Device $device): void
    {
        TransactionManager::startTransaction();
        try {
            $this->deviceRepository->update($device);
        } catch (ORMException $e) {
            TransactionManager::rollback();
            Log::error($e->getMessage());
            throw new WebAPIException(
                ErrorCodeConst::ERROR_500_UPDATE,
                [],
                WebAPIException::HTTP_STATUS_INTERNAL_SERVER_ERROR
            );
        }
        TransactionManager::commit();
    }

    /**
     * IPアドレスの重複チェック
     * ＠FIXME ロック処理など設けていなく、楽観的なチェックのみとなっています。べき論でいうとロックをかける必要があるはず
     * @param Device $device
     * @throws WebAPIException
     */
    public function checkDuplicateForRegister(Device $device): void
    {
        $ipAddressList = [];
        $ipAddressList[] = $device->getDeviceIpAddress()->getValue();
        if ($this->deviceRepository->isDuplicate($device->getDeviceIpAddress())) {
            throw new WebAPIException(ErrorCodeConst::ERROR_400_DUPLICATE_HOSR_NAME_OR_IP_ADDRESS, [], 400);
        }

        if (max(array_count_values($ipAddressList)) > 1) {
            throw new WebAPIException(ErrorCodeConst::ERROR_400_DUPLICATE_HOSR_NAME_OR_IP_ADDRESS, [], 400);
        }
    }

    /**
     * IPアドレスの重複チェック
     * ＠FIXME ロック処理など設けていなく、楽観的なチェックのみとなっています。べき論でいうとロックをかける必要があるはず
     * @param Device $device
     * @throws WebAPIException
     */
    public function checkDuplicateForUpdate(Device $device): void
    {
        $ipAddressList = [];
        $ipAddressList[] = $device->getDeviceIpAddress()->getValue();
        if (
            !$this->deviceRepository->existIpAddress(
                $device->getDeviceId(),
                $device->getDeviceIpAddress()
            )
            && $this->deviceRepository->isDuplicate($device->getDeviceIpAddress())
        ) {
            throw new WebAPIException(ErrorCodeConst::ERROR_400_DUPLICATE_HOSR_NAME_OR_IP_ADDRESS, [], 400);
        }
        if (max(array_count_values($ipAddressList)) > 1) {
            throw new WebAPIException(ErrorCodeConst::ERROR_400_DUPLICATE_HOSR_NAME_OR_IP_ADDRESS, [], 400);
        }
    }
}
