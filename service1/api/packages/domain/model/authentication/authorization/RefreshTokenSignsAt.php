<?php

namespace packages\domain\model\authentication\authorization;

use packages\domain\basic\type\DateTimeType;

class RefreshTokenSignsAt implements DateTimeType
{
    private string $value;

    public function __construct(string $value = null)
    {
        $this->value = $value;
    }

    public function isEmpty(): bool{
        return false;
    }

    public function toLocalDateTime(): \DateTime {
        return new \DateTime($this->value);
    }

    public function isExpired(): bool{
        if(new \DateTime($this->value) < new \DateTime()){
            return true;
        }
        return false;
    }
}
