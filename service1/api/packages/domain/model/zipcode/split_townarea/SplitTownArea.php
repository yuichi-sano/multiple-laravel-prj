<?php
declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;
use packages\domain\model\zipcode\ZipCodeConstants;
use packages\domain\model\zipcode\ZipCodeAnalyzer;

abstract class SplitTownArea
{

    /**
     * @param  array zipcode
     * @return array splittedTownArea
     */
    public final function splitTownArea(array $zipcode):array
    {
        return $this->process(
                    $this->extract(
                        $zipcode[ZipCodeConstants::IDX_TOWNAREA]
                        ,$zipcode[ZipCodeConstants::IDX_TOWNAREA_KANA]
                    )
        );
    }

    /**
     * @param  string $townArea      町域名称
     * @param  string $townAreaKana  町域名称カナ
     * @return array
     */
    abstract protected function extract(string $townArea, string $townAreaKana): array;
    /**
     * @param  array  $townAreaInfo 町域名称（カナ）情報
     * @return array                加工した町域名称（カナ）情報
     */
    abstract protected function process(array $townAreaInfo): array;

    /* 共通利用メソッド */
    /**
     * 引数で渡された正規表現にマッチした文字列を抽出
     * @param  string $regex 正規表現
     * @param  string $str   抽出対象の文字列
     * @return string        抽出したメインの文字列
     */
    protected function extractMatch(string $regex, string $str): string
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
    protected function extractMatchArray($regex, $str, $separator): array
    {
        preg_match($regex, $str, $matchedArray);
        $subArray = explode($separator, $matchedArray[0]);
        return $subArray;
    }

    /**
     * 連番の町域名称（カナ）から連番を除いた町域名称情報を抽出する
     * @param  string $townArea 町域（カナ）名称
     * @return                  抽出した町域（カナ）名称
     */
    protected function extractBeforeNum($townArea): string
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

        if(TownAreaAnalyzer::hasHalfSizeNum($townArea)){
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
    protected function extractAfterNum(string $str): string
    {
        $extracted = '';
        if(TownAreaAnalyzer::hasHalfSizeNum($str)){
            $extracted = $this->extractMatch("/(?<=[0-9])[^0-9]+/u", $str);
        } else {
            $extracted = $this->extractMatch("/(?<=[０-９])[^０-９]+/u", $str);
        }
        return $extracted;
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
    protected function generateTownAreaStartToEnd(
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
     * 町域名称カナから連番の始点と終点を抽出する 
     * @param  string $townArea     町域情報
     * @return array                連番の始点と終点
     */
    protected function extractSerialStartAndEnd(string $townAreaKana): array
    {

        $start = $this->extractMatch(
            ZipCodeConstants::REGEX_BEFORE_SERIAL_TOWNAREA_KANA,
            $townAreaKana
        );
        // 稀に1-97-116といったような引数が来た場合に97-116が連番に該当する
        // その際は単純な数値の抽出では対応できない
        if(ZipCodeAnalyzer::hasAfterSerialTownAreaKana($start)) {
            $start = $this->extractMatch(
                ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA_KANA,
                $start
            );
        } else{
            // こちらに該当するものが大半
            $start = $this->extractMatch(
                ZipCodeConstants::REGEX_NUMBER_HALF,
                $start
            );
        }
        // 稀に1-97-116といったような引数が来た場合にpreg_matchでは97しか抽出ができない
        preg_match_all(ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA_KANA, $townAreaKana, $end);
        $end = $this->extractMatch(
            ZipCodeConstants::REGEX_NumberHalf,
            $end[0][count($end[0])-1]
        );

        return ['start' => $start, 'end' => $end];
    }


}
