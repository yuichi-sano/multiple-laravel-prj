<?php

namespace packages\domain\basic\type;

use SplFileObject;

interface FileType
{
    public function toFile(): SplFileObject;
}
