<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;
use packages\domain\model\zipcode\ZipCodeConstants;

/**
 * 町域（カナ）を複数に分割する -> 主と連続した住所単位パターン
 * `〇〇（✗✗n〜m丁目）`といったようなデータに対して、
 * nとmの間の数値を生成した上で住所単位を付与して独立した町域情報に分割する
 */
class SplitMainSerialUnit extends SplitTownArea {

    /**
     * 必要な情報を抽出する
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array
     */
    protected function extract(string $townArea, string $townAreaKana): array
    {
        /* 町域名称（カナ）を構成する各情報を抽出する */

        // 主の町域名称（カナ）
        $mainTownArea     = $this->extractMatch(
             ZipCodeConstants::REGEX_BEFORE_PARENTHESES
            ,$townArea
        );
        $mainTownAreaKana = $this->extractMatch(
             ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA
            ,$townAreaKana
        );

        // カッコ内の情報
        $insideParentheses     = $this->extractMatch(
             ZipCodeConstants::REGEX_INSIDE_PARENTHESES
            ,$townArea
        );
        $insideParenthesesKana = $this->extractMatch(
             ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA
            ,$townAreaKana
        );

        // カッコ内の、従属する町域名称（カナ ）に連番と住所区分単位が存在
        // それらを全て抽出する必要がある

        // 町域名称（カナ）
        $subTownArea     = $this->extractBeforeNum(
            $this->extractMatch(
                 ZipCodeConstants::REGEX_BEFORE_SERIAL_TOWNAREA
                ,$insideParentheses
            )
        );
        $subTownAreaKana = $this->extractBeforeNum(
            $this->extractMatch(
                 ZipCodeConstants::REGEX_BEFORE_SERIAL_TOWNAREA_KANA
                ,$insideParenthesesKana
            )
        );

        // 住所区分単位
        $unitName    = $this->extractAfterNum(
            $this->extractMatch(
                 ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA
                ,$insideParentheses
            )
        );
        $unitNameKana = $this->extractAfterNum(
            $this->extractMatch(
                 ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA_KANA
                ,$insideParenthesesKana
            )
        );

        // 連番
        $serial = $this->extractSerialStartAndEnd($townAreaKana);

        return [
             'serial_start'     => $serial['start']
            ,'serial_end'       => $serial['end']
            ,'mainTownArea'     => $mainTownArea
            ,'subTownArea'      => $subTownArea
            ,'mainTownAreaKana' => $mainTownAreaKana
            ,'subTownAreaKana'  => $subTownAreaKana
            ,'unitName'         => $unitName
            ,'unitNameKana'     => $unitNameKana
        ];
    }

    /**
     * 抽出した情報を加工する
     * @param  array  $townAreaInfo 町域名称（カナ）情報
     * @return array                加工した町域名称（カナ）情報
     */
    protected function process(array $townAreaInfo): array
    {
        return $this->generateTownAreaStartToEnd(
             (int)$townAreaInfo['serial_start']
            ,(int)$townAreaInfo['serial_end']
            ,$townAreaInfo['mainTownArea'] . $townAreaInfo['subTownArea']
            ,$townAreaInfo['unitName']
            ,$townAreaInfo['mainTownAreaKana'] . $townAreaInfo['subTownAreaKana']
            ,$townAreaInfo['unitNameKana']
        );
    }
}
