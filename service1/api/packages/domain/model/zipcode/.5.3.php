<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;
class ZipCodeFactory
{

    public function create($row): ZipCode
    {
        return  new ZipCode(
            $row[ZipCodeConstants::IDX_JIS],
            $row[ZipCodeConstants::IDX_ZIPCODE5],
            $row[ZipCodeConstants::IDX_ZIPCODE],
            $row[ZipCodeConstants::IDX_PREFECTURE_KANA],
            $row[ZipCodeConstants::IDX_CITY_KANA],
            $this->cleanTownAreaKana(
                $row[ZipCodeConstants::IDX_TOWNAREA],
                $row[ZipCodeConstants::IDX_TOWNAREA_KANA]
            ),
            $row[ZipCodeConstants::IDX_PREFECTURE],
            $row[ZipCodeConstants::IDX_CITY],
            $this->cleanTownArea($row[ZipCodeConstants::IDX_TOWNAREA]),
            $row[ZipCodeConstants::IDX_IS_ONETOWN_BY_MULTIZIPCODE],
            $row[ZipCodeConstants::IDX_IS_NEEDS_SMALLAREAADDRESS],
            $row[ZipCodeConstants::IDX_IS_CHOUME],
            $row[ZipCodeConstants::IDX_IS_MULTITOWN_BY_ONEPOSTCODE],
            $row[ZipCodeConstants::IDX_UPDATED],
            $row[ZipCodeConstants::IDX_UPDATEREASON],
        );
    }
    public function cleanTownArea($townArea){
        $cleanTownArea= $townArea;
        if((bool)preg_match(ZipCodeConstants::REGEX_IGNORE, $townArea)){
            $cleanTownArea = '';
        };
        if((bool)preg_match(ZipCodeConstants::REGEX_FLOOR, $townArea)){
            $cleanTownArea = preg_replace(ZipCodeConstants::REGEX_FLOOR, '', $townArea);
        }

        /*
        if((bool)preg_match(ZipCodeConstants::REGEX_JIWARI, $townArea)){
            $cleanTownArea = preg_replace(ZipCodeConstants::REGEX_JIWARI, '', $townArea);
        };
        */
        //if((bool)preg_match(ZipCodeConstants::REGEX_PARENTHESES, $townArea)){
        //    $cleanTownArea = preg_replace(ZipCodeConstants::REGEX_PARENTHESES, '', $cleanTownArea);
        //};

        return $cleanTownArea;
    }
    public function cleanTownAreaKana($townArea,$townAreaKana){
        $cleanTownAreaKana= $townAreaKana;
        if((bool)preg_match(ZipCodeConstants::REGEX_IGNORE, $townArea)){
            $cleanTownAreaKana = '';
        };
        if((bool)preg_match(ZipCodeConstants::REGEX_FLOOR_KANA, $townAreaKana)){
            $cleanTownAreaKana = preg_replace(ZipCodeConstants::REGEX_FLOOR_KANA, '', $townAreaKana);
        };
        /*
        if((bool)preg_match(ZipCodeConstants::REGEX_JIWARI_KANA, $townAreaKana)){
            $cleanTownAreaKana = preg_replace(ZipCodeConstants::REGEX_JIWARI_KANA, '', $townAreaKana);
        };
        */
        //if((bool)preg_match(ZipCodeConstants::REGEX_PARENTHESES, $townArea)){
        //    $cleanTownArea = preg_replace(ZipCodeConstants::REGEX_PARENTHESES, '', $cleanTownArea);
        //};

        return $cleanTownAreaKana;


    }

    public function isUnClose($row): bool
    {
        return (bool)preg_match(
            ZipCodeConstants::REGEX_UNCLOSED_PARENTHESES_KANA,
            $row[ZipCodeConstants::IDX_TOWNAREA_KANA]
        ) || 
        (bool)preg_match(
            ZipCodeConstants::REGEX_UNCLOSED_PARENTHESES
           ,$row[ZipCodeConstants::IDX_TOWNAREA]
        );
    }
    public function isClose($row){
        return (bool)preg_match(
            ZipCodeConstants::REGEX_CLOSED_PARENTHESES_KANA
           ,$row[ZipCodeConstants::IDX_TOWNAREA_KANA]
        )
        ||
        (bool)preg_match(
            ZipCodeConstants::REGEX_CLOSED_PARENTHESES
           ,$row[ZipCodeConstants::IDX_TOWNAREA]
        );
    }

