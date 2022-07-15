<?php

declare(strict_types=1);

namespace packages\domain\model\batch;

use DateTime;
use packages\domain\basic\type\DateTimeType;

class MigrationBatchAuditApplyDate implements DateTimeType
{
    private ?DateTime $value;

    public function __construct(DateTime $value = null)
    {
        $this->value = $value;
    }

    public static function create(string $value = null): MigrationBatchAuditApplyDate
    {
        if ($value) {
            return new MigrationBatchAuditApplyDate(DateTime::createFromFormat('Y-m-d H:i:s', $value));
        }
        return new MigrationBatchAuditApplyDate();
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }

    public function toLocalDateTime(): DateTime
    {
        return $this->value;
    }

    public function getValue(): ?DateTime
    {
        return $this->value;
    }

    public function format(): ?string
    {
        if ($this->isEmpty()) {
            return null;
        }
        return $this->value->format('Y-m-d H:i:s');
    }
}
