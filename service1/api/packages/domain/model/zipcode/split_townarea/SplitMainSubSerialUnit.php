<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;

use packages\domain\model\zipcode\ZipCodeConstants;
use packages\domain\model\zipcode\split_townArea\TownAreaAnalyzer;

class SplitMainSubSerialUnit extends SplitTownArea {


    /**
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array
     */
    function extract(string $townArea, string $townAreaKana): array
    {
        /* 各情報の抽出 */
        $mainTownArea          = parent::extractMatch(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
            $townArea
        );
        $mainTownAreaKana      = parent::extractMatch(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA,
            $townAreaKana
        );

        $insideParentheses     = parent::extractMatch(
            ZipCodeConstants::REGEX_INSIDE_PARENTHESES,
            $townArea,
            '、'
        );
        $insideParenthesesKana = parent::extractMatch(
            ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA,
            $townAreaKana,
            '､'
        );

        /* 移譲するメソッドごとに町域の振り分け */
        // カッコ内の従属町域 -> 連続町域（カナ）と複数町域（カナ）に分ける
        $subTownArray       = explode('、', $insideParentheses);
        $subTownArrayKanas  = explode('､', $insideParenthesesKana);
        $subSerialTownAreas = ['townArea' => [], 'townAreaKana' => []];
        $subTownAreas       = ['townArea' => [], 'townAreaKana' => []];
        foreach($subTownArray as $idx => $subTownArea){
            if(
                    TownAreaAnalyzer::hasSerial($subTownArea)
                && !TownAreaAnalyzer::hasSerialBanchiGou($subTownArea)
            ) {
                // true 連続町域 splitSerialMain
                $subSerialTownAreas['townArea'][]     = $subTownArea;
                $subSerialTownAreas['townAreaKana'][] = $subTownArrayKanas[$idx];
            }
            else{
                // false 複数町域 splitMainSub
                $subTownAreas['townArea'][]     = $subTownArea;
                $subTownAreas['townAreaKana'][] = $subTownArrayKanas[$idx];
            }

        }
        return [
             'mainTownArea'       => $mainTownArea
            ,'mainTownAreaKana'   => $mainTownAreaKana
            ,'subSerialTownAreas' => $subSerialTownAreas
            ,'subTownAreas'       => $subTownAreas
        ];
}

    /**
     * @param  array  $townAreaInfo 町域名称（カナ）情報
     * @return array                加工した町域名称（カナ）情報
     */
    protected function process(array $townAreaInfo): array
    {
        $mainTownArea       = $townAreaInfo['mainTownArea'];
        $mainTownAreaKana   = $townAreaInfo['mainTownAreaKana'];
        $subSerialTownAreas = $townAreaInfo['subSerialTownAreas'];
        $subTownAreas       = $townAreaInfo['subTownAreas'];
        /* 分割処理の移譲 それぞれの移譲先の戻り値をマージして返却*/
        $processed = ['townArea' => [], 'townAreaKana' => []];
        foreach($subSerialTownAreas['townArea'] as $idx => $subSerialTownArea) {
            $splitter = new SplitSerialMain;
            $processed = array_merge_recursive(
                $processed,
                    $splitter->split(
                    $mainTownArea . $subSerialTownArea,
                    $mainTownAreaKana . $subSerialTownAreas['townAreaKana'][$idx]
                )
            );
        }

        $mainSub     = implode('、', $subTownAreas['townArea']);
        $mainSubKana = implode('､', $subTownAreas['townAreaKana']);
        $splitter = new SplitMainSub;
        $processed = array_merge_recursive(
            $processed,
                $splitter->split(
                $mainTownArea . '（' . $mainSub . '）',
                $mainTownAreaKana . '(' . $mainSubKana .')'
            )
        );
        return $processed;
    }
}
