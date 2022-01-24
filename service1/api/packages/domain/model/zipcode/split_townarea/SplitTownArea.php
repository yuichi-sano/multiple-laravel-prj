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


}
