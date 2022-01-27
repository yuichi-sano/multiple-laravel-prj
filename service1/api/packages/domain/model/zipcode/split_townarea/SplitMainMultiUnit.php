<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;
use packages\domain\model\zipcode\ZipCodeConstants;

class SplitMainMultiUnit extends SplitTownArea {

    /**
     * 必要な情報の抽出をする
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array
     */
    protected function extract(string $townArea, string $townAreaKana): array
    {

        /* 町域（カナ）の抽出 */
        $mainTownArea = $this->extractMatch(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
            $townArea
        );
        $subTownAreas = $this->extractMatchArray(
            ZipCodeConstants::REGEX_INSIDE_PARENTHESES,
            $townArea,
            '、'
        );

        $mainTownAreaKana = $this->extractMatch(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA,
            $townAreaKana
        );
        $subTownAreaKanas = $this->extractMatchArray(
            ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA,
            $townAreaKana,
            '､'
        );

        /* 住所単位の抽出 */
        $lastIndex    = count($subTownAreas)-1;
        $unitName     = $this->extractAfterNum($subTownAreas[$lastIndex]);
        $unitNameKana = $this->extractAfterNum($subTownAreaKanas[$lastIndex]);

        return [
                 'mainTownArea'     => $mainTownArea
                ,'subTownAreas'     => $subTownAreas
                ,'mainTownAreaKana' => $mainTownAreaKana
                ,'subTownAreaKanas' => $subTownAreaKanas
                ,'unitName'         => $unitName
                ,'unitNameKana'     => $unitNameKana
                ,'lastIndex'        => $lastIndex
        ];
    }

    /**
     * 抽出した情報を加工する
     * @param  array  $townAreaInfo 町域名称（カナ）情報
     * @return array                加工した町域名称（カナ）情報
     */
    protected function process(array $townAreaInfo): array
    {
        $processed = ['townArea' => [], 'townAreaKana' => []];
        for($i = 0; $i <= (int)$townAreaInfo['lastIndex']; $i++){
            if($i < (int)$townAreaInfo['lastIndex']) {
                $processed['townArea'][]     =  $townAreaInfo['mainTownArea']
                                              . $townAreaInfo['subTownAreas'][$i]
                                              . $townAreaInfo['unitName'];
                $processed['townAreaKana'][] =  $townAreaInfo['mainTownAreaKana']
                                              . $townAreaInfo['subTownAreaKanas'][$i]
                                              . $townAreaInfo['unitNameKana'];
            } else {
                $processed['townArea'][]     =  $townAreaInfo['mainTownArea']
                                              . $townAreaInfo['subTownAreas'][$i];
                $processed['townAreaKana'][] =  $townAreaInfo['mainTownAreaKana']
                                              . $townAreaInfo['subTownAreaKanas'][$i];
            }
        }
        return $processed;
    }
}
