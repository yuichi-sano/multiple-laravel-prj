<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

class ZipCodeFactory
{

    protected string $regexUnClosedParentheses = "/（.*[^）]$/u";
    protected string $regexUnClosedParenthesesKana = "/\\(.*[^\\)]$/u";
    protected string $regexClosedParentheses = "/.*.）$/u";
    protected string $regexClosedParenthesesKana = "/.*.\\)$/u";
    protected string $regexIgnore = "/以下に掲載がない場合|(市|町|村)の次に番地がくる場合|(市|町|村)一円/u";
    protected string $regexFloor = "/（([０-９]+階)）/u";
    protected string $regexFloorKana = "/\\(([0-9]+ｶｲ)\\)/u";
    protected string $regexJiwari = "/^([^０-９第（]+)|[第]*[０-９]+地割.*/u";
    protected string $regexJiwariKana = "/^([^0-9\\(]+)|(ﾀﾞｲ)*[0-9(]*ﾁﾜﾘ.*/u";
    protected string $regexParentheses = "/（(.*)）/u";
    protected string $regexParenthesesKana = "/\\((.*)\\)/u";
    protected string $regexBeforeParentheses = "/(.*)(?=（)/u";
    protected string $regexBeforeParenthesesKana = "/(.*)(?=\()/u";
    protected string $regexInsideParentheses = "/(?<=（).*?(?=）)/u";
    protected string $regexInsideParenthesesKana = "/(?<=\().*?(?=\))/u";
    protected string $regexSeparateTownArea = "/.*、.*/";
    protected string $regexSeparateTownAreaKana = "/.*､.*/";
    protected string $regexUseInParentheses = "/[０-９]+区/u";
    protected string $regexUseInParenthesesKana =  "/[0-9]+ｸ/u";
    protected string $regexIgnoreInParentheses =  "/[０-９]|^その他$|^丁目$|^番地$|^地階・階層不明$|[０-９]*地割|成田国際空港内|次のビルを除く|^全域$/u";
    protected array  $regexDeprecatedPatternCollection = array("/抜海村バッカイ/u");


    //row[8]=町域名称,row[5]=町域名称カナ
    public function create($row): ZipCode
    {
        return  new ZipCode(
            $row[0],$row[1],$row[2],$row[3],$row[4]
            ,$this->cleanTownAreaKana($row[8],$row[5])
            ,$row[6],$row[7]
            ,$this->cleanTownArea($row[8])
            ,$row[9],$row[10],$row[11],$row[12],$row[13],$row[14]
        );
    }
    public function cleanTownArea($townArea){
        $cleanTownArea= $townArea;
        if((bool)preg_match($this->regexIgnore, $townArea)){
            $cleanTownArea = '';
        };
        if((bool)preg_match($this->regexFloor, $townArea)){
            $cleanTownArea = preg_replace($this->regexFloor, '', $townArea);
        }

        /*
        if((bool)preg_match($this->regexJiwari, $townArea)){
            $cleanTownArea = preg_replace($this->regexJiwari, '', $townArea);
        };
        */
        //if((bool)preg_match($this->regexParentheses, $townArea)){
        //    $cleanTownArea = preg_replace($this->regexParentheses, '', $cleanTownArea);
        //};

        return $cleanTownArea;
    }
    public function cleanTownAreaKana($townArea,$townAreaKana){
        $cleanTownAreaKana= $townAreaKana;
        if((bool)preg_match($this->regexIgnore, $townArea)){
            $cleanTownAreaKana = '';
        };
        if((bool)preg_match($this->regexFloorKana, $townAreaKana)){
            $cleanTownAreaKana = preg_replace($this->regexFloorKana, '', $townAreaKana);
        };
        /*
        if((bool)preg_match($this->regexJiwariKana, $townAreaKana)){
            $cleanTownAreaKana = preg_replace($this->regexJiwariKana, '', $townAreaKana);
        };
        */
        //if((bool)preg_match($this->regexParentheses, $townArea)){
        //    $cleanTownArea = preg_replace($this->regexParentheses, '', $cleanTownArea);
        //};

        return $cleanTownAreaKana;


    }

    public function isUnClose($row): bool
    {
        return (bool)preg_match($this->regexUnClosedParenthesesKana, $row[5]) || (bool)preg_match($this->regexUnClosedParentheses, $row[8]);
    }
    public function isClose($row){
        return (bool)preg_match($this->regexClosedParenthesesKana, $row[5]) || (bool)preg_match($this->regexClosedParentheses, $row[8]);
    }

