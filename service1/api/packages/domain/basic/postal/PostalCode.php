<?php

declare(strict_types=1);

namespace packages\domain\basic\postal;

use packages\domain\basic\type\StringType;

class PostalCode implements StringType
{
    protected ?string $value;

    public function __construct(string $value = null)
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
        return $this->toOnlyNumber($this->value);
    }

    public function format(): string
    {
        return substr($this->value, 0, 3) . "-" . substr($this->value, 3);
    }

    protected function toOnlyNumber(?string $code): string
    {
        return str_replace("-", "", $code);
    }

    public function isOld(): bool
    {
        return strlen($this->value) === 5;
    }

    public function isMostOld(): bool
    {
        return strlen($this->value) === 3;
    }

    public function toMostOldStr(): string
    {
        return substr($this->getValue(), 0, 3);
    }
}
