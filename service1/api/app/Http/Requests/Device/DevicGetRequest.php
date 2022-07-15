<?php

namespace App\Http\Requests\Device;

use App\Http\Requests\Basic\AbstractFormRequest;
use App\Http\Requests\Definition\Device\DeviceInfoDefinition;
use packages\domain\basic\page\Page;
use packages\domain\basic\page\Pageable;
use packages\domain\basic\page\PerPage;
use packages\domain\model\facility\device\criteria\DeviceCriteria;
use packages\domain\model\facility\workplace\WorkplaceId;

class DevicGetRequest extends AbstractFormRequest
{
    public function __construct(DeviceInfoDefinition $definition = null)
    {
        parent::__construct($definition);
    }

    protected function transform(array $attrs): array
    {
        return $attrs;
    }

    public function toWorkplaceId(): WorkplaceId
    {
        return new WorkplaceId($this->facilityCode);
    }

    public function toPageable(): Pageable
    {
        $page = new Page($this->page);
        $perPage = new PerPage($this->perPage);
        return new Pageable($page, $perPage);
    }

    public function toDeviceCriteria(): DeviceCriteria
    {
        return DeviceCriteria::create(
            $this->facilityCode,
            $this->page,
            $this->perPage
        );
    }
}
