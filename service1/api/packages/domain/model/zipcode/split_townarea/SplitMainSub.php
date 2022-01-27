<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;
use packages\domain\model\zipcode\ZipCodeConstants;

class SplitMainSub extends SplitTownArea {

    /**
     * 必要な情報を加工する
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array                 抽出した町域名称（カナ）
     */
    protected function extract(string $townArea, string $townAreaKana): array
    {
        $mainTownArea = $this->extractMatch(
             ZipCodeConstants::REGEX_BEFORE_PARENTHESES
            ,$townArea
        );
        $subTownAreas = $this->extractMatchArray(
             ZipCodeConstants::REGEX_INSIDE_PARENTHESES
            ,$townArea
            ,'、'
        );

       $mainTownAreaKana = '';
       if(TownAreaAnalyzer::hasMainKana($townAreaKana)) {
            $mainTownAreaKana = $this->extractMatch(
                 ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA
                ,$townAreaKana
            );
        } else {
            $mainTownAreaKana = $townAreaKana;
        }

        $subTownAreaKanas = [];
        if(TownAreaAnalyzer::needSplitKana($townAreaKana)) {
            $subTownAreaKanas = $this->extractMatchArray(
                 ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA
                ,$townAreaKana
                ,'､'
            );
        } elseif(TownAreaAnalyzer::hasSubKana($townAreaKana)) {
            // 分割するレコードでも、従属する町域カナは単一の値の場合がある
            $subTownAreaKanas = [
                $this->extractMatch(
                     ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA
                    ,$townAreaKana
                )
            ];
        } else {
            // そもそも存在しない場合もある
            $subTownAreaKanas = null;
        }

        return [
                 'mainTownArea'     => $mainTownArea
                ,'subTownAreas'     => $subTownAreas
                ,'mainTownAreaKana' => $mainTownAreaKana
                ,'subTownAreaKanas' => $subTownAreaKanas
        ];
    }

    /**
     * 抽出した情報を加工する
     * @param  array $townAreaInfo 町域名称情報
     * @return array                 抽出した町域名称（カナ）
     */
    protected function process(array $townAreaInfo): array
    {
        $mainTownArea     = $townAreaInfo['mainTownArea'];
        $subTownAreas     = $townAreaInfo['subTownAreas'];
        $mainTownAreaKana = $townAreaInfo['mainTownAreaKana'];
        $subTownAreaKanas = $townAreaInfo['subTownAreaKanas'];

        // 分割したレコードの一つに、従属する町名を含めないものが必要になる
        array_unshift($subTownAreas, '');
        if(!is_null($subTownAreaKanas)) {
            array_unshift($subTownAreaKanas, '');
        }

        // 町域（カナ）の加工
        $processed = ['townArea' => [], 'townAreaKana' => []];
        foreach($subTownAreas as $index => $subTownArea){
            $processed['townArea'][]     = $mainTownArea . $subTownArea;
            $processed['townAreaKana'][] = is_null($subTownAreaKanas) ?
                $mainTownAreaKana : // 処理の共通化の為単一の値を配列に格納
                $mainTownAreaKana . $subTownAreaKanas[$index];
        }
        return $processed;
    }
}