    /**
     * レコードの分割要否を判定する
     * @return bool
     */
    public function needSplit($row): bool
    {
        return (bool)preg_match($this->regexSeparateTownArea, $row[8]);
    }

    /**
     * 町域カナの分割要否を判定する
     * @return bool
     */
    private function needSplitKana($row): bool
    {
        return (bool)preg_match($this->regexSeparateTownAreaKana, $row[5]);
    }

    /**
     * レコードに記載された、従属する町域の数だけレコードを分割する
     * 分割する対象は、レコードの町域（カナ）属性の値が下記の形式もの
     * → メイン町域（従属する町域1、 従属する町域2、 ...従属する町域n）
     * 従属する町域ごとに、個別のレコードへ分割を行う
     * @return array
     */
    public function splitRow($row): array
    {
        /* 分割したレコードに設定する値を抽出 */
        $mainTownArea = $this->extractMain($this->regexBeforeParentheses, $row[8]);
        $subTownAreas = $this->extractSub($this->regexInsideParentheses, $row[8], '、');

        $mainTownAreaKana = '';
        $subTownAreaKana = [];
        // 分割が必要なレコードでも、従属する町域カナは複数存在せず単一の場合がある
        if($this->needSplitKana($row)) {
            $mainTownAreaKana = $this->extractMain($this->regexBeforeParenthesesKana, $row[5]);
            $subTownAreaKanas = $this->extractSub($this->regexInsideParenthesesKana, $row[5], '､');
        } else {
            $mainTownAreaKana = $row[5];
            $subTownAreaKanas = null;
        }

        /* 抽出した値を元にレコードを分割 */
        $splittedRows = [];
        foreach($subTownAreas as $areaIndex => $subTownArea){
            $splittedRow = $this->generateTemplateRow($row);

            // レコード分割後の町域属性の値は、メインの町域と従属する町域を連結したものになる
            $splittedRow[8] = $mainTownArea . $subTownArea;

            // レコード分割後の町域カナ属性の値は、従属する町域カナが存在する時だけ連結する
            if($this->needSplitKana($row)){
                $splittedRow[5] = $mainTownAreaKana . $subTownAreaKanas[$areaIndex];
            } else {
                $splittedRow[5] = $mainTownAreaKana;
            }

            $splittedRows[] = $splittedRow;
        }
        return $splittedRows;
    }

    /**
     * メインの町域（カナ）を抽出
     * @return string
     */
    private function extractMain($regex, $str): string
    {
            preg_match($regex, $str, $matchedArray);
            if(!(bool)preg_match($regex, $str, $matchedArray)){
                ddd($str. $regex);
            }
            return $matchedArray[0];
    } 

    /**
     * 従属する町域（カナ）を抽出
     * @return array
     */
    private function extractSub($regex, $str, $separator): array
    {
        preg_match($regex, $str, $matchedArray);
        return explode($separator, $matchedArray[0]);
    }

    /**
     * 分割するレコードのテンプレートを生成する
     * @return array
     */
    private function generateTemplateRow($row): array
    {
        $samePeaceRow = [];
        foreach($row as $attributeIndex => $attribute){
            // レコード内の、町域属性と町域カナ属性以外は同一の値となる
            if($attributeIndex != 8 && $attributeIndex != 5){
                $samePeaceRow[$attributeIndex] = $attribute;
            } else {
                $samePeaceRow[$attributeIndex] = '';
            }
        }
        return $samePeaceRow;
    }

    public function isDeprecated(){

    }

    public function mergeRows(array $mergeRows){
        $mergeTownArea=[];
        $mergeTownAreaKana=[];
        $mergedRow = [];
        foreach ($mergeRows as $row){
            $mergeTownArea[] = $row[8];
            $mergeTownAreaKana[] = $row[5];
            $mergedRow = $row;
        }
        $mergedTownArea  =implode('',$mergeTownArea);
        $mergedTownAreaKana  =implode('',$mergeTownAreaKana);
        $mergedRow[5] = $mergedTownAreaKana;
        $mergedRow[8] = $mergedTownArea;
        return $mergedRow;
    }
    public function mergeTownAreaKana(){

    }


}
