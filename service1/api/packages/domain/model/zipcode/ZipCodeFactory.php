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
    protected string $regexSerialTownArea = "/.*〜.*/";
    protected string $regexUseInParentheses = "/[０-９]+区/u";
    protected string $regexUseInParenthesesKana =  "/[0-9]+ｸ/u";
    protected string $regexIgnoreInParentheses =  "/[０-９]|^その他$|^丁目$|^番地$|^地階・階層不明$|[０-９]*地割|成田国際空港内|次のビルを除く|^全域$/u";
    protected array  $regexDeprecatedPatternCollection = array("/抜海村バッカイ/u");

    // 町域（カナ）の分割パターン
    protected int $ptnNotApplicable = 0;
    protected int $ptnMainSub       = 1;
    protected int $ptnDoubleMain    = 2;
    protected int $ptnSerialMain    = 3;

    // 元データ(ken_all.csv)の属性に紐付いたインデックス
    protected int $idxTownAreaKana = 5;
    protected int $idxTownArea     = 8;

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
        $hasSerial = (bool)preg_match(
            $this->regexSerialTownArea,
            $row[$this->idxTownArea]
        );

        $hasSeparater = (bool)preg_match(
            $this->regexSeparateTownArea,
            $row[$this->idxTownArea]
        );
        /* TODO 
        preg_match(
            $this->regexParentheses,
            $row[$this->idxTownArea],
            $parents
        );
        // 括弧が2つにつき1組の従属する町域のグループ
        $subTownAreaNum = count($parents) / 2;

        // 従属する町域のグループが2つ以上存在する場合がある
        // その場合は分割を自動で行えないため、対象から除外する
        return ($hasSerial || $hasSeparater) &&$subTownAreaNum < 2;
        */

        // 連番と'、'区切りが同時に含まれているものは分割の対象外とする
        return !($hasSerial && $hasSeparater);
    }

    /**
     * 町域カナの分割要否を判定する
     * @param  string  $townAreaKana 町域カナ情報
     * @return bool                  分割要否
     */
    private function needSplitKana(string $townAreaKana): bool
    {
        return (bool)preg_match(
            $this->regexSeparateTownAreaKana,
            $townAreaKana
        );
    }

    /**
     * 単一のレコードを複数に分割する
     * 仕様の詳細は、同ディレクトリ配下にあるREADME.mdを参照してください
     * @param  array $row zipcode
     * @return array      分割したzipcodeの配列
     */
    public function splitRow(array $row): array
    {

        $splittedTownAreas = $this->splitTownArea(
            $row[$this->idxTownArea],
            $row[$this->idxTownAreaKana]
        );

        $splittedRows = [];
        foreach($splittedTownAreas['townArea'] as $index => $townArea){

            $splittedRow = $this->generateTemplateRow($row);

            $splittedRow[$this->idxTownArea]     = $townArea;
            $splittedRow[$this->idxTownAreaKana] = $splittedTownAreas['townAreaKana'][$index];

            $splittedRows[] = $splittedRow;
        }
        return $splittedRows;
    }

    /**
     * 町域（カナ）を複数に分割する
     * それぞれの分割パターンに応じた町域（カナ）の分割メソッドを呼び出す
     * @param  string $townArea     元データの町域情報
     * @param  string $townAreaKana 元データの町域カナ情報
     * @return array                分割した町域（カナ）情報
     */
     private function splitTownArea(string $townArea, string $townAreaKana): array
     {
        switch ($this->analyzeSplitPattern($townArea)) {
            case $this->ptnMainSub:
                return $this->splitMainSub($townArea, $townAreaKana);
                break;
            case $this->ptnDoubleMain:
                return $this->splitDoubleMain($townArea, $townAreaKana);
                break;
        }
    }

    /**
     * 町域（カナ）を複数に分割する主・従属パターン
     * @param  string $townArea     元データの町域情報
     * @param  string $townAreaKana 元データの町域カナ情報
     * @return array                分割した町域（カナ）情報
     */
    private function splitMainSub(string $townArea, string $townAreaKana): array
    {
        /* 町域（カナ）の抽出 */
        $mainTownArea = $this->extractMain(
            $this->regexBeforeParentheses,
            $townArea
        );

        $subTownAreas = $this->extractSub(
            $this->regexInsideParentheses,
            $townArea,
            '、'
        );

        // 分割が必要なレコードでも、従属する町域カナは複数存在せず単一の場合がある
        $mainTownAreaKana = $this->needSplitKana($townAreaKana)?
            $this->extractMain($this->regexBeforeParenthesesKana, $townAreaKana):
            $townAreaKana;

        $subTownAreaKanas = $this->needSplitKana($townAreaKana)?
            $this->extractSub($this->regexInsideParenthesesKana, $townAreaKana, '､'):
            null;

        /* 町域（カナ）の加工 */
        $processed = ['townArea' => [], 'townAreaKana' => []];
        foreach($subTownAreas as $index => $subTownArea){

            $processed['townArea'][]     = $mainTownArea . $subTownArea;
            //分割処理の共通化の都合で、単一の値を配列に格納する場合がある
            $processed['townAreaKana'][] = is_null($subTownAreaKanas)?
                $mainTownAreaKana:
                $mainTownAreaKana . $subTownAreaKanas[$index];

        }
        return $processed;
    }
    /**
     * 町域（カナ）を複数に分割する2つの主パターン
     * @param  string $townArea     元データの町域情報
     * @param  string $townAreaKana 元データの町域カナ情報
     * @return array                分割した町域（カナ）情報
     */
    private function splitDoubleMain(string $townArea, string $townAreaKana): array
    {
        $processed['townArea']     = explode('、', $townArea);
        $processed['townAreaKana'] = explode('､', $townAreaKana);

        return $processed;
    }

    /**
     * 町域（カナ）情報がどの分割パターンか判定する
     * @param  string $ownArea
     * @return int    分割パターン
     */
    private function analyzeSplitPattern(string $townArea): int
    {
        $pattern = $this->ptnNotApplicable;

        // 主・従属パターン
        if($this->isMainSub($townArea)) {
            return $this->ptnMainSub;
        }
        // 2つの主パターン
        if($this->isDoubleMain($townArea)) {
            return $this->ptnDoubleMain;
        }

        if($pattern === $this->ptnNotApplicable) {
            abort(500, '無効なデータの検出');
        }
    }

    /**
     * 町域が主・従属パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             主・従属パターン
     */
    private function isMainSub(string $townArea): bool
    {
        $hasMain   = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasParent = (bool)preg_match($this->regexParentheses, $townArea);

        // $parents の要素数が2 -> 括弧が1組のみ存在する
        return $hasMain && $hasParent;

    }

    /**
     * 町域が2つの主パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             2つの主パターン
     */
    private function isDoubleMain(string $townArea): bool
    {
        $hasSub      = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasParent   = (bool)preg_match($this->regexParentheses, $townArea);
        $townAreaNum = count(explode('、', $townArea));

        return !$hasSub && !$hasParent && $townAreaNum === 2;

    }
    /**
     * 従属する町域のグループが1つだけか判定する
     * グループが2つ以上存在する場合分割の対象外となる
        preg_match($this->regexParentheses, $townArea, $parents);

        // $parents の要素数が2 -> 括弧が1組のみ存在する
        return $hasMain && count($parents) === 2;

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
