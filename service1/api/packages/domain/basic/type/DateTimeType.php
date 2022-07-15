<?php

namespace packages\domain\basic\type;

use DateTime;

interface DateTimeType
{
    public function toLocalDateTime(): DateTime;

    public function getValue(): ?DateTime;
}
