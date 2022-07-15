<?php

declare(strict_types=1);

namespace packages\domain\model\facility\workplace;

use packages\domain\basic\type\IntegerType;

class WorkplaceId implements IntegerType
{
    private ?int $value;

    public function __construct(int $value = null)
    {
        $this->value = $value;
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
