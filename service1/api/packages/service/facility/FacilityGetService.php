<?php

namespace packages\service\facility;

use App\Extension\Support\Facades\TransactionManager;
use packages\domain\model\facility\device\DeviceList;
use packages\domain\model\facility\device\DeviceRepository;
use packages\domain\model\facility\device\HTDevice;
use packages\domain\model\facility\device\HTDeviceRepository;
use packages\domain\model\facility\workplace\delivery\WorkplaceList;
use packages\domain\model\facility\workplace\delivery\WorkplaceRepository;
use packages\domain\model\facility\workplace\WorkplaceList;
use packages\domain\model\facility\workplace\WorkplaceRepository;
use packages\domain\model\user\UserId;

class FacilityGetService
{
    private DeviceRepository $deviceRepository;
    private HTDeviceRepository $HTDeviceRepository;
    private WorkplaceRepository $workplaceRepository;
    private WorkplaceRepository $workplaceRepository;

    public function __construct(
        DeviceRepository $deviceRepository,
        HTDeviceRepository $HTDeviceRepository,
        WorkplaceRepository $workplaceRepository,
        WorkplaceRepository $workplaceRepository
    ) {
        $this->deviceRepository = $deviceRepository;
        $this->HTDeviceRepository = $HTDeviceRepository;
        $this->workplaceRepository = $workplaceRepository;
        $this->workplaceRepository = $workplaceRepository;
    }

    /**
     * 工場一括取得
     * @return WorkplaceList
     */
    public function getWorkplaceList(): WorkplaceList
    {
        return $this->workplaceRepository->list();
    }

    /**
     * 配送拠点工場のみに焦点を当てて一括取得
     * @return WorkplaceList
     */
    public function getWorkplaceList(): WorkplaceList
    {
        return $this->workplaceRepository->list();
    }
}
