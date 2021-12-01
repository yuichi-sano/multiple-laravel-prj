<?php
namespace packages\Domain\Basic\Type;

interface DateTimeType
{
    public function  toLocalDateTime(): \DateTime;
}
