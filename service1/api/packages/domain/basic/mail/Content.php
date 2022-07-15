<?php

namespace packages\domain\basic\mail;

class Content
{
    private array $lines;

    public function __construct(array $lines)
    {
        $this->lines = $lines;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function toString(): string
    {
        return join("\n", $this->lines);
    }
}