    /**
     * 町域名称の値を元にレコードの分割要否を判定する
     * @param  array $row zipcode
     * @return bool       分割要否
     */
    public function needSplit(array $row): bool
    {
        $townArea = $row[ZipCodeConstants::IDX_TOWNAREA];

        $hasMain   = (bool)preg_match(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
            $townArea
        );
        $hasSerial = (bool)preg_match(
            ZipCodeConstants::REGEX_SERIAL_TOWNAREA,
            $townArea
        );
        $hasSeparater = (bool)preg_match(
            ZipCodeConstants::REGEX_SEPARATE_TOWNAREA,
            $townArea
        );

        if($hasMain && $hasSerial && $hasSeparater) {
            // 主の町域と従属する町域と連続する町域が存在する際に、
            // 主の町域が連続する町域であった場合は分割の対象外となる
            // 例：種市第１５地割～第２１地割（鹿糠、小路合、緑町、大久保、高取）
            return !(bool)preg_match(
                ZipCodeConstants::REGEX_SERIAL_TOWNAREA,
                $this->extractMatch(
                    ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
                    $townArea
                )
            );
        }

        if($hasSerial){
            //連番の町域であり、かつ始点と終点に異なる番地を保持している場合は、
            //分割の対象外となる 例: ２４３０－１～２４３１－７５
            $hasSerialBanchiGou = (bool)preg_match(
                ZipCodeConstants::REGEX_SERIAL_BANCHIGOU
               ,$townArea
            );

            //〜のあとに町域情報が存在しないものは分割の対象外となる
            // 例：大前（細原２２５９〜）
            $hasNotSerialEnd = (bool)preg_match(
                ZipCodeConstants::REGEX_NOT_SERIALEND_NUMBER
               ,$townArea
            );

            // $hasSerialBanchiGou, $hasNotSerialEndに該当するのが
            // 複数まとめられた町域のうちの1つであれば分割の対象となる
            return (!$hasSerialBanchiGou && !$hasNotSerialEnd) ||
                   count(explode('、', $townArea)) > 1;
        }

        return $hasSerial || $hasSeparater;
    }

    /**
     * 単一のレコードを複数に分割する
     * 実際に分割を行うのは町域名称（カナ）の値のみでそれ以外の属性値は変わらない
     * 仕様の詳細は、同ディレクトリ配下にあるREADME.mdを参照してください
     * @param  array $row zipcode
     * @return array      分割したzipcodeの配列
     */
    public function splitRow(array $row): array
    {
        $splittedTownAreas = $this->splitTownArea(
            $row[ZipCodeConstants::IDX_TOWNAREA],
            $row[ZipCodeConstants::IDX_TOWNAREA_KANA]
        );

        $splittedRows = [];
        foreach($splittedTownAreas['townArea'] as $index => $townArea){

            $splittedRow = $this->generateTemplateRow($row);

            $splittedRow[ZipCodeConstants::IDX_TOWNAREA]     = $townArea;
            $splittedRow[ZipCodeConstants::IDX_TOWNAREA_KANA] = $splittedTownAreas['townAreaKana'][$index];

            $splittedRows[] = $splittedRow;
        }
        return $splittedRows;
    }


    /**
     * 分割するレコードのテンプレートを生成する
     * @param  array $row テンプレート生成元のzipcode
     * @return array      テンプレートのzipcode
     */
    private function generateTemplateRow(array $row): array
    {
        $samePieceRow = [];
        foreach($row as $attributeIndex => $attribute){
            // レコード内の、町域属性と町域カナ属性以外は同一の値となる
            if($attributeIndex != ZipCodeConstants::IDX_TOWNAREA &&
               $attributeIndex != ZipCodeConstants::IDX_TOWNAREA_KANA
            ){
                $samePieceRow[$attributeIndex] = $attribute;
            } else {
                $samePieceRow[$attributeIndex] = '';
            }
        }
        return $samePieceRow;
    }

    public function isDeprecated(){

    }

    public function mergeRows(array $mergeRows){
        $mergeTownArea=[];
        $mergeTownAreaKana=[];
        $mergedRow = [];
        foreach ($mergeRows as $row){
            $mergeTownArea[] = $row[ZipCodeConstants::IDX_TOWNAREA];
            $mergeTownAreaKana[] = $row[ZipCodeConstants::IDX_TOWNAREA_KANA];
            $mergedRow = $row;
        }
        $mergedTownArea  =implode('',$mergeTownArea);
        $mergedTownAreaKana  =implode('',$mergeTownAreaKana);
        $mergedRow[ZipCodeConstants::IDX_TOWNAREA_KANA] = $mergedTownAreaKana;
        $mergedRow[ZipCodeConstants::IDX_TOWNAREA]      = $mergedTownArea;
        return $mergedRow;
    }
    public function mergeTownAreaKana(){

    }


}
