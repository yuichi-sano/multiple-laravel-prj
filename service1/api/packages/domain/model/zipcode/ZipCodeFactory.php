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
     * @param  array $row zipcode
     * @return bool       分割要否
     */
    public function needSplit(array $row): bool
    {
        return (bool)preg_match($this->regexSeparateTownArea, $row[8]);
    }

    /**
     * 町域カナの分割要否を判定する
     * @param  array $row zipcode
     * @return bool       分割要否
     */
    private function needSplitKana(array $row): bool
    {
        return (bool)preg_match($this->regexSeparateTownAreaKana, $row[5]);
    }

    /**
     * 単一のレコードを複数に分割する
     * 仕様の詳細は、同ディレクトリ配下にあるREADME.mdを参照してください
     * @param  array $row zipcode
     * @return array      分割したzipcodeの配列
     */
    public function splitRow(array $row): array
    {
        /* 分割したレコードに設定する値を抽出 */
        $mainTownArea = $this->extractMain($this->regexBeforeParentheses, $row[8]);
        $subTownAreas = $this->extractSub($this->regexInsideParentheses, $row[8], '、');

        $mainTownAreaKana = '';
        $subTownAreaKanas = [];
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
     * @param  string $regex 正規表現
     * @param  string $str   抽出対象の町域情報
     * @return string        抽出したメインの町域
     */
    private function extractMain(string $regex, string $str): string
    {
            preg_match($regex, $str, $matchedArray);
            return $matchedArray[0];
    } 
    /**
     * 従属する町域（カナ）を抽出
     * @param  string $regex 正規表現
     * @param  string $str   抽出対象の町域情報
     * @return array         抽出した従属する町域の配列
     */
    private function extractSub($regex, $str, $separator): array
    {
        preg_match($regex, $str, $matchedArray);
        $subArray = explode($separator, $matchedArray[0]);
        // 分割するレコードの一つに、従属する町名を含めないものが必要になる
        array_unshift($subArray, '');
        return $subArray;
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
            if($attributeIndex != 8 && $attributeIndex != 5){
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
