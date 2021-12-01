<?php
namespace packages\Domain\Basic\Type;

interface FileType
{
    public function toFile(): \File;
}
