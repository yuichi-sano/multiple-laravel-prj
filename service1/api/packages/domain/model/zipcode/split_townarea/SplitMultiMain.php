<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;

class SplitMultiMain extends SplitTownArea {

    /**
     * 必要な情報を加工する
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
     * 抽出した情報を加工する
     * この分割パターンは例外的に加工を必要としない
     * @param  array $townAreaInfo 町域名称情報
     * @return array               加工した町域名称（カナ）
     */
    public function process(array $townAreaInfo): array
    {
        return $townAreaInfo;
    }

}
