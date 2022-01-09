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
    protected string $regexBeforeSerialTownArea = "/(.*)(?=〜)/u";
    protected string $regexBeforeSerialTownAreaKana = "/(.*)(?=-)/u";
    protected string $regexAfterSerialTownArea = "/(?<=〜).*/u";
    protected string $regexAfterSerialTownAreaKana = "/(?<=-).*/u";
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
        dd($splittedRows);
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
            case $this->ptnSerialMain:
                return $this->splitSerialMain($townArea, $townAreaKana);
                break;
        }
    }

    /**
     * 町域（カナ）を複数に分割する -> 主・従属パターン
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
     * 町域（カナ）を複数に分割する -> 2つの主パターン
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
     * 町域（カナ）を複数に分割する -> 連番の主パターン
     * `〇〇n地割〜〇〇m地割`といったようなデータに対して、
     * nとmの間の数値を生成して、それぞれ独立した町域として分割する
     * @param  string $townArea     元データの町域情報
     * @param  string $townAreaKana 元データの町域カナ情報
     * @return array                分割した町域（カナ）情報
     */
    private function splitSerialMain(string $townArea, string $townAreaKana): array
    {
        /* 連番の始点と終点の抽出 */
        $serial = $this->extractSerialStartAndEnd($townAreaKana);

        /* 連番の前後の町域名称（カナ）を抽出 */
        preg_match($this->regexBeforeSerialTownArea, $townArea, $town);
        $townArea = $this->exceptTownNumber($town[0]);

        preg_match($this->regexBeforeSerialTownAreaKana, $townAreaKana, $town);
        $townAreaKana = $this->exceptTownNumber($town[0]);


        /* 抽出した情報を元に連番の町域を生成 */
        return $this->generateTownAreaStartToEnd(
            (int)$serial['start'],
            (int)$serial['end'],
            $townArea['before'],
            $townArea['after'],
            $townAreaKana['before'],
            $townAreaKana['after'],
        );
    }

    /**
     * 町域名称カナから連番の始点と終点を抽出する 
     * @param  string $townAreaKana 町域カナ情報
     * @return array                連番の始点と終点
     */
    private function extractSerialStartAndEnd(string $townAreaKana): array
    {
        preg_match($this->regexBeforeSerialTownAreaKana, $townAreaKana, $start);
        preg_match("/\d{1,}/u", $start[0], $start);

        preg_match($this->regexAfterSerialTownAreaKana, $townAreaKana, $end);
        preg_match("/\d{1,}/u", $end[0], $end);

        return ['start' => $start[0], 'end' => $end[0]];
    }

    /**
     * 町域名称（カナ）から連番を除いた前後の情報を抽出する 
     * @param  string $townAreaKana 町域（カナ）情報
     * @return array                連番の始点と終点
     */
    private function exceptTownNumber(string $townAreaKana): array
    {
        $excepted = [];

        // 町域名称     → 全角数字使用
        // 町域名称カナ → 半角数字使用
        if((bool)preg_match("/[0-9]+/u", $townAreaKana)){
            preg_match_all("/[^0-9]+/u", $townAreaKana, $excepted);
        } else {
            preg_match_all("/[^０-９]+/u", $townAreaKana, $excepted);
        }

        return ['before' => $excepted[0][0], 'after' => $excepted[0][1]];
    }

    /**
     * 連番の始点から終点までの町域名称（カナ）を生成
     * @param  int    $start              連番の始点
     * @param  int    $end                連番の終点
     * @param  string $townAreaBefore     町域名称_連番の前
     * @param  string $townAreaAfter      町域名称_連番の後
     * @param  string $townAreaKanaBefore 町域名称カナ_連番の前
     * @param  string $townAreaKanaAfter  町域名称カナ_連番の後
     * @return array                      生成した町域（カナ）
     */
    private function generateTownAreaStartToEnd(
        int    $start,
        int    $end,
        string $townAreaBefore,
        string $townAreaAfter,
        string $townAreaKanaBefore,
        string $townAreaKanaAfter
    ): array
    {
        $serialed = ['townArea' => [], 'townAreaKana' => []];
        for($townNum = $start; $townNum <= $end; $townNum++) {

            $serialed['townArea'][]     = $townAreaBefore
                                            . mb_convert_kana((string)$townNum, 'A')
                                            . $townAreaAfter;

            $serialed['townAreaKana'][] = $townAreaKanaBefore
                                            . $townNum
                                            . $townAreaKanaAfter;
        }
        return $serialed;
    }

    /**
     * 町域（カナ）情報がどの分割パターンか判定する
     * @param  string $townArea
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
        // 連番の主パターン
        if($this->isSerialMain($townArea)) {
            return $this->ptnSerialMain;
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
     * 町域が2つの主パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             2つの主パターン
     */
    private function isSerialMain(string $townArea): bool
    {
        $hasSub    = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasParent = (bool)preg_match($this->regexParentheses, $townArea);
        $hasSerial = (bool)preg_match($this->regexSerialTownArea, $townArea);

        return !$hasSub && !$hasParent && $hasSerial;

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
