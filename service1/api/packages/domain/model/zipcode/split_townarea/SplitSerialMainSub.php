<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;
use packages\domain\model\zipcode\ZipCodeConstants;

/**
 * 町域（カナ）を複数に分割する -> 連番の主と単一の従属町域パターン
 * 例：`〇〇n地割〜〇〇m地割（✗✗町）`
 * 例の✗✗町は連番の主のすべての町域を包括するため、nとmの間の数値を生成した上で
 * 末尾に（✗✗町）を付与してそれぞれ独立した町域として分割する
 * 連番の主パターンに処理を一部移譲する
 */
class SplitSerialMainSub extends SplitTownArea {

    /**
     * 必要な情報を抽出する
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
     * 抽出した情報を加工する
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
