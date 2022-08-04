<?php

namespace packages\domain\model\authentication\authorization;

use packages\domain\basic\type\StringType;

class AccessToken implements StringType
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
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
