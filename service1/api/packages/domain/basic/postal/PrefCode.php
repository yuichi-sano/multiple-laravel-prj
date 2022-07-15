<?php

declare(strict_types=1);

namespace packages\domain\basic\postal;

use packages\domain\basic\type\StringType;

class PrefCode implements StringType
{
    protected ?string $value;

    public function __construct(string $value = null)
    {
        $this->value = sprintf('%02d', $value);
    }

    public function isEmpty(): bool
    {
        if (!$this->value) {
            return true;
        }
        return false;
    }

    public function toString(): string
    {
        if ($this->isEmpty()) {
            return "";
        }
        return $this->value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
