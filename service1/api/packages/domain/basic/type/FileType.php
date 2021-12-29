<?php
namespace packages\domain\basic\type;

interface FileType
{
    public function toFile(): \SplFileObject;
}
