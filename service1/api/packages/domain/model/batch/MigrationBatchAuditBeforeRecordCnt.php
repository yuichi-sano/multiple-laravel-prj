<?php

declare(strict_types=1);

namespace packages\domain\model\batch;

use packages\domain\basic\type\IntegerType;

class MigrationBatchAuditBeforeRecordCnt implements IntegerType
{
    private int $value;

    public function __construct($recordCnt)
    {
        $this->value = $recordCnt;
    }

    public static function create(string $value): self
    {
        return new self((int)$value);
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }

    public function toInteger(): int
    {
        return $this->value;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}
