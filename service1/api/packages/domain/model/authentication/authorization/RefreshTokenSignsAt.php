<?php

namespace packages\domain\model\authentication\authorization;

use packages\domain\basic\type\DateTimeType;

class RefreshTokenSignsAt implements DateTimeType
{
    private \DateTime $value;

    public function __construct(string $value = null)
    {
        $this->value = new \DateTime($value);
    }

    public function isEmpty(): bool{
        return false;
    }

    public function toLocalDateTime(): \DateTime {
        return $this->value;
    }

    public function isExpired(): bool{
        if($this->value < new \DateTime()){
            return true;
        }
        return false;
    }
}
