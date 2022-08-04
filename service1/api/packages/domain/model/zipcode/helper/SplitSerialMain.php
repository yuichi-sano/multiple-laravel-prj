<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\helper;

/**
 * 町域（カナ）を複数に分割する -> 連番の主パターン
 * `〇〇n地割〜〇〇m地割`といったようなデータに対して、
 * nとmの間の数値を生成して、それぞれ独立した町域として分割する
 */
class SplitSerialMain extends SplitTownArea
{
    /**
     * 必要な情報を抽出する
     * @param string $townArea 町域名称
     * @param string $townAreaKana 町域名称カナ
     * @return array                 抽出した町域名称（カナ）
     */
    public function extract(string $townArea, string $townAreaKana): array
    {
        // もしカッコがついていたら不要なので外す
        if (TownAreaAnalyzer::hasSub($townArea)) {
            $townArea = $this->extractMatch(
                ZipCodeConstants::REGEX_INSIDE_PARENTHESES,
                $townArea
            );
        }
        if (TownAreaAnalyzer::hasSubKana($townAreaKana)) {
            $townAreaKana = $this->extractMatch(
                ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA,
                $townAreaKana
            );
        }

        /* 連番の前後の町域名称（カナ）を抽出 */
        $townAreaBefore = $this->extractBeforeNum(
            $this->extractMatch(
                ZipCodeConstants::REGEX_BEFORE_SERIAL_TOWNAREA,
                $townArea
            )
        );
        $townAreaAfter = $this->extractAfterNum(
            $this->extractMatch(
                ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA,
                $townArea
            )
        );
        $townAreaBeforeKana = $this->extractBeforeNum(
            $this->extractMatch(
                ZipCodeConstants::REGEX_BEFORE_SERIAL_TOWNAREA_KANA,
                $townAreaKana
            )
        );
        $townAreaAfterKana = $this->extractAfterNum(
            $this->extractMatch(
                ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA_KANA,
                $townAreaKana
            )
        );

        /* 連番の始点と終点の抽出 */
        $serial = $this->extractSerialStartAndEnd($townAreaKana);

        return [
            'serial_start' => $serial['start']
            ,
            'serial_end' => $serial['end']
            ,
            'townAreaBefore' => $townAreaBefore
            ,
            'townAreaAfter' => $townAreaAfter
            ,
            'townAreaBeforeKana' => $townAreaBeforeKana
            ,
            'townAreaAfterKana' => $townAreaAfterKana
        ];
    }

    /**
     * 抽出した情報を加工する
     * @param array $townAreaInfo 町域名称情報
     * @return array               加工した町域名称（カナ）
     */
    public function process(array $townAreaInfo): array
    {
        return $this->generateTownAreaStartToEnd(
            (int)$townAreaInfo['serial_start'],
            (int)$townAreaInfo['serial_end'],
            $townAreaInfo['townAreaBefore'],
            $townAreaInfo['townAreaAfter'],
            $townAreaInfo['townAreaBeforeKana'],
            $townAreaInfo['townAreaAfterKana']
        );
    }
}
