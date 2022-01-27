
<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;
use packages\domain\model\zipcode\ZipCodeConstants;

class SplitSerialMain extends SplitTownArea {

    /**
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array                 抽出した町域名称（カナ）
     */
    public function extract(string $townArea, string $townAreaKana): array
    {
        // もしカッコがついていたら不要なので外す
        if(TownAreaAnalyzer::hasSub($townArea)) {
            $townArea = $this->extractMatch(
                ZipCodeConstants::REGEX_INSIDE_PARENTHESES,
                $townArea
            );
        }
        if(TownAreaAnalyzer::hasSubKana($townAreaKana)) {
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
             'serial_start'       => $serial['start']
            ,'serial_end'         => $serial['end']
            ,'townAreaBefore'     => $townAreaBefore
            ,'townAreaAfter'      => $townAreaAfter
            ,'townAreaBeforeKana' => $townAreaBeforeKana
            ,'townAreaAfterKana'  => $townAreaAfterKana
        ];

    }

    /**
     * @param  array $townAreaInfo 町域名称情報
     * @return array               加工した町域名称（カナ）
     */
    public function process(array $townAreaInfo): array
    {
        return $this->generateTownAreaStartToEnd(
             (int)$townAreaInfo['serial_start']
            ,(int)$townAreaInfo['serial_end']
            ,$townAreaInfo['townAreaBefore']
            ,$townAreaInfo['townAreaAfter']
            ,$townAreaInfo['townAreaBeforeKana']
            ,$townAreaInfo['townAreaAfterKana']
        );
    }

}
