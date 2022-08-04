<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\helper;

/**
 * 町域（カナ）を複数に分割する -> 主と従属と連続した住所単位パターン
 * `〇〇（✗✗n〜m丁目、△△町）`といったようなデータに対して、
 * nとmの間の数値を生成した上で住所単位を付与して独立した町域情報に分割する
 * 加えて従属した町域も独立した町域情報として分割する
 * 主・従属パターンと主の連続パターンの複合パターンのため実際の処理は移譲する
 */
class SplitMainSubSerialUnit extends SplitTownArea
{
    /**
     * 必要な情報を抽出する
     * @param string $townArea 町域名称
     * @param string $townAreaKana 町域名称カナ
     * @return array
     */
    public function extract(string $townArea, string $townAreaKana): array
    {
        /* 各情報の抽出 */
        $mainTownArea = $this->extractMatch(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
            $townArea
        );
        $mainTownAreaKana = $this->extractMatch(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA,
            $townAreaKana
        );

        $insideParentheses = $this->extractMatch(
            ZipCodeConstants::REGEX_INSIDE_PARENTHESES,
            $townArea,
            '、'
        );
        $insideParenthesesKana = $this->extractMatch(
            ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA,
            $townAreaKana,
            '､'
        );

        /* 移譲するメソッドごとに町域の振り分け */
        // カッコ内の従属町域 -> 連続町域（カナ）と複数町域（カナ）に分ける
        $subTownArray = explode('、', $insideParentheses);
        $subTownArrayKanas = explode('､', $insideParenthesesKana);
        $subSerialTownAreas = ['townArea' => [], 'townAreaKana' => []];
        $subTownAreas = ['townArea' => [], 'townAreaKana' => []];
        foreach ($subTownArray as $idx => $subTownArea) {
            if (
                TownAreaAnalyzer::hasSerial($subTownArea)
                && !TownAreaAnalyzer::hasSerialBanchiGou($subTownArea)
            ) {
                // true 連続町域 splitSerialMain
                $subSerialTownAreas['townArea'][] = $subTownArea;
                $subSerialTownAreas['townAreaKana'][] = $subTownArrayKanas[$idx];
            } else {
                // false 複数町域 splitMainSub
                $subTownAreas['townArea'][] = $subTownArea;
                $subTownAreas['townAreaKana'][] = $subTownArrayKanas[$idx];
            }
        }
        return [
            'mainTownArea' => $mainTownArea
            ,
            'mainTownAreaKana' => $mainTownAreaKana
            ,
            'subSerialTownAreas' => $subSerialTownAreas
            ,
            'subTownAreas' => $subTownAreas
        ];
    }

    /**
     * 抽出した情報を加工する
     * @param array $townAreaInfo 町域名称（カナ）情報
     * @return array                加工した町域名称（カナ）情報
     */
    protected function process(array $townAreaInfo): array
    {
        $mainTownArea = $townAreaInfo['mainTownArea'];
        $mainTownAreaKana = $townAreaInfo['mainTownAreaKana'];
        $subSerialTownAreas = $townAreaInfo['subSerialTownAreas'];
        $subTownAreas = $townAreaInfo['subTownAreas'];
        /* 分割処理の移譲 それぞれの移譲先の戻り値をマージして返却*/
        $processed = ['townArea' => [], 'townAreaKana' => []];
        foreach ($subSerialTownAreas['townArea'] as $idx => $subSerialTownArea) {
            $splitter = new SplitSerialMain();
            $processed = array_merge_recursive(
                $processed,
                $splitter->split(
                    $mainTownArea . $subSerialTownArea,
                    $mainTownAreaKana . $subSerialTownAreas['townAreaKana'][$idx]
                )
            );
        }

        $mainSub = implode('、', $subTownAreas['townArea']);
        $mainSubKana = implode('､', $subTownAreas['townAreaKana']);
        $splitter = new SplitMainSub();
        $processed = array_merge_recursive(
            $processed,
            $splitter->split(
                $mainTownArea . '（' . $mainSub . '）',
                $mainTownAreaKana . '(' . $mainSubKana . ')'
            )
        );
        return $processed;
    }
}
