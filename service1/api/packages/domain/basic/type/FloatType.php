<?php

namespace packages\domain\basic\type;

interface FloatType
{
    public function toFloat(): float;

    public function getValue(): ?float;
}
