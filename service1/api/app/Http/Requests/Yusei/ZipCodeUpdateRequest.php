<?php

namespace App\Http\Requests\Yusei;

use App\Http\Requests\Basic\AbstractFormRequest;
use App\Http\Requests\Definition\Yusei\YuseiZipCodeUpdateDefinition;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\user\UserId;

class ZipCodeUpdateRequest extends AbstractFormRequest
{
    public function __construct(YuseiZipCodeUpdateDefinition $definition = null)
    {
        parent::__construct($definition);
    }

    protected function transform(array $attrs): array
    {
        return $attrs;
    }

    public function toMigrationBatchAuditApplyDate(): MigrationBatchAuditApplyDate
    {
        return MigrationBatchAuditApplyDate::create($this->applyDate);
    }

    public function toUserId(): UserId
    {
        return $this->getAuthedUser()->getUserId();
    }
}
