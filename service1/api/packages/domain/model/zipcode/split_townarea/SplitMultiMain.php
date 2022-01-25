<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;

class SplitMultiMain extends SplitTownArea {

    /**
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array                 抽出した町域名称（カナ）
     */
    public function extract(string $townArea, string $townAreaKana): array
    {

        $processed['townArea']     = explode('、', $townArea);
        $processed['townAreaKana'] = explode('､', $townAreaKana);

        return $processed;
    }

    /**
     * @param  array $townAreaInfo 町域名称情報
     * @return array               加工した町域名称（カナ）
     */
    public function process(array $townAreaInfo): array
    {
        // この分割パターンは加工を必要としない
        return $townAreaInfo;
    }

}
