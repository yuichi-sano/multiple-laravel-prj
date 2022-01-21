<?php
/*
 * 再利用出来るようにしたい
 * StringSplit interface
 *  merge rows implements
 *
 * 定数の定義の仕方
 *  コードの分割
 *
 *  create cleaTownArea
 *  コード の概要
 *  ちゃんと見る
 *
 *  コメントアウト消す
 *  種市けす
 *  テストコード書く
 *
 *  WebApiExceptionにする
 *
 */
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
    protected string $regexBeforeParenthesesKana = "/[^\(]+(?=\()/u";
    protected string $regexInsideParentheses = "/(?<=（).*(?=）)/u";
    protected string $regexInsideParenthesesKana = "/(?<=\().*(?=\))/u";
    protected string $regexSeparateTownArea = "/.*、.*/u";
    protected string $regexSeparateTownAreaKana = "/.*､.*/u";
    protected string $regexSerialTownArea = "/.*[～〜].*/u";
    protected string $regexBeforeSerialTownArea = "/(.*)(?=[～〜])/u";
    protected string $regexBeforeSerialTownAreaKana = "/(.*)(?=-)/u";
    protected string $regexAfterSerialTownArea = "/(?<=[～〜]).*/u";
    protected string $regexAfterSerialTownAreaKana = "/(?<=-)[^-]+/u";
    protected string $regexSerialBanchiGou= "/(?<=[～〜])[０-９]+[－−][０-９]+/u";
    protected string $regexUseInParentheses = "/[０-９]+区/u";
    protected string $regexUseInParenthesesKana =  "/[0-9]+ｸ/u";
    protected string $regexBullets = "/.*・.*/u";
    protected string $regexKeyBrackets = "/.*「.*」.*/u";
    protected string $regexIgnoreInParentheses =  "/[０-９]|^その他$|^丁目$|^番地$|^地階・階層不明$|[０-９]*地割|成田国際空港内|次のビルを除く|^全域$/u";
    protected string $regexUnits = "/地割|丁目|番地|区|線|条/u";
    protected string $regexNotSerialEndNumber = "/[０-９]+(((番地)?[～〜][、）])+|((番地)?[～〜]$))/u";
    protected string $regexNumber = "/[０-９]+/u";
    protected string $regexNumberHalf = "/[0-9]+/u";
    protected string $regexNotNumber = "/[^０-９]+/u";
    protected string $regexNotNumberHalf = "/[^0-9]+/u";
    protected array  $regexDeprecatedPatternCollection = array("/抜海村バッカイ/u");

    // 町域（カナ）の分割パターン
    protected int $ptnMainSub           = 1;
    protected int $ptnMultiMain         = 2;
    protected int $ptnSerialMain        = 3;
    protected int $ptnMainMultiUnit     = 4;
    protected int $ptnMainSerialUnit    = 5;
    protected int $ptnMainSubSerialUnit = 6;
    protected int $ptnSerialMainSub     = 7;

    // 元データ(ken_all.csv)の属性に紐付いたインデックス
    protected int $idxJis                      = 0;
    protected int $idxZipCode5                 = 1;
    protected int $idxZipCode                  = 2;
    protected int $idxPrefectureKana           = 3;
    protected int $idxCityKana                 = 4;
    protected int $idxTownAreaKana             = 5;
    protected int $idxPrefecture               = 6;
    protected int $idxCity                     = 7;
    protected int $idxTownArea                 = 8;
    protected int $idxIsOneTownByMultiZipCode  = 9;
    protected int $idxIsNeedSmallAreaAddress   = 10;
    protected int $idxIsChoume                 = 11;
    protected int $idxIsMultiTownByOnePostCode = 12;
    protected int $idxUpdated                  = 13;
    protected int $idxUpdateReason             = 14;

    public function create($row): ZipCode
    {
        return  new ZipCode(
            $row[$this->idxJis],
            $row[$this->idxZipCode5],
            $row[$this->idxZipCode],
            $row[$this->idxPrefectureKana],
            $row[$this->idxCityKana],
            $this->cleanTownAreaKana(
                $row[$this->idxTownArea],
                $row[$this->idxTownAreaKana]
            ),
            $row[$this->idxPrefecture],
            $row[$this->idxCity],
            $this->cleanTownArea($row[$this->idxTownArea]),
            $row[$this->idxIsOneTownByMultiZipCode],
            $row[$this->idxIsNeedSmallAreaAddress],
            $row[$this->idxIsChoume],
            $row[$this->idxIsMultiTownByOnePostCode],
            $row[$this->idxUpdated],
            $row[$this->idxUpdateReason],
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
        return (bool)preg_match($this->regexUnClosedParenthesesKana, $row[$this->idxTownAreaKana]) || (bool)preg_match($this->regexUnClosedParentheses, $row[$this->idxTownArea]);
    }
    public function isClose($row){
        return (bool)preg_match($this->regexClosedParenthesesKana, $row[$this->idxTownAreaKana]) || (bool)preg_match($this->regexClosedParentheses, $row[$this->idxTownArea]);
    }

    /**
     * 町域名称の値を元にレコードの分割要否を判定する
     * @param  array $row zipcode
     * @return bool       分割要否
     */
    public function needSplit(array $row): bool
    {
        $townArea = $row[$this->idxTownArea];

        $hasMain   = (bool)preg_match(
            $this->regexBeforeParentheses,
            $townArea
        );
        $hasSerial = (bool)preg_match(
            $this->regexSerialTownArea,
            $townArea
        );
        $hasSeparater = (bool)preg_match(
            $this->regexSeparateTownArea,
            $townArea
        );

        if($hasMain && $hasSerial && $hasSeparater) {
            // 主の町域と従属する町域と連続する町域が存在する際に、
            // 主の町域が連続する町域であった場合は分割の対象外となる
            // 例：種市第１５地割～第２１地割（鹿糠、小路合、緑町、大久保、高取）
            return !(bool)preg_match(
                $this->regexSerialTownArea,
                $this->extractMatch(
                    $this->regexBeforeParentheses,
                    $townArea
                )
            );
        }

        if($hasSerial){
            //連番の町域であり、かつ始点と終点に異なる番地を保持している場合は、
            //分割の対象外となる 例: ２４３０－１～２４３１－７５
            $hasSerialBanchiGou = (bool)preg_match(
                $this->regexSerialBanchiGou,
                $townArea
            );

            //〜のあとに町域情報が存在しないものは分割の対象外となる
            // 例：大前（細原２２５９〜）
            $hasNotSerialEnd = (bool)preg_match(
                $this->regexNotSerialEndNumber,
                $townArea
            );

            // $hasSerialBanchiGou, $hasNotSerialEndに該当するのが
            // 複数まとめられた町域のうちの1つであれば分割の対象となる
            return (!$hasSerialBanchiGou && !$hasNotSerialEnd) ||
                   count(explode('、', $townArea)) > 1;
        }

        return $hasSerial || $hasSeparater;
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
     * 実際に分割を行うのは町域名称（カナ）の値のみでそれ以外の属性値は変わらない
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
     * それぞれの分割パターンに応じた町域名称（カナ）の分割メソッドを呼び出す
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
            case $this->ptnMultiMain:
                return $this->splitMultiMain($townArea, $townAreaKana);
                break;
            case $this->ptnSerialMain:
                return $this->splitSerialMain($townArea, $townAreaKana);
                break;
            case $this->ptnMainMultiUnit:
                return $this->splitMainMultiUnit($townArea, $townAreaKana);
            case $this->ptnMainSerialUnit:
                return $this->splitMainSerialUnit($townArea, $townAreaKana);
                break;
            case $this->ptnMainSubSerialUnit:
                return $this->splitMainSubSerialUnit($townArea, $townAreaKana);
                break;
            case $this->ptnSerialMainSub:
                return $this->splitSerialMainSub($townArea, $townAreaKana);
                break;
            default :
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
        $mainTownArea = $this->extractMatch(
            $this->regexBeforeParentheses,
            $townArea
        );

        $subTownAreas = $this->extractMatchArray(
            $this->regexInsideParentheses,
            $townArea,
            '、'
        );

        // 分割するレコードの一つに、従属する町名を含めないものが必要になる
        array_unshift($subTownAreas, '');

       $mainTownAreaKana = '';
       if((bool)preg_match($this->regexBeforeParenthesesKana, $townAreaKana)) {
            $mainTownAreaKana = $this->extractMatch(
                $this->regexBeforeParenthesesKana,
                $townAreaKana
            );
        } else {
            $mainTownAreaKana = $townAreaKana;
        }

        $subTownAreaKanas = [];
        if($this->needSplitKana($townAreaKana)) {
            $subTownAreaKanas = $this->extractMatchArray(
                $this->regexInsideParenthesesKana,
                $townAreaKana,
                '､'
            );
        } elseif((bool)preg_match($this->regexInsideParenthesesKana, $townAreaKana)) {
            // 分割が必要なレコードでも、従属する町域カナは複数存在せず単一の場合がある
            $subTownAreaKanas = [
                $this->extractMatch($this->regexInsideParenthesesKana, $townAreaKana)
            ];
        } else {
            // そもそも存在しない場合もある
            $subTownAreaKanas = null;
        }

        // 分割するレコードの一つに、従属する町名を含めないものが必要になる
        if(!is_null($subTownAreaKanas)){
            array_unshift($subTownAreaKanas, '');
        }

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
    private function splitMultiMain(string $townArea, string $townAreaKana): array
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
        // もしカッコがついていたら不要なので外す
        if((bool)preg_match($this->regexInsideParentheses, $townArea)) {
            $townArea = $this->extractMatch(
                $this->regexInsideParentheses,
                $townArea
            );
        }
        if((bool)preg_match($this->regexInsideParenthesesKana, $townAreaKana)) {
            $townAreaKana = $this->extractMatch(
                $this->regexInsideParenthesesKana,
                $townAreaKana
            );
        }

        /* 連番の前後の町域名称（カナ）を抽出 */
        $townAreaBefore = $this->extractBeforeNum(
            $this->extractMatch(
                $this->regexBeforeSerialTownArea,
                $townArea
            )
        );
        $townAreaAfter = $this->extractAfterNum(
            $this->extractMatch(
                $this->regexAfterSerialTownArea,
                $townArea
            )
        );
        $townAreaBeforeKana = $this->extractBeforeNum(
            $this->extractMatch(
                $this->regexBeforeSerialTownAreaKana,
                $townAreaKana
            )
        );
        $townAreaAfterKana = $this->extractAfterNum(
            $this->extractMatch(
                $this->regexAfterSerialTownAreaKana,
                $townAreaKana
            )
        );

        /* 連番の始点と終点の抽出 */
        $serial = $this->extractSerialStartAndEnd($townAreaKana);

        /* 抽出した情報を元に連番の町域を生成 */
        return $this->generateTownAreaStartToEnd(
            (int)$serial['start'],
            (int)$serial['end'],
            $townAreaBefore,
            $townAreaAfter,
            $townAreaBeforeKana,
            $townAreaAfterKana,
        );
    }

    /**
     * 町域名称カナから連番の始点と終点を抽出する 
     * @param  string $townArea     町域情報
     * @return array                連番の始点と終点
     */
    private function extractSerialStartAndEnd(string $townAreaKana): array
    {

        $start = $this->extractMatch(
            $this->regexBeforeSerialTownAreaKana,
            $townAreaKana
        );
        // 稀に1-97-116といったような引数が来た場合に97-116が連番に該当する
        // その際は単純な数値の抽出では対応できない
        if((bool)preg_match($this->regexAfterSerialTownAreaKana, $start)) {
            $start = $this->extractMatch(
                $this->regexAfterSerialTownAreaKana,
                $start
            );
        } else{
            // こちらに該当するものが大半
            $start = $this->extractMatch(
                $this->regexNumberHalf,
                $start
            );
        }
        // 稀に1-97-116といったような引数が来た場合にpreg_matchでは97しか抽出ができない
        preg_match_all($this->regexAfterSerialTownAreaKana, $townAreaKana, $end);
        $end = $this->extractMatch(
            $this->regexNumberHalf,
            $end[0][count($end[0])-1]
        );

        return ['start' => $start, 'end' => $end];
    }

    /**
     * 渡された引数に半角数字が使用されているか判定する
     * @param  string $str 判定する文字列
     * @return bool        半角数字の有無
     */
    private function hasHalfSizeNum(string $str): bool
    {
        return (bool)preg_match($this->regexNumberHalf, $str);
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
     * 町域（カナ）を複数に分割する -> 主と複数の住所単位パターン
     * `〇〇（n,m丁目）`といったようなデータに対して、
     * nに住所単位を付与してそれぞれ独立した町域情報に分割する
     * @param  string $townArea     元データの町域情報
     * @param  string $townAreaKana 元データの町域カナ情報
     * @return array                分割した町域（カナ）情報
     */
    private function splitMainMultiUnit(string $townArea, string $townAreaKana): array
    {
        /* 町域（カナ）の抽出 */
        $mainTownArea = $this->extractMatch(
            $this->regexBeforeParentheses,
            $townArea
        );
        $subTownAreas = $this->extractMatchArray(
            $this->regexInsideParentheses,
            $townArea,
            '、'
        );

        $mainTownAreaKana = $this->extractMatch(
            $this->regexBeforeParenthesesKana,
            $townAreaKana
        );
        $subTownAreaKanas = $this->extractMatchArray(
            $this->regexInsideParenthesesKana,
            $townAreaKana,
            '､'
        );

        /* 住所単位の抽出 */
        $lastIndex = count($subTownAreas)-1;

        $unitName     = $this->extractAfterNum($subTownAreas[$lastIndex]);
        $unitNameKana = $this->extractAfterNum($subTownAreaKanas[$lastIndex]);

        /* 抽出した住所単位をすべての要素に追加する */
        $processed = ['townArea' => [], 'townAreaKana' => []];
        foreach($subTownAreas as $index => $subTownArea){
            if($index < $lastIndex) {
                $processed['townArea'][]     = $mainTownArea
                                              . $subTownArea
                                              . $unitName;
                $processed['townAreaKana'][] = $mainTownAreaKana
                                              . $subTownAreaKanas[$index]
                                              . $unitNameKana;
            } else {
                $processed['townArea'][]     = $mainTownArea
                                              . $subTownArea;
                $processed['townAreaKana'][] = $mainTownAreaKana
                                              . $subTownAreaKanas[$index];
            }
        }
        return $processed;
    }

    /**
     * 町域（カナ）を複数に分割する -> 主と連続した住所単位パターン
     * `〇〇（✗✗n〜m丁目）`といったようなデータに対して、
     * nとmの間の数値を生成した上で住所単位を付与して独立した町域情報に分割する
     * @param  string $townArea     元データの町域名称
     * @param  string $townAreaKana 元データの町域カナ名称
     * @return array                分割した町域（カナ）名称
     */
    private function splitMainSerialUnit(string $townArea, string $townAreaKana): array
    {
        /* 町域名称（カナ）を構成する各情報を抽出する */

        // 主の町域名称（カナ）
        $mainTownArea     = $this->extractMatch(
            $this->regexBeforeParentheses,
            $townArea
        );
        $mainTownAreaKana = $this->extractMatch(
            $this->regexBeforeParenthesesKana,
            $townAreaKana
        );
        // カッコ内の情報
        $insideParentheses    = $this->extractMatch(
            $this->regexInsideParentheses,
            $townArea
        );
        $insideParenthesesKana = $this->extractMatch(
            $this->regexInsideParenthesesKana,
            $townAreaKana
        );

        // カッコ内の、従属する町域名称（カナ ）に連番と住所区分単位が存在
        // それらを全て抽出する必要がある

        // 町域名称（カナ）
        $subTownArea     = $this->extractBeforeNum(
            $this->extractMatch(
                $this->regexBeforeSerialTownArea,
                $insideParentheses
            )
        );
        $subTownAreaKana = $this->extractBeforeNum(
            $this->extractMatch(
                $this->regexBeforeSerialTownAreaKana,
                $insideParenthesesKana
            )
        );

        // 住所区分単位
        $unitName    = $this->extractAfterNum(
            $this->extractMatch(
                $this->regexAfterSerialTownArea,
                $insideParentheses
            )
        );
        $unitNameKana = $this->extractAfterNum(
            $this->extractMatch(
                $this->regexAfterSerialTownAreaKana,
                $insideParenthesesKana
            )
        );

        // 連番
        $serial = $this->extractSerialStartAndEnd($townAreaKana);

        /* 抽出した情報を元に連番の町域を生成 */
        return $this->generateTownAreaStartToEnd(
            (int)$serial['start'],
            (int)$serial['end'],
            $mainTownArea . $subTownArea,
            $unitName,
            $mainTownAreaKana . $subTownAreaKana,
            $unitNameKana,
        );
    }

    /**
     * 町域（カナ）を複数に分割する -> 連番の主と単一の従属町域パターン
     * 例：`〇〇n地割〜〇〇m地割（✗✗町）`
     * 例の✗✗町は連番の主のすべての町域を包括するため、nとmの間の数値を生成した上で
     * 末尾に（✗✗町）を付与してそれぞれ独立した町域として分割する
     * 連番の主パターンに処理を一部移譲する
     * @param  string $townArea     元データの町域情報
     * @param  string $townAreaKana 元データの町域カナ情報
     * @return array                分割した町域（カナ）情報
     */
    private function splitSerialMainSub(string $townArea, string $townAreaKana): array
    {
        /* 必要な情報の抽出 */
        $mainTownArea     = $this->extractMatch(
            $this->regexBeforeParentheses,
            $townArea);
        $mainTownAreaKana = $this->extractMatch(
            $this->regexBeforeParenthesesKana,
            $townAreaKana
        );

        $subTownArea     = $this->extractMatch(
            $this->regexParentheses,
            $townArea
        );
        $subTownAreaKana = $this->extractMatch(
            $this->regexParenthesesKana,
            $townAreaKana
        );

        /* 抽出した情報を元に連番の町域を生成 */
        // 連番生成処理の移譲
        $serialedMains = $this->splitSerialMain($mainTownArea, $mainTownAreaKana);

        for($i = 0; $i < count($serialedMains['townArea']); $i++) {
            $serialedMains['townArea'][$i]     .= $subTownArea;
            $serialedMains['townAreaKana'][$i] .= $subTownAreaKana;
        }

        return $serialedMains;
    }

    /**
     * 連番の町域名称（カナ）から連番を除いた町域名称情報を抽出する
     * @param  string $townArea 町域（カナ）名称
     * @return                  抽出した町域（カナ）名称
     */
    private function extractBeforeNum($townArea): string
    {
        $regCharBeforeSerial = "/\D(?=[０-９])/u";  //連番の直前の文字
        $regStrBeforeChar    = "/.*(?=\D[０-９])/u";//↑ の前の文字列
        // このメソッドは上記２つの正規表現にマッチした文字列を連結して返すが、
        // [^０-９]+のような正規表現を使用しないのは、稀に連番とそうじゃない整数を
        // 区別して返却する必要があるため。
        // 例： `１－９７`の、`１－`を抽出。`９７`は連番に該当するので削除する。

        $regCharBeforeSerialKana = "/\D(?=[0-9])/u";  //連番の直前の文字
        $regStrBeforeCharKana    = "/.*(?=\D[0-9])/u";//↑ の前の文字列
        $isExist                 = false;

        if($this->hasHalfSizeNum($townArea)){
            $charBeforeSerial = $this->extractMatch(
                $regCharBeforeSerialKana, $townArea
            );
            $strBeforeChar    = $this->extractMatch(
                $regStrBeforeCharKana,
                $townArea
            );

            $isExist = (bool)preg_match($regCharBeforeSerialKana, $townArea);

        } else {
            $charBeforeSerial = $this->extractMatch(
                $regCharBeforeSerial,
                $townArea
            );
            $strBeforeChar    = $this->extractMatch(
                $regStrBeforeChar,
                $townArea
            );

            $isExist = (bool)preg_match($regCharBeforeSerial, $townArea);
        }

        //従属する町域（カナ）が存在しない場合がある
        return $isExist? $strBeforeChar . $charBeforeSerial : '';
    }

    /**
     * 引数の数字部分の末尾に存在する文字列を抽出。存在しない場合は空文字を返却
     * このメソッドは、引数の例として`〇〇3丁目`の`丁目`部分を抽出する用途を想定
     * @param  string $str 抽出元の文字列
     * @return string      抽出した文字列
     */
    private function extractAfterNum(string $str): string
    {
        $extracted = '';
        if($this->hasHalfSizeNum($str)){
            $extracted = $this->extractMatch("/(?<=[0-9])[^0-9]+/u", $str);
        } else {
            $extracted = $this->extractMatch("/(?<=[０-９])[^０-９]+/u", $str);
        }
        return $extracted;
    }

    /**
     * 町域（カナ）を複数に分割する -> 主と従属と連続した住所単位パターン
     * `〇〇（✗✗n〜m丁目、△△町）`といったようなデータに対して、
     * nとmの間の数値を生成した上で住所単位を付与して独立した町域情報に分割する
     * 加えて従属した町域も独立した町域情報として分割する
     * 主・従属パターンと主の連続パターンの複合パターンのため実際の処理は移譲する
     * @param  string $townArea     元データの町域名称
     * @param  string $townAreaKana 元データの町域カナ名称
     * @return array                分割した町域（カナ）名称
     */
    private function splitMainSubSerialUnit(string $townArea, string $townAreaKana): array
    {
        /* 各情報の抽出 */
        $mainTownArea          = $this->extractMatch(
            $this->regexBeforeParentheses,
            $townArea
        );
        $mainTownAreaKana      = $this->extractMatch(
            $this->regexBeforeParenthesesKana,
            $townAreaKana
        );

        $insideParentheses     = $this->extractMatch(
            $this->regexInsideParentheses,
            $townArea,
            '、'
        );
        $insideParenthesesKana = $this->extractMatch(
            $this->regexInsideParenthesesKana,
            $townAreaKana,
            '､'
        );

        /* 移譲するメソッドごとに町域の振り分け */
        // カッコ内の従属町域 -> 連続町域（カナ）と複数町域（カナ）に分ける
        $subSerialTownAreas = ['townArea' => [], 'townAreaKana' => []];
        $subTownAreas       = ['townArea' => [], 'townAreaKana' => []];
        $subTownArray       = explode('、', $insideParentheses);
        $subTownArrayKanas  = explode('､', $insideParenthesesKana);

        foreach($subTownArray as $index => $subTownArea){

            $hasSerial = (bool)preg_match(
                $this->regexSerialTownArea,
                $subTownArea
            );

            $hasSerialBanchiGou = (bool)preg_match(
                $this->regexSerialBanchiGou,
                $subTownArea
            );

            if($hasSerial && !$hasSerialBanchiGou) {
                // true 連続町域 splitSerialMain
                $subSerialTownAreas['townArea'][]     = $subTownArea;
                $subSerialTownAreas['townAreaKana'][] = $subTownArrayKanas[$index];
            }
            else{
                // false 複数町域 splitMainSub
                $subTownAreas['townArea'][]     = $subTownArea;
                $subTownAreas['townAreaKana'][] = $subTownArrayKanas[$index];
            }

        }

        /* 分割処理の移譲 それぞれの移譲先の戻り値をマージして返却*/
        $processedTowns = ['townArea' => [], 'townAreaKana' => []];

        foreach($subSerialTownAreas['townArea'] as $index => $subSerialTownArea){

            $processedTowns = array_merge_recursive(
                $processedTowns,
                $this->splitSerialMain(
                    $mainTownArea . $subSerialTownArea,
                    $mainTownAreaKana . $subSerialTownAreas['townAreaKana'][$index]
                )
            );

        }

        $mainSub        = implode('、', $subTownAreas['townArea']);
        $mainSubKana    = implode('､', $subTownAreas['townAreaKana']);
        $processedTowns = array_merge_recursive(
            $processedTowns,
            $this->splitMainSub(
                $mainTownArea . '（' . $mainSub . '）',
                $mainTownAreaKana . '(' . $mainSubKana .')'
            )
        );

        return $processedTowns;
    }

    /**
     * 町域（カナ）情報がどの分割パターンか判定する
     * @param  string $townArea
     * @return int    分割パターン
     */
    private function analyzeSplitPattern(string $townArea): int
    {
        // 主・従属パターン
        if($this->isMainSub($townArea)) {
            return $this->ptnMainSub;
        }
        // 複数の主パターン
        if($this->isMultiMain($townArea)) {
            return $this->ptnMultiMain;
        }
        // 連番の主パターン
        if($this->isSerialMain($townArea)) {
            return $this->ptnSerialMain;
        }
        // 主と複数の住所単位パターン
        if($this->isMainMultiUnit($townArea)) {
            return $this->ptnMainMultiUnit;
        }
        // 主と連続した住所単位パターン
        if($this->isMainSerialUnit($townArea)) {
            return $this->ptnMainSerialUnit;
        }
        // 主・従属と連続した住所単位パターン
        if($this->isMainSubSerialUnit($townArea)) {
            return $this->ptnMainSubSerialUnit;
        }
        // 連続の主と・単一の従属町域パターン
        if($this->isSerialMainSub($townArea)) {
            return $this->ptnSerialMainSub;
        }

        // 該当なし
        abort(500, '無効なデータの検出: ' . $townArea);
    }

    /**
     * 町域が主・従属パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainSub(string $townArea): bool
    {
        $hasMain        = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasParent      = (bool)preg_match($this->regexParentheses, $townArea);
        $hasSeparater   = (bool)preg_match($this->regexSeparateTownArea, $townArea);
        $hasSerial      = (bool)preg_match($this->regexSerialTownArea, $townArea);
        $hasBullets     = (bool)preg_match($this->regexBullets, $townArea);
        $hasKeyBrackets = (bool)preg_match($this->regexKeyBrackets, $townArea);
        $hasNotSerialEndNumber = (bool)preg_match(
             $this->regexNotSerialEndNumber,
            $townArea
        );

        // 主・従属パターンは、主と複数の住所単位パターンを包括している
        // -> 主と複数の住所単位パターンに該当しないものを主・従属パターンとして処理
        // 〜を使用しているものはこの分割パターンに該当しないが、
        // 例外的に〜と、`・`もしくは`「」`を同時で使用する場合はここで処理する
        // -> 例：西瑞江（４丁目１～２番・１０～２７番、５丁目）
        // -> 例：折茂（今熊「２１３〜２３４、（中略）１５０４を除く」、大原、（以下略）
        return   $hasMain
              && $hasParent
              && $hasSeparater
              && (!$hasSerial ||$hasBullets || $hasKeyBrackets || $hasNotSerialEndNumber)
              && !$this->isMainMultiUnit($townArea);

    }

    /**
     * 町域が2つの主パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMultiMain(string $townArea): bool
    {
        $hasSub      = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasParent   = (bool)preg_match($this->regexParentheses, $townArea);
        $townAreaNum = count(explode('、', $townArea));

        return !$hasSub && !$hasParent && $townAreaNum > 1;

    }

    /**
     * 町域が連続の主パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isSerialMain(string $townArea): bool
    {
        $hasSub    = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasParent = (bool)preg_match($this->regexParentheses, $townArea);
        $hasSerial = (bool)preg_match($this->regexSerialTownArea, $townArea);

        return !$hasSub && !$hasParent && $hasSerial;

    }

    /**
     * 町域が主と複数の住所単位パターンか否か判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainMultiUnit(string $townArea): bool
    {
        $hasMain      = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasSeparater = (bool)preg_match($this->regexSeparateTownArea, $townArea);

        if($hasMain && $hasSeparater) {

            preg_match($this->regexInsideParentheses, $townArea, $units);
            $units       = explode('、', $units[0]);
            $unitNum     = count(explode('、', $townArea));
            $hasUnitName = (bool)preg_match(
                $this->regexUnits,
                $units[count($units)-1]
            );

            $isRemainNumOnly = true;
            array_pop($units);
            //カッコ内の、最後を除いた要素が数字以外ならこのパターンに該当しない
            foreach($units as $remainElement) {
                $isRemainNumOnly = !(bool)preg_match(
                    $this->regexNotNumber,
                    $remainElement
                );
            }
            return $unitNum > 1 && $hasUnitName && $isRemainNumOnly;
        } else {
            return false;
        }
    }

    /**
     * 町域が主と連続した住所単位パターンか否か判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainSerialUnit(string $townArea): bool
    {
        $hasMain      = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasSeparater = (bool)preg_match($this->regexSeparateTownArea, $townArea);

        if($hasMain && !$hasSeparater){

            $isMainSerial = (bool)preg_match(
                $this->regexSerialTownArea,
                $this->extractMatch(
                    $this->regexBeforeParentheses,
                    $townArea
                )
            );
            $isSubSerial  = (bool)preg_match(
                $this->regexSerialTownArea,
                $this->extractMatch(
                    $this->regexParentheses,
                    $townArea
                )
            );

            return !$isMainSerial && $isSubSerial ;

        }
        return false;
    }

    /**
     * 町域が主・従属と連続した住所単位パターンか否か判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainSubSerialUnit(string $townArea): bool
    {
        $hasMain               = (bool)preg_match(
            $this->regexBeforeParentheses,
            $townArea
        );
        $hasSeparater          = (bool)preg_match(
            $this->regexSeparateTownArea,
            $townArea
        );
        $hasSerial             = (bool)preg_match(
            $this->regexSerialTownArea,
            $townArea
        );
        $hasUnitName           = (bool)preg_match(
            $this->regexUnits,
            $townArea
        );
        $hasNotSerialEndNumber = (bool)preg_match(
             $this->regexNotSerialEndNumber,
            $townArea
        );

        return $hasMain && $hasSeparater && $hasSerial && !$hasNotSerialEndNumber;
    }

    /**
     * 連続の主と・単一の従属町域パターンか判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isSerialMainSub(string $townArea): bool
    {
        $hasMain   = (bool)preg_match($this->regexBeforeParentheses, $townArea);
        $hasParent = (bool)preg_match($this->regexParentheses, $townArea);

        if($hasMain && $hasParent) {
            $isMainSerial   =  (bool)preg_match(
                $this->regexSerialTownArea,
                $this->extractMatch(
                    $this->regexBeforeParentheses,
                    $townArea
                )
            );
            $isSubSeparater = (bool)preg_match(
                    $this->regexSeparateTownArea,
                    $this->extractMatch(
                        $this->regexParentheses,
                        $townArea
                    )
                );

            return $isMainSerial && !$isSubSeparater;
        }
        return false;
    }

    /**
     * 引数で渡された正規表現にマッチした文字列を抽出
     * @param  string $regex 正規表現
     * @param  string $str   抽出対象の文字列
     * @return string        抽出したメインの文字列
     */
    private function extractMatch(string $regex, string $str): string
    {
        if((bool)preg_match($regex, $str)){
            preg_match($regex, $str, $matchedArray);
            return $matchedArray[0];
        } else {
            return '';
        }
    }

    /**
     * 引数で渡された正規表現と区切り文字にマッチした文字列を配列にして抽出
     * @param  string $regex     正規表現
     * @param  string $str       抽出対象の文字列
     * @param  string $separator 区切り文字
     * @return array             抽出した配列
     */
    private function extractMatchArray($regex, $str, $separator): array
    {
        preg_match($regex, $str, $matchedArray);
        $subArray = explode($separator, $matchedArray[0]);
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
            if($attributeIndex != $this->idxTownArea &&
               $attributeIndex != $this->idxTownAreaKana
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
            $mergeTownArea[] = $row[$this->idxTownArea];
            $mergeTownAreaKana[] = $row[$this->idxTownAreaKana];
            $mergedRow = $row;
        }
        $mergedTownArea  =implode('',$mergeTownArea);
        $mergedTownAreaKana  =implode('',$mergeTownAreaKana);
        $mergedRow[$this->idxTownAreaKana] = $mergedTownAreaKana;
        $mergedRow[$this->idxTownArea] = $mergedTownArea;
        return $mergedRow;
    }
    public function mergeTownAreaKana(){

    }


}
