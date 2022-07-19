<?php

namespace packages\service\facility;

use App\Extension\Support\Facades\TransactionManager;
use packages\domain\model\facility\workplace\WorkplaceList;
use packages\domain\model\facility\workplace\WorkplaceRepository;

class FacilityGetService
{
    private WorkplaceRepository $workplaceRepository;

    public function __construct(
        WorkplaceRepository $workplaceRepository,
    ) {
        $this->workplaceRepository = $workplaceRepository;
    }

    /**
     * 拠点一括取得
     * @return WorkplaceList
     */
    public function getWorkplaceList(): WorkplaceList
    {
        return $this->workplaceRepository->list();
    }

}
