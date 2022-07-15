<?php

declare(strict_types=1);

namespace packages\domain\model\batch;

use packages\domain\basic\type\IntegerType;

class MigrationBatchAuditStatus implements IntegerType
{
    private static int $WaitingStatus = 1;
    private static int $DoneStatus = 2;
    private static int $CancelStatus = 3;
    private static int $FailStatus = 4;
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

    public static function getWaitingStatus(): self
    {
        return new self(self::$WaitingStatus);
    }

    public static function getDoneStatus(): self
    {
        return new self(self::$DoneStatus);
    }

    public static function getCancelStatus(): self
    {
        return new self(self::$CancelStatus);
    }

    public static function getFailStatus(): self
    {
        return new self(self::$FailStatus);
    }
}
