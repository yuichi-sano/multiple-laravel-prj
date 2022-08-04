<?php

namespace packages\domain\basic\mail;

class Subject
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function toString(): string
    {
        return (string)$this->value;
    }
}
