<?php

declare(strict_types=1);

namespace packages\domain\model\facility\device\criteria;

use packages\domain\basic\page\Page;
use packages\domain\basic\page\Pageable;
use packages\domain\basic\page\PerPage;
use packages\domain\model\facility\workplace\WorkplaceId;

class DeviceCriteria
{
    public Pageable $pageable;
    public WorkplaceId $workplaceId;

    private function __construct(
        WorkplaceId $workplaceId,
        Pageable $pageable
    ) {
        $this->workplaceId = $workplaceId;
        $this->pageable = $pageable;
    }

    public static function create(
        ?int $workplaceId,
        int $page,
        int $perPage
    ): DeviceCriteria {
        return new DeviceCriteria(
            new WorkplaceId($workplaceId),
            new Pageable(new Page($page), new PerPage($perPage))
        );
    }
}
