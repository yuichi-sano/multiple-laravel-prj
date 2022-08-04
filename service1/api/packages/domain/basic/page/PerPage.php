<?php

declare(strict_types=1);

namespace packages\domain\basic\page;

use packages\domain\basic\type\IntegerType;

class PerPage implements IntegerType
{
    private int $value;

    public function __construct($value)
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

    public function isGreaterThanZero(): bool
    {
        return $this->value > 0;
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
