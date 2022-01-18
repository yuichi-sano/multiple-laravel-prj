<?php

declare(strict_types=1);

namespace packages\domain\model\User;

use packages\domain\basic\type\IntegerType;

class UserId implements IntegerType
{
    private int $value;

    public function __construct(int $id)
    {
        $this->value = $id;
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }



    function  toInteger(): int
    {
        return $this->value;
    }

}
