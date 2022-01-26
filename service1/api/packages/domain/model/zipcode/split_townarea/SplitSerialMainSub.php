
<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;

use packages\domain\model\zipcode\ZipCodeConstants;
use packages\domain\model\zipcode\TowAreaAnalyzer;

class SplitSerialMainSub extends SplitTownArea {

    /**
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array
     */
    protected function extract(string $townArea, string $townAreaKana): array
    {
        /* 必要な情報の抽出 */
        $mainTownArea     = $this->extractMatch(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
            $townArea);
        $mainTownAreaKana = $this->extractMatch(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA,
            $townAreaKana
        );

        $subTownArea     = $this->extractMatch(
            ZipCodeConstants::REGEX_PARENTHESES,
            $townArea
        );
        $subTownAreaKana = $this->extractMatch(
            ZipCodeConstants::REGEX_PARENTHESES_KANA,
            $townAreaKana
        );

        return [
             'mainTownArea'     => $mainTownArea
            ,'subTownArea'      => $subTownArea
            ,'mainTownAreaKana' => $mainTownAreaKana
            ,'subTownAreaKana'  => $subTownAreaKana
        ];
}

    /**
     * @param  array  $townAreaInfo 町域名称（カナ）情報
     * @return array                加工した町域名称（カナ）情報
     */
    protected function process(array $townAreaInfo): array
    {
        $mainTownArea     = $townAreaInfo['mainTownArea'];
        $subTownArea      = $townAreaInfo['subTownArea'];
        $mainTownAreaKana = $townAreaInfo['mainTownAreaKana'];
        $subTownAreaKana  = $townAreaInfo['subTownAreaKana'];

        /* 抽出した情報を元に連番の町域を生成 */
        // 連番生成処理の移譲
        $splitter = new SplitSerialMain;
        $serialedMains = $splitter->split($mainTownArea, $mainTownAreaKana);
        for($i = 0; $i < count($serialedMains['townArea']); $i++) {
            $serialedMains['townArea'][$i]     .= $subTownArea;
            $serialedMains['townAreaKana'][$i] .= $subTownAreaKana;
        }
        return $serialedMains;
    }
}
