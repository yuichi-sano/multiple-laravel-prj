<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

use packages\domain\basic\type\FileType;
use SplFileObject;

class ZipCodeSource implements FileType
{
    private mixed $sourcePath;

    public function __construct($sourcePath)
    {
        $this->sourcePath = $sourcePath;
    }

    public function toFile(): SplFileObject
    {
        $file = new SplFileObject($this->sourcePath);

        $file->setFlags(
            SplFileObject::READ_CSV |           // CSV 列として行を読み込む
            SplFileObject::READ_AHEAD |       // 先読み/巻き戻しで読み出す。
            SplFileObject::SKIP_EMPTY |         // 空行は読み飛ばす
            SplFileObject::DROP_NEW_LINE    // 行末の改行を読み飛ばす
        );
        $file->setCsvControl(',', '"');
        return $file;
    }
}
