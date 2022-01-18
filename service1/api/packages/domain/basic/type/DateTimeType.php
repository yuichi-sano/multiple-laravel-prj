<?php
namespace packages\domain\basic\type;

interface DateTimeType
{
    public function  toLocalDateTime(): \DateTime;
}
