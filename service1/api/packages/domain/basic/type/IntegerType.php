<?php

namespace packages\domain\basic\type;

interface IntegerType
{
    public function toInteger(): int;

    public function getValue(): ?int;
}
