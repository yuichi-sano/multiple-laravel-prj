<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;
use packages\domain\model\zipcode\ZipCodeConstants;
use packages\domain\model\zipcode\TowAreaAnalyzer;

class SplitMainSerialUnit extends SplitTownArea {

    /**
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array
     */
    protected function extract(string $townArea, string $townAreaKana): array
    {
        /* 町域名称（カナ）を構成する各情報を抽出する */

        // 主の町域名称（カナ）
        $mainTownArea     = parent::extractMatch(
             ZipCodeConstants::REGEX_BEFORE_PARENTHESES
            ,$townArea
        );
        $mainTownAreaKana = parent::extractMatch(
             ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA
            ,$townAreaKana
        );

        // カッコ内の情報
        $insideParentheses     = parent::extractMatch(
             ZipCodeConstants::REGEX_INSIDE_PARENTHESES
            ,$townArea
        );
        $insideParenthesesKana = parent::extractMatch(
             ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA
            ,$townAreaKana
        );

        // カッコ内の、従属する町域名称（カナ ）に連番と住所区分単位が存在
        // それらを全て抽出する必要がある

        // 町域名称（カナ）
        $subTownArea     = parent::extractBeforeNum(
            parent::extractMatch(
                 ZipCodeConstants::REGEX_BEFORE_SERIAL_TOWNAREA
                ,$insideParentheses
            )
        );
        $subTownAreaKana = parent::extractBeforeNum(
            parent::extractMatch(
                 ZipCodeConstants::REGEX_BEFORE_SERIAL_TOWNAREA_KANA
                ,$insideParenthesesKana
            )
        );

        // 住所区分単位
        $unitName    = parent::extractAfterNum(
            parent::extractMatch(
                 ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA
                ,$insideParentheses
            )
        );
        $unitNameKana = parent::extractAfterNum(
            parent::extractMatch(
                 ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA_KANA
                ,$insideParenthesesKana
            )
        );

        // 連番
        $serial = parent::extractSerialStartAndEnd($townAreaKana);

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
