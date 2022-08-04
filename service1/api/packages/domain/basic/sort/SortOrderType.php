<?php

declare(strict_types=1);

namespace packages\domain\basic\sort;

use packages\domain\basic\type\StringType;

class SortOrderType implements StringType
{
    private ?int $value;
    public const SortTypeValues = [
        0 => 'ASC',
        1 => 'DESC'
    ];

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function create(string $sortOrder)
    {
        foreach (self::SortTypeValues as $key => $sortTypeValue) {
            if ($sortOrder == $sortTypeValue) {
                return new self($key);
            }
        }
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }

    public function orderBy(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        foreach (self::SortTypeValues as $key => $sortTypeValue) {
            if ($key === $this->value) {
                return $sortTypeValue;
            }
        }
        //TODO Exceptions
    }
}
