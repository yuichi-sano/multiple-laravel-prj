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
use packages\domain\model\facility\device\HTDevice;
use packages\domain\model\facility\device\HTDeviceRepository;
use packages\domain\model\user\UserId;

class DeviceAddService
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
     * 配送デバイス、ハンディ端末登録
     * @return void
     * @throws WebAPIException
     */
    public function addDevice(Device $device): void
    {
        TransactionManager::startTransaction();
        try {
            $deviceId = $this->deviceRepository->add($device);

            if (is_array($device->getHTDeviceList()->toArray())) {
                foreach ($device->getHTDeviceList()->toArray() as $HTDevice) {
                    $HTDevice->setDeviceId($deviceId);
                    $this->HTDeviceRepository->add($HTDevice);
                }
            }
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
            $this->HTDeviceRepository->deleteByHostId($device->getDeviceId());
            if (is_array($device->getHTDeviceList()->toArray())) {
                foreach ($device->getHTDeviceList()->toArray() as $HTDevice) {
                    $HTDevice->setDeviceId($device->getDeviceId());
                    $this->HTDeviceRepository->add($HTDevice);
                }
            }
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
     * ハンディ端末個別追加
     * @return HTDevice
     */
    public function addHTDevice(HTDevice $HTDevice): HTDevice
    {
        return $this->HTDeviceRepository->add($HTDevice);
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
        foreach ($device->getHTDeviceList() as $hTDevice) {
            if ($this->HTDeviceRepository->isDuplicate($hTDevice->getHTDeviceIpAddress())) {
                throw new WebAPIException(ErrorCodeConst::ERROR_400_DUPLICATE_IP_ADDRESS, [], 400);
            }
            $ipAddressList[] = $hTDevice->getHTDeviceIpAddress()->getValue();
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
        foreach ($device->getHTDeviceList() as $hTDevice) {
            if (
                !$this->HTDeviceRepository->existIpAddress(
                    $device->getDeviceId(),
                    $hTDevice->getHTDeviceIpAddress()
                )
                && $this->HTDeviceRepository->isDuplicate($hTDevice->getHTDeviceIpAddress())
            ) {
                throw new WebAPIException(ErrorCodeConst::ERROR_400_DUPLICATE_IP_ADDRESS, [], 400);
            }
            $ipAddressList[] = $hTDevice->getHTDeviceIpAddress()->getValue();
        }
        if (max(array_count_values($ipAddressList)) > 1) {
            throw new WebAPIException(ErrorCodeConst::ERROR_400_DUPLICATE_HOSR_NAME_OR_IP_ADDRESS, [], 400);
        }
    }
}
